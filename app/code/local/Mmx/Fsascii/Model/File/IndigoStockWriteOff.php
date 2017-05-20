<?php

class Mmx_Fsascii_Model_File_IndigoStockWriteOff extends Mmx_Fsascii_Model_File {

    public function _getRunControlRecord() {

        $line = new Mmx_Fsascii_Model_Format_RunControlRecord();
        $line->setCode('25')
                ->setSalesOrderNumber('000000')
                ->setHeader('Stock Write Off');
        
        return $line;
    }

    public function _getInventoryAdjustmentsExtended() {

        // http://stackoverflow.com/questions/21682868/how-to-get-order-cancel-date-in-magento
        $historyCollection = $this->order->getStatusHistoryCollection();
        foreach ($historyCollection as $history) {
            if ($history->getStatus() === Mage_Sales_Model_Order::STATE_CANCELED) {
                $orderCancelDate = $history->getCreatedAt();
            }
        }
        // $date = date('d/m/y', strtotime($orderCancelDate)); cancelation history hasn't been saved at this point in the dispatch cycle
        $date = date('d/m/y', time());
        
        // Get custom order attrs for this order
        $amorderattr = Mage::getModel('amorderattr/attribute')->load($this->order->getId(), 'order_id');
        $schemeref = $amorderattr->getSchemeref();
        
        $line = new Mmx_Fsascii_Model_Format_InventoryAdjustmentExtended();
        $line->setWarehouse(11)
                ->setProduct('INBTRESERVATION')
                ->setQty('-1.00')
                ->setAdjustDate($date)
                ->setComment('Reservation Canceled')
                ->setBin('INDIGO')
                ->setSerial($schemeref);
        
        return $line;
    }

    /**
     * Generates a filename based on order date and time
     * 
     * @return string
     */
    public function generateFilename() {

        $datetime = date('dmyHi', strtotime($this->order->getCreatedAt()));
        
        $filename = sprintf('SWO_%s.txt', $datetime);
        return $filename;
    }

    public function generateAscii() {

        $lines = array();
        $lines[] = $this->_getRunControlRecord();
        $lines[] = $this->_getInventoryAdjustmentsExtended();

        $ascii = implode(self::MMX_FSASCII_MODEL_FILE_EOL, $lines);
        return $ascii;
    }
    
    /**
     * SWO file can be created if order is cancelled ONLY via the BT store
     * Confirmed by JP on 24/Feb/2017
     * 
     * @return boolean
     */
    public function isValid() {

        $is_valid = false;
        if ($this->order->getStoreId() == 2) { // BT
            $is_valid = true;
        }
        
        return $is_valid;
    }

}
