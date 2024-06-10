<?php

class YappyBuy_Checkout_Model_OrderApi extends Mage_Sales_Model_Order_Api
{
    
    
    public function addComment($orderIncrementId, $status, $comment = '', $notify = false)
    {
        $order = $this->_initOrder($orderIncrementId);
		
		if($order->getPayment()->getMethod()=='yappybuy' && !$order->getEmailSent()){
			try {
				$order->queueNewOrderEmail();
			} catch (Exception $e) {
				Mage::logException($e);
			}
		}		
		return parent::addComment($orderIncrementId, $status, $comment, $notify);		
        
    }


}
