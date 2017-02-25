<?php

/**
 * Only implemented the necessary parts of the spec - 72prefix.docx
 * Add more as required
 */
class Mmx_Fsascii_Model_Format_SalesOrderAllocation extends Mmx_Fsascii_Model_Format {

    const FILE_CODE = '72';

    public function setSalesOrder($sales_order) {
        $this->fields[0] = $sales_order;
        return $this;
    }

    public function setSalesOrderLineNumber($sales_order_line_number) {
        $this->fields[1] = $sales_order_line_number;
        return $this;
    }
    
    public function setWarehouse($warehouse) {
        $this->fields[2] = $warehouse;
        return $this;
    }
    
    public function setProduct($product) {
        $this->fields[3] = $product;
        return $this;
    }
    
    public function setSerialNumber($serial_number) {
        $this->fields[7] = $serial_number;
        return $this;
    }

    public function setAllocationType($allocation_type) {
        $this->fields[8] = $allocation_type;
        return $this;
    }

    public function setAllocatedQty($allocated_qty) {
        $this->fields[9] = $allocated_qty;
        return $this;
    }
    
}