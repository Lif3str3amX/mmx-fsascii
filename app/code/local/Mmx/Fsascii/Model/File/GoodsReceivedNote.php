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
     * Generates a filename based on order create date
     * 
     * @return string
     */
    public function generateFilename() {

        $datetime = date('dmyHi', strtotime($this->order->getCreatedAt())); // e.g. 2016-11-02 18:24:23 -> 0211161824
        $filename = sprintf('GRN %s.txt', $datetime);

        return $filename;
    }

    public function generateAscii() {

        $lines = array();
        $lines[] = $this->_getRunControlRecord();
        $lines[] = $this->_getGoodsReceivedNote();

        $ascii = implode(self::MMX_FSASCII_MODEL_FILE_EOL, $lines);
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
