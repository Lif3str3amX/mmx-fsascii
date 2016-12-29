<?php

/**
 * Only implemented the necessary parts of the spec - FS ASCII File Format V1.11
 * Add more as required
 */
class Mmx_Fsascii_Model_Format_SalesOrderDeletion extends Mmx_Fsascii_Model_Format {

    const FILE_CODE = '71';

    public function setSalesorder($salesorder) {
        $this->fields[0] = $salesorder;
        return $this;
    }

}
