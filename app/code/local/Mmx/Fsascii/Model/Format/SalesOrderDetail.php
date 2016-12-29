<?php

/**
 * Only implemented the necessary parts of the spec - FS ASCII File Format V1.11
 * Add more as required
 */
class Mmx_Fsascii_Model_Format_SalesOrderDetail extends Mmx_Fsascii_Model_Format {

    const FILE_CODE = '04';

    public function setSalesorder($salesorder) {
        $this->fields[0] = $salesorder;
        return $this;
    }
    
    public function setSequence($sequence) {
        $this->fields[2] = $sequence;
        return $this;
    }

    public function setProduct($product) {
        $this->fields[3] = $product;
        return $this;
    }
    
    public function setType($type) {
        $this->fields[4] = $type;
        return $this;
    }

    public function setWarehouse($warehouse) {
        $this->fields[5] = $warehouse;
        return $this;
    }
    
    public function setLongDescription($long_description) {
        $this->fields[10] = $long_description;
        return $this;
    }

    public function setQty($qty) {
        $this->fields[20] = $qty;
        return $this;
    }

    public function setAllocatedQty($allocated_qty) {
        $this->fields[21] = $allocated_qty;
        return $this;
    }
    
}