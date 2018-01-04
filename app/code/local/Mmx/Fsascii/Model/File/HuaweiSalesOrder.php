<?php

class Mmx_Fsascii_Model_File_HuaweiSalesOrder extends Mmx_Fsascii_Model_File {

    public function _getRunControlRecord() {

        $line = new Mmx_Fsascii_Model_Format_RunControlRecord();
        $line->setCode('SR25')
            ->setSalesOrderNumber($this->order->getIncrementId())
            ->setHeader('Huawei Sales Order');

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
     * @return Mmx_Fsascii_Model_Format_SalesOrderDetail[]
     */
    public function _getSalesOrderDetailCollection() {

        $i = 1;
        /* @var $orderItem Mage_Sales_Model_Order_Item */
        foreach ($this->order->getAllItems() as $orderItem) {

            $product = Mage::getModel('catalog/product')->load($orderItem->getProductId());

            $line = new Mmx_Fsascii_Model_Format_SalesOrderDetail();
            $line->setSalesorder(sprintf('"%s"', $this->order->getIncrementId()))
                ->setSequence(sprintf('%04d', $i))
                ->setProduct($product->getSku())
                ->setType('P')
                ->setWarehouse(11)
                ->setQty(number_format($orderItem->getQtyOrdered()))
                ->setAllocatedQty(0);
            $lines[] = $line;
            $i++;
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

    public function generateAscii() {

        $lines = array();
        $lines[] = $this->_getRunControlRecord();
        $lines[] = $this->_getSalesOrderHeader();
        $lines[] = $this->_getSalesOrderDetails();
        $lines[] = $this->_getSalesOrderAllocations();

        $ascii = implode(self::MMX_FSASCII_MODEL_FILE_EOL, $lines);
        return $ascii;
    }

    public function isValid() {

        $is_huawei_store = false;
        if ($this->order->getStoreId() == 5) {  // Huawei
            $is_huawei_store = true;
        }

        return $is_huawei_store;
    }

}