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

}
