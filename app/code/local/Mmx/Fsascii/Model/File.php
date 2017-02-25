<?php

class Mmx_Fsascii_Model_File {

    /**
     * @var Mage_Sales_Model_Order
     */
    protected $order;

    public function setOrder($order) {
        $this->order = $order;
        return $this;
    }

    public function getOrder() {
        return $this->order;
    }
    
    /**
     * Determines if a product contains serial numbers without relying on hard-coded SKUs
     * 
     * @param Mage_Sales_Model_Order_Item $orderItem
     * @return boolean
     */
    public function isSerialisedItem($orderItem)
    {
        $is_serialised_product = false;

        $productOptions = $orderItem->getProductOptions();
        foreach ($productOptions as $productOption) {
            foreach ($productOption as $option) {
                if (isset($option['label'])) {
                    if ($option['label'] == 'Serial Code') {
                        $is_serialised_product = true;
                    }
                }
            }
        }
        
        return $is_serialised_product;
    }
    
    /*
    public function isSerialisedItem($product) {
        if (strtoupper($product->getSku()) == 'INCIENABOM' || strtoupper($product->getSku()) == 'INBTRESERVATION') {    // these are to be displayed in IndigoCienaSalesOrder
            return true;
        }
        else {
            return false;
        }
    }
    */

}
