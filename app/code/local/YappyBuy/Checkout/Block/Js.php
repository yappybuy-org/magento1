<?php

class YappyBuy_Checkout_Block_Js extends Mage_Core_Block_Abstract
{
    
    
    protected function _toHtml()
    {
		if($this->helper('ybcheckout')->isEnabled()){
			return 
"<script type=\"text/javascript\">
//<![CDATA[
new ybCheckout('".
Mage::getUrl('yb_checkout/checkout/config',array('_secure'=>true))
."');
//]]>
</script>";
		}
        return '';
    }	
	
}