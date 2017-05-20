<?php

class Mmx_Fsascii_Model_File_SalesOrderDeletion extends Mmx_Fsascii_Model_File {

    public function _getRunControlRecord() {

        $line = new Mmx_Fsascii_Model_Format_RunControlRecord();
        $line->setCode('S5')
                ->setSalesOrderNumber($this->order->getIncrementId())
                ->setHeader('Order Del');

        return $line;
    }

    public function _getSalesOrderDeletion() {

        $line = new Mmx_Fsascii_Model_Format_SalesOrderDeletion();
        $line->setSalesorder($this->order->getIncrementId());

        return $line;
    }

    /**
     * Generates a filename based on the order increment_id
     *
     * @return string
     */
    public function generateFilename() {

        $increment_id = $this->order->getIncrementId();

        $filename = sprintf('SOD %s.txt', $increment_id);
        return $filename;
    }

    /**
     * 
     * @return string
     */
    public function generateAscii() {

        $lines = array();
        $lines[] = $this->_getRunControlRecord();
        $lines[] = $this->_getSalesOrderDeletion();

        $ascii = implode(self::MMX_FSASCII_MODEL_FILE_EOL, $lines);
        return $ascii;
    }

    /**
     * DEL file can be created if order is cancelled via the BT or Indigo stores
     * Confirmed by J.P on 24/Feb/2017
     * 
     * @return boolean
     */
    public function isValid() {

        $is_valid = false;
        if ($this->order->getStoreId() == 2 || $this->order->getStoreId() == 3) { // BT or Indigo
            $is_valid = true;
        }
        
        return $is_valid;
    }

}
