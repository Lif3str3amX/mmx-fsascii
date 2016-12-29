<?php

class Mmx_Fsascii_Model_File_BtSalesOrder extends Mmx_Fsascii_Model_File {

    public function _getRunControlRecord() {

        $line = new Mmx_Fsascii_Model_Format_RunControlRecord();
        $line->setCode('SR25')
                ->setSalesOrderNumber($this->order->getIncrementId())
                ->setHeader('Sales Order');
        
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

        // Get custom order attrs for this order
        $amorderattr = Mage::getModel('amorderattr/attribute')->load($this->order->getId(), 'order_id');
        
        $attributes = array(
            'Scheme ' . $amorderattr->getSchemeref(),
            'Site ' . $amorderattr->getSchemesite(),
            'Route ' . $amorderattr->getRouteid(),
            'Driver ' . $amorderattr->getSchemedriver(),
            'Comment ' . $amorderattr->getBtcomments()
        );

        $i = 1;
        /* @var $orderItem Mage_Sales_Model_Order_Item */
        foreach ($this->order->getAllItems() as $orderItem) {

            $product = Mage::getModel('catalog/product')->load($orderItem->getProductId());

            $line = new Mmx_Fsascii_Model_Format_SalesOrderDetail();
            $line->setSalesorder(sprintf('="%s"', $this->order->getIncrementId()))
                    ->setSequence(sprintf('%04d', $i))
                    ->setProduct($product->getSku())
                    ->setType('P')
                    ->setWarehouse(11)
                    ->setQty(number_format($orderItem->getQtyOrdered()))
                    ->setAllocatedQty(0);
            $lines[] = $line;
            $i++;
        }
        
        
        // Additional order attributes e.g. schemeref, routeid, etc
        foreach ($attributes as $attr) {
            
            $attr = Mmx_Fsascii_Helper_Data::sanitize($attr);
            $long_description = substr($attr, 0, 40); // 40 max
            
            $line = new Mmx_Fsascii_Model_Format_SalesOrderDetail();
            $line->setSalesorder(sprintf('="%s"', $this->order->getIncrementId()))
                    ->setSequence(sprintf('%04d', $i))
                    ->setType('C')
                    ->setWarehouse('d')
                    ->setLongDescription(sprintf('"%s"', $long_description));
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

        $filename = sprintf('BT %s.txt', $increment_id);
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
    
    public function isValid() {

        $is_bt_store = false;
        if ($this->order->getStoreId() == 2) {  // BT
            $is_bt_store = true;
        }

        return $is_bt_store;
    }

}