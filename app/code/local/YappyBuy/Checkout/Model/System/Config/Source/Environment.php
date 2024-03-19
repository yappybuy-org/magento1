<?php



class YappyBuy_Checkout_Model_System_Config_Source_Environment
{

    public function toOptionArray()
    {
        return array(
            array('value' => 0, 'label'=>Mage::helper('adminhtml')->__('Test')),
            array('value' => 1, 'label'=>Mage::helper('adminhtml')->__('Live'))
        );
    }

} 