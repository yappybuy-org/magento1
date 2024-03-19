<?php


class YappyBuy_Checkout_Model_Cart_Item_Api extends Mage_Checkout_Model_Api_Resource
{


    public function remove($quoteId, $items, $store = null)
    {
		
		$quote = $this->_getQuote($quoteId, $store);
		try{
			foreach($items as $item){
                $quoteItem = $quote->getItemById($item->item_id);				
                if (!$quoteItem) {
                    $this->_fault('items_not_exists');
                }					
        		$quote->deleteItem($quoteItem);		
			}
			$quote->collectTotals();
			$quote->save();
		}catch (Exception $e) {
            $this->_fault('items_remove_product_fault', $e->getMessage());
        }         
        return true;
    }


    public function update($quoteId, $items, $store = null)
    {
		
        $quote = $this->_getQuote($quoteId, $store);
		try{
			foreach($items as $item){
                $quoteItem = $quote->getItemById($item->item_id);
                if (!$quoteItem || $quoteItem->getParentItemId()) {
                    $this->_fault('items_not_exists');
                }		
				if($item->qty>0){
					$quoteItem->setQty($item->qty);
				}else{
					$quote->deleteItem($quoteItem);
				}
			}
			$quote->collectTotals();
			$quote->save();
		}catch (Exception $e) {
            $this->_fault('items_update_product_fault', $e->getMessage());
        } 
        return true;
    }

   
}
