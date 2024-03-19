<?php



class YappyBuy_Checkout_Model_System_Config_Source_Mode
{

    public function toOptionArray()
    {
        return array(
            array('value' => 0, 'label'=>Mage::helper('adminhtml')->__('Same Tab')),
            array('value' => 'popup', 'label'=>Mage::helper('adminhtml')->__('PopUp')),
			array('value' => 'newtab', 'label'=>Mage::helper('adminhtml')->__('New Tab'))
        );
    }

} 