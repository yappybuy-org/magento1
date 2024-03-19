<?php

class YappyBuy_Checkout_Model_Method_Yappybuy extends Mage_Payment_Model_Method_Abstract
{



    protected $_canAuthorize   = true;
	protected $_canUseCheckout = false;
	protected $_canManageRecurringProfiles  = false;
	protected $_canOrder                    = true;
	
    protected $_code = 'yappybuy';
	


    public function isAvailable($quote = null)
    {
        return !empty($quote) && Mage::app()->getStore()->roundPrice($quote->getGrandTotal()) > 0;
    }


    public function getConfigPaymentAction()
    {
        return $this->getConfigData('order_status') == 'pending' ? null : parent::getConfigPaymentAction();
    }
}
