<?php

/**
 * Only implemented the necessary parts of the spec - FS ASCII File Format V1.11
 * Add more as required
 */
class Mmx_Fsascii_Model_Format_RunControlRecord extends Mmx_Fsascii_Model_Format {
    
    protected $code;
    protected $sales_order_number;
    protected $header;
    
    public function setCode($code) {
        $this->code = $code;
        return $this;
    }

    public function setSalesOrderNumber($sales_order_number) {
        $this->sales_order_number = $sales_order_number;
        return $this;
    }

    public function setHeader($header) {
        $this->header = $header;
        return $this;
    }
    
    public function __toString() {
        return sprintf('00|%s|%s|%s|', $this->code, $this->sales_order_number, $this->header);
    }
    
}