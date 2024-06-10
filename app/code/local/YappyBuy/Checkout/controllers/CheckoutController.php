<?php

class YappyBuy_Checkout_CheckoutController extends Mage_Core_Controller_Front_Action {

	

	
    public function configAction() {
		return $this->getResponse()
					->setHeader('Content-type', 'application/json;charset=utf-8', true)
					->setBody(Mage::helper('core')->jsonEncode(
						array(
							'checkoutUrl' => Mage::getUrl('yb_checkout/checkout/start',array('_secure'=>true)),
							'addProductUrl' => Mage::getUrl('yb_checkout/checkout/ajaxAdd',array('_secure'=>true)),
							'mode' => Mage::helper('ybcheckout')->getMode(),
							'activateLabel'=>Mage::helper('ybcheckout')->__('Checkout was activated, click here to return to your checkout window.')
						)));
    }

    public function startAction() {
		
		$curl=Mage::helper('ybcheckout/curl');

		$data=array(
			"reference"=>$this->_getCart()->getQuote()->getId()
		);
				
		$result=$curl->curlRequest('POST','api/v1/carts',$data); 		
		if(is_array($result) && isset($result['uri'])){
			return $this->getResponse()->setHeader('Content-type', 'application/json;charset=utf-8', true)->setBody(Mage::helper('core')->jsonEncode(array('url'=>$result['uri'], 'status'=>'success') ));
		}else{
			return $this->getResponse()->setHeader('Content-type', 'application/json;charset=utf-8', true)->setBody(Mage::helper('core')->jsonEncode($result /* array('url'=>"test") */));
		}
		
    }
    
    protected function _initProduct()
    {
        $productId = (int) $this->getRequest()->getParam('product');
        if ($productId) {
            $product = Mage::getModel('catalog/product')
                ->setStoreId(Mage::app()->getStore()->getId())
                ->load($productId);
            if ($product->getId()) {
                return $product;
            }
        }
        return false;
    }
	
    protected function _getCart()
    {
        return Mage::getSingleton('checkout/cart');
    }

    protected function _getSession()
    {
        return Mage::getSingleton('checkout/session');
    }
    
    protected function _getQuote()
    {
        return $this->_getCart()->getQuote();
    }
	
    public function ajaxAddAction()
    {
		$result = array();
		$errorMessage='';
        if (!$this->_validateFormKey()) {
			$result['status'] = 'error';
            $result['message'] = $this->__('Invalid form key. Refresh page.');
        }else{
			$cart   = $this->_getCart();
			$params = $this->getRequest()->getParams();		
			
			try {
			    if (isset($params['qty'])) {
					$filter = new Zend_Filter_LocalizedToNormalized(array('locale' => Mage::app()->getLocale()->getLocaleCode()));
					$params['qty'] = $filter->filter($params['qty']);
				}
				
				$product = $this->_initProduct();
				
				if(!$product){
					$result['status'] = 'error';
					$result['message'] = $this->__('Cannot add the product to shopping cart.');
				}else{
					$cart->addProduct($product, $params);
				
					$cart->save();
				
					if (!$this->_getSession()->getNoCartRedirect(true)) {
						if (!$cart->getQuote()->getHasError()) {
							$result['status'] = 'success';							
						}else{
							$result['status'] = 'error';
							$result['message'] = $this->__('Cannot add the product to shopping cart.');
						}						
					}
				}
			} catch (Exception $e) {
				$result['status'] = 'error';
				$result['message'] = $this->__('Cannot add the product to shopping cart.');
			}			 
		}
		if($result['status'] == 'success'){
			$curl=Mage::helper('ybcheckout/curl');

			$data=array(
				"reference"=>$this->_getCart()->getQuote()->getId()
			);
					
			$result=$curl->curlRequest('POST','api/v1/carts',$data); 		
			if(is_array($result) && isset($result['uri'])){
				return $this->getResponse()->setHeader('Content-type', 'application/json;charset=utf-8', true)->setBody(Mage::helper('core')->jsonEncode(array('url'=>$result['uri'], 'status'=>'success') ));
			}else{
				return $this->getResponse()->setHeader('Content-type', 'application/json;charset=utf-8', true)->setBody(Mage::helper('core')->jsonEncode($result /* array('url'=>"test") */));
			}	
		}else{
			$this->getResponse()->setHeader('Content-type', 'application/json');
			$this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));		
		}
	}
	
	public function successAction(){
		$incrementId=$this->getRequest()->getParam('order_id',0);		
		if($incrementId){
			$order = Mage::getModel('sales/order')->loadByIncrementId($incrementId);		
			if($order && $order->getId()){				
				$checkoutSession = Mage::getSingleton('checkout/session');				 
				$checkoutSession->setLastQuoteId($order->getQuoteId())
					->setLastSuccessQuoteId($order->getQuoteId())
					->clearHelperData();
				$checkoutSession->setLastOrderId($order->getId());
				
				$this->_redirect('checkout/onepage/success');				
			}else{
				$this->_redirect('checkout/cart');			
			}
		}else{
			$this->_redirect('checkout/cart');			
		}
	}
	
}