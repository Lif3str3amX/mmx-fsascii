<?php

class Mmx_Fsascii_Model_File_GoodsReceivedNote extends Mmx_Fsascii_Model_File {

    public function _getRunControlRecord() {

        $line = new Mmx_Fsascii_Model_Format_RunControlRecord();
        $line->setCode('25')
                ->setSalesOrderNumber('')
                ->setHeader('Goods Received Note');
        
        return $line;
    }

    public function _getGoodsReceivedNote() {

        // Get custom order attrs for this order
        $amorderattr = Mage::getModel('amorderattr/attribute')->load($this->order->getId(), 'order_id');
        
        $date = date('d/m/y', strtotime($this->order->getCreatedAt()));
        $schemeref = $amorderattr->getSchemeref();

        $line = new Mmx_Fsascii_Model_Format_GoodsReceivedNote();
        $line->setWarehouse(11)
                ->setProduct('INBTRESERVATION')
                ->setQty('1.00')
                ->setReceiptDate($date)
                ->setComment('BT Scheme Reservation')
                ->setBin('INDIGO')
                ->setSerial($schemeref);
        
        return $line;

    }

    /**
     * Generates a filename based on the scheme reference
     * 
     * @return string
     */
    public function generateFilename() {

        // Get custom order attrs for this order
        $amorderattr = Mage::getModel('amorderattr/attribute')->load($this->order->getId(), 'order_id');

        $schemeref = preg_replace('/[^a-zA-Z0-9_-]/', '_', $amorderattr->getSchemeref());
        $filename = sprintf('%s.txt', $schemeref);

        return $filename;
    }

    public function generateAscii() {

        $lines = array();
        $lines[] = $this->_getRunControlRecord();
        $lines[] = $this->_getGoodsReceivedNote();

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
