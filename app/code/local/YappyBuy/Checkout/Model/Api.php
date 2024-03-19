<?php


class YappyBuy_Checkout_Model_Api extends Mage_Checkout_Model_Cart_Api										  
{
	
    public function totals($quoteId, $store = null)
    {
        $quote = $this->_getQuote($quoteId, $store);
        $totals = $quote->getTotals();

        $totalsResult = array();
        foreach ($totals as $total) {	
            $totalsResult[] = array(
                "title" => (1?$total->getCode():$total->getTitle()),				
                "amount" => $total->getValue()
            );
        }
        return $totalsResult;
    }	
	
}