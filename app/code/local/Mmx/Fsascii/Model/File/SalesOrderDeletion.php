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

        $filename = sprintf('DEL_SO %s.txt', $increment_id);
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

        $ascii = implode(PHP_EOL, $lines);
        return $ascii;
    }

    /**
     * Order can be deleted as long as Sage order status is 4 and below, however, by the 
     * time this function is called after the "order_cancel_after" event, the order status has already been set to canceled!
     * 
     * Cancellation depending on order status will need to be validated in the UI instead.
     *
     * @return boolean
     */
    public function isValid() {

        $is_bt_store = false;
        if ($this->order->getStoreId() == 2) { // BT
            $is_bt_store = true;
        }

        if ($is_bt_store && $this->order->getStatus() == 'canceled') {
            return true;
        }
        else {
            return false;
        }
    }

}
