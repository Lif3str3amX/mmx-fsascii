<?php

class Mmx_Fsascii_Model_Format_InventoryAdjustmentExtendedTest extends PHPUnit_Framework_TestCase {

    /**
     *
     * @var Mmx_Fsascii_Model_Format_InventoryAdjustmentExtended
     */
    protected $model;
    
    public function setUp() {
        $this->model = new Mmx_Fsascii_Model_Format_InventoryAdjustmentExtended();
    }

    public function testInventoryAdjustmentExtended() {
        
        $expected = '24|1|11|2|INBTRESERVATION|3|-1.00|4|05/10/16|8|Auto write off by Portal|9|INDIGO|16|AA117017|';
        
        $line = new Mmx_Fsascii_Model_Format_InventoryAdjustmentExtended();
        $line->setWarehouse(11)
                ->setProduct('INBTRESERVATION')
                ->setQty('-1.00')
                ->setAdjustDate('05/10/16')
                ->setComment('Auto write off by Portal')
                ->setBin('INDIGO')
                ->setSerial('AA117017');
        
        $this->assertEquals($line->__toString(), $expected);
    }

}
