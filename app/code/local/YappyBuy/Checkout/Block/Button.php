<?php

class YappyBuy_Checkout_Block_Button extends Mage_Core_Block_Abstract
{
    
    
    protected function _toHtml()
    {
		if($this->helper('ybcheckout')->isEnabled()){
			return '<div class="ybuy-button">'.$this->helper('ybcheckout')->getButton().'</div>';
		}
        return '';
    }	
	
}
