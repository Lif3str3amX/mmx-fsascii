<?php

class Mmx_Fsascii_Model_File_IndigoSalesOrder extends Mmx_Fsascii_Model_File {

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
        return implode(self::MMX_FSASCII_MODEL_FILE_EOL, $lines);
    }

    /**
     *
     * @return string
     */
    public function _getSalesOrderAllocations() {
        $lines = $this->_getSalesOrderAllocationCollection();
        return implode(self::MMX_FSASCII_MODEL_FILE_EOL, $lines);
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
     * @return int
     */
    public function getSalesOrderAllocationLineCount() {
        return count($this->_getSalesOrderAllocationCollection());
    }
    
    /**
     *
     * @return Mmx_Fsascii_Model_Format_SalesOrderDetail[]
     */
    public function _getSalesOrderDetailCollection() {

        $i = 1;
        
        /* @var $orderItem Mage_Sales_Model_Order_Item */
        foreach ($this->order->getAllItems() as $orderItem) {

            $product = Mage::getModel('catalog/product')->load($orderItem->getProductId());

            // If serialised, create a line for each serial number, else if not serialised, one line only
            if ($this->isSerialisedItem($orderItem)) {

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
                                            ->setProduct(strtoupper($product->getSku()))
                                            ->setType('P')
                                            ->setWarehouse(11)
                                            ->setQty(1)
                                            ->setAllocatedQty(0);
                                    $lines[] = $line;
                                    $i++;
                                }
                            }
                        }
                    }
                }
                
            }
            else {

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

        // Add this Indigo Sales Order Number
        $line = new Mmx_Fsascii_Model_Format_SalesOrderDetail();
        $line->setSalesorder(sprintf('="%s"', $this->order->getIncrementId()))
                ->setSequence(sprintf('%04d', $i))
                ->setType('C')
                ->setWarehouse('d')
                ->setLongDescription(sprintf('"Indigo Order %s"', $this->order->getIncrementId()));
        $lines[] = $line;
        $i++;

        // Get custom order attrs for this order
        $amorderattr = Mage::getModel('amorderattr/attribute')->load($this->order->getId(), 'order_id');

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
        
        // Add Ciena Sales Order Number (would have been optionally created by Mmx Splitter if this order contains serialised products)
        // Defunct, no more order splitting
        /*
        $ciena_increment_id = $amorderattr->getCienaIncrementId();
        if ($ciena_increment_id) {
            $line = new Mmx_Fsascii_Model_Format_SalesOrderDetail();
            $line->setSalesorder(sprintf('="%s"', $this->order->getIncrementId()))
                    ->setSequence(sprintf('%04d', $i))
                    ->setType('C')
                    ->setWarehouse('d')
                    ->setLongDescription(sprintf('"Ciena Sage Order %s"', $ciena_increment_id));
            $lines[] = $line;
            $i++;
        }
        */
        
        // Add BT Sales Order Number (if found by Mmx Updater)
        $bt_increment_id = $amorderattr->getBtIncrementId();
        if ($bt_increment_id) {
            $line = new Mmx_Fsascii_Model_Format_SalesOrderDetail();
            $line->setSalesorder(sprintf('="%s"', $this->order->getIncrementId()))
                    ->setSequence(sprintf('%04d', $i))
                    ->setType('C')
                    ->setWarehouse('d')
                    ->setLongDescription(sprintf('"BT Sage Order %s"', $bt_increment_id));
            $lines[] = $line;
            $i++;
        }

        // Line by line breakdown of serialised items showing products with serial numbers in this order
        foreach ($this->order->getAllItems() as $orderItem) {

            if ($this->isSerialisedItem($orderItem)) {            

                $product = Mage::getModel('catalog/product')->load($orderItem->getProductId());

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
     * 
     * @return string
     */    
    public function _getSalesOrderAllocationCollection() {
        
        $lines = array();
        
        $i = 1;
        // Line by line breakdown showing hard/soft allocations
        foreach ($this->order->getAllItems() as $orderItem) {

            $product = Mage::getModel('catalog/product')->load($orderItem->getProductId());

            // Products with serial numbers in this order
            if ($this->isSerialisedItem($orderItem)) {

                $productOptions = $orderItem->getProductOptions();
                foreach ($productOptions as $productOption) {
                    foreach ($productOption as $option) {
                        if (isset($option['label'])) {
                            if ($option['label'] == 'Serial Code') {
                                $serials = $option['value'];
                                $arrSerials = explode(',', $serials);

                                foreach ($arrSerials as $serial) {

                                    $line = new Mmx_Fsascii_Model_Format_SalesOrderAllocation();
                                    $line->setSalesOrder(sprintf('"%s"', $this->order->getIncrementId()))
                                            ->setSalesOrderLineNumber(sprintf('%04d', $i))
                                            ->setWarehouse(11)
                                            ->setProduct(strtoupper($product->getSku()))
                                            ->setSerialNumber(trim($serial))
                                            ->setAllocationType('H')
                                            ->setAllocatedQty(1);

                                    $lines[] = $line;
                                    $i++;
                                }
                            }
                        }
                    }
                }
                
            }
            else {
                // Non-serialised products
                $line = new Mmx_Fsascii_Model_Format_SalesOrderAllocation();
                $line->setSalesOrder(sprintf('"%s"', $this->order->getIncrementId()))
                        ->setSalesOrderLineNumber(sprintf('%04d', $i))
                        ->setWarehouse(11)
                        ->setProduct(strtoupper($product->getSku()))
                        ->setAllocationType('S')
                        ->setAllocatedQty(number_format($orderItem->getQtyOrdered()));

                $lines[] = $line;
                $i++;                
            }
        }

        return $lines;
    }    
    
    /**
     * Generates a filename based on store and increment_id
     *
     * @return string
     */
    public function generateFilename() {

        $increment_id = $this->order->getIncrementId();

        $filename = sprintf('SOA %s.txt', $increment_id);
        return $filename;
    }

    /**
     *
     * @return string
     */
    public function generateAscii() {

        $lines = array();
        $lines[] = $this->_getRunControlRecord();
        $lines[] = $this->_getSalesOrderHeader();
        $lines[] = $this->_getSalesOrderDetails();
        $lines[] = $this->_getSalesOrderAllocations();

        $ascii = implode(self::MMX_FSASCII_MODEL_FILE_EOL, $lines);
        return $ascii;
    }

    /**
     * An Indigo portal order can contain a mix of standard Indigo (non-serialised) products,
     * and INCIENABOM/INBTRESERVATION serialised products.
     * 
     * This Indigo Sales Order should only list standard Indigo products.
     * INCIENABOM/INBTRESERVATION are split into a separate order using the
     * Mmx_Processor extension
     * 
     * Returns true if order placed in Indigo store and contains some standard
     * Indigo products (not just INCIENABOM/INBTRESERVATION serialised products)
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