<?php

/**
 * Only implemented the necessary parts of the spec - FS ASCII File Format V1.11
 * Add more as required
 */
class Mmx_Fsascii_Model_Format_SalesOrderHeader extends Mmx_Fsascii_Model_Format {

    const FILE_CODE = '03';

    public function setSalesorder($salesorder) {
        $this->fields[0] = $salesorder;
        return $this;
    }

    public function setCustomer($customer) {
        $this->fields[3] = $customer;
        return $this;
    }

    public function setDeliveryAddr1($delivery_addr_1) {
        $this->fields[4] = $delivery_addr_1;
        return $this;
    }

    public function setDeliveryAddr2($delivery_addr_2) {
        $this->fields[5] = $delivery_addr_2;
        return $this;
    }

    public function setDeliveryAddr3($delivery_addr_3) {
        $this->fields[6] = $delivery_addr_3;
        return $this;
    }

    public function setDeliveryAddr4($delivery_addr_4) {
        $this->fields[7] = $delivery_addr_4;
        return $this;
    }

    public function setDeliveryAddr5($delivery_addr_5) {
        $this->fields[8] = $delivery_addr_5;
        return $this;
    }

    public function setDateEntered($date_entered) {
        $this->fields[12] = $date_entered;
        return $this;
    }

    public function setDateReceived($date_received) {
        $this->fields[13] = $date_received;
        return $this;
    }

    public function setDateRequired($date_required) {
        $this->fields[14] = $date_required;
        return $this;
    }

    public function setOrderStatus($order_status) {
        $this->fields[24] = $order_status;
        return $this;
    }

    public function setCtolnolines($ctolnolines) {
        $this->fields[45] = $ctolnolines;
        return $this;
    }

}
