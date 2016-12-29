<?php

class Mmx_Fsascii_Model_File_IndigoCienaSalesOrder extends Mmx_Fsascii_Model_File {

    public function _getRunControlRecord() {

        $line = new Mmx_Fsascii_Model_Format_RunControlRecord();
        $line->setCode('SR25')
                ->setSalesOrderNumber($this->order->getIncrementId())
                ->setHeader('Indigo Sales Order');
        
        return $line;
    }

    public function _getSalesOrderHeader() {

        $date = date('d/m/y', strtotime($this->order->getCreatedAt()));
        $shippingAddress = $this->order->getShippingAddress();
        
        $line = new Mmx_Fsascii_Model_Format_SalesOrderHeader();
        $line->setSalesorder(sprintf('="%s"', $this->order->getIncrementId()))
                ->setCustomer('ZZINDIGO')
                ->setDeliveryAddr1(sprintf('"%s %s"', $shippingAddress->getFirstname(), $shippingAddress->getLastname()))
                ->setDeliveryAddr2($shippingAddress->getStreet1() ? sprintf('"%s"', $shippingAddress->getStreet1()) : '""')
                ->setDeliveryAddr3($shippingAddress->getCity() ? sprintf('"%s"', $shippingAddress->getCity()) : '""')
                ->setDeliveryAddr4($shippingAddress->getRegion() ? sprintf('"%s"', $shippingAddress->getRegion()) : '""')
                ->setDeliveryAddr5($shippingAddress->getPostcode() ? sprintf('"%s"', $shippingAddress->getPostcode()) : '""')
                ->setDateEntered($date)
                ->setDateReceived($date)
                ->setDateRequired($date)
                ->setOrderStatus(5)
                ->setCtolnolines($this->getSalesOrderDetailsLineCount());
        
        return $line;
    }    

    /**
     * 
     * @return string
     */
    public function _getSalesOrderDetails() {
        $lines = $this->_getSalesOrderDetailCollection();
        return implode(PHP_EOL, $lines);
    }

    /**
     * 
     * @return int
     */
    public function getSalesOrderDetailsLineCount() {
        return count($this->_getSalesOrderDetailCollection());
    }

    /**
     * 
     * @return Mmx_Fsascii_Model_Format_SalesOrderDetail[]
     */
    public function _getSalesOrderDetailCollection() {

        $lines = array();
        
        $i = 1;
        /* @var $orderItem Mage_Sales_Model_Order_Item */
        foreach ($this->order->getAllItems() as $orderItem) {
            
            $product = Mage::getModel('catalog/product')->load($orderItem->getProductId());
            if (strtoupper($product->getSku()) == 'INCIENABOM' || strtoupper($product->getSku()) == 'INBTRESERVATION') {

                $line = new Mmx_Fsascii_Model_Format_SalesOrderDetail();
                $line->setSalesorder(sprintf('="%s"', $this->order->getIncrementId()))
                        ->setSequence(sprintf('%04d', $i))
                        ->setProduct(strtoupper($product->getSku()))
                        ->setType('P')
                        ->setWarehouse(11)
                        ->setQty(number_format($orderItem->getQtyOrdered()))
                        ->setAllocatedQty(0);
                $lines[] = $line;
                $i++;
            }
        }        

        // Get custom order attrs for this order
        $amorderattr = Mage::getModel('amorderattr/attribute')->load($this->order->getId(), 'order_id');

        
        // If this Ciena order was split from a mixed Indigo order, show the source Indigo order
        $indigo_increment_id = $amorderattr->getIndigoIncrementId();
        if ($indigo_increment_id) {
            $line = new Mmx_Fsascii_Model_Format_SalesOrderDetail();
            $line->setSalesorder(sprintf('="%s"', $this->order->getIncrementId()))
                    ->setSequence(sprintf('%04d', $i))
                    ->setType('C')
                    ->setWarehouse('d')
                    ->setLongDescription(sprintf('"Indigo Order %s"', $indigo_increment_id));
            $lines[] = $line;
            $i++;        
        }
        else {
            // Add this Indigo Sales Order Number
            $line = new Mmx_Fsascii_Model_Format_SalesOrderDetail();
            $line->setSalesorder(sprintf('="%s"', $this->order->getIncrementId()))
                    ->setSequence(sprintf('%04d', $i))
                    ->setType('C')
                    ->setWarehouse('d')
                    ->setLongDescription(sprintf('"Indigo Order %s"', $this->order->getIncrementId()));
            $lines[] = $line;
            $i++;                            
        }
        
        
        // Add Scheme Reference
        $schemeref = $amorderattr->getSchemeref();
        $line = new Mmx_Fsascii_Model_Format_SalesOrderDetail();
        $line->setSalesorder(sprintf('="%s"', $this->order->getIncrementId()))
                ->setSequence(sprintf('%04d', $i))
                ->setType('C')
                ->setWarehouse('d')
                ->setLongDescription(sprintf('"Scheme %s"', $schemeref));
        $lines[] = $line;
        $i++;

        // Add Order Staging
        $orderstaging = $amorderattr->getOrderstaging();
        $_orderstaging = 'No';  // 6
        if ($orderstaging == 7) {   // Yes
            $_orderstaging = 'Yes';
        }
        $line = new Mmx_Fsascii_Model_Format_SalesOrderDetail();
        $line->setSalesorder(sprintf('="%s"', $this->order->getIncrementId()))
                ->setSequence(sprintf('%04d', $i))
                ->setType('C')
                ->setWarehouse('d')
                ->setLongDescription(sprintf('"Staging %s"', $_orderstaging));
        $lines[] = $line;
        $i++;
        
        // Add Indigo Shipping
        $indigoship = $amorderattr->getIndigoship();
        switch ($indigoship) {
            case 8:
                $_indigoship = 'Saturday delivery';
                break;
            case 9:
                $_indigoship = '12:00';
                break;
            case 10:
                $_indigoship = 'Standard';
                break;
            case 11:
                $_indigoship = '9:00';
                break;
            case 12:
                $_indigoship = '10:00';
                break;
        }
        $line = new Mmx_Fsascii_Model_Format_SalesOrderDetail();
        $line->setSalesorder(sprintf('="%s"', $this->order->getIncrementId()))
                ->setSequence(sprintf('%04d', $i))
                ->setType('C')
                ->setWarehouse('d')
                ->setLongDescription(sprintf('"Shipping %s"', $_indigoship));
        $lines[] = $line;
        $i++;
        
        // Line by line breakdown showing products with serial numbers in this order
        foreach ($this->order->getAllItems() as $orderItem) {

            $product = Mage::getModel('catalog/product')->load($orderItem->getProductId());
            if (strtoupper($product->getSku()) == 'INCIENABOM' || strtoupper($product->getSku()) == 'INBTRESERVATION') {

                $productOptions = $orderItem->getProductOptions();
                foreach ($productOptions as $productOption) {
                    foreach ($productOption as $option) {
                        if (isset($option['label'])) {
                            if ($option['label'] == 'Serial Code') {
                                $serials = $option['value'];
                                $arrSerials = explode(',', $serials);

                                foreach ($arrSerials as $serial) {

                                    $line = new Mmx_Fsascii_Model_Format_SalesOrderDetail();
                                    $line->setSalesorder(sprintf('="%s"', $this->order->getIncrementId()))
                                            ->setSequence(sprintf('%04d', $i))
                                            ->setType('C')
                                            ->setWarehouse('d')
                                            ->setLongDescription(sprintf('"%s %s"', strtoupper($product->getSku()), trim($serial)));

                                    $lines[] = $line;
                                    $i++;
                                }
                            }
                        }
                    }
                }
            }
        }
        
        return $lines;
    }

    /**
     * Generates a filename based on order date and time
     * 
     * @return string
     */
    public function generateFilename() {

        $datetime = date('dmyHi', strtotime($this->order->getCreatedAt()));
        
        $filename = sprintf('Ciena %s.txt', $datetime);
        return $filename;
    }

    public function generateAscii() {

        $lines = array();
        $lines[] = $this->_getRunControlRecord();
        $lines[] = $this->_getSalesOrderHeader();
        $lines[] = $this->_getSalesOrderDetails();

        $ascii = implode(PHP_EOL, $lines);
        return $ascii;
    }

    /**
     * Returns true if Indigo store and contains *only* INCIENABOM/INBTRESERVATION serialised products
     * Also see http://magento.stackexchange.com/questions/7246/dashes-added-to-sku-within-order
     * 
     * @return boolean
     */
    public function isValid() {

        $is_indigo_store = false;
        if ($this->order->getStoreId() == 3) {  // Indigo
            $is_indigo_store = true;
        }

        return $is_indigo_store;
    }    
    
    
}
