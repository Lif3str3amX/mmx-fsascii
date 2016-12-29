<?php

class Mmx_Fsascii_Model_Format_GoodsReceivedNoteTest extends PHPUnit_Framework_TestCase {

    /**
     *
     * @var Mmx_Fsascii_Model_Format_GoodsReceivedNote
     */
    protected $model;
    
    public function setUp() {
        $this->model = new Mmx_Fsascii_Model_Format_GoodsReceivedNote();
    }

    public function testGoodsReceivedNote() {
        
        $expected = '25|1|11|2|INBTRESERVATION|3|1.00|4|13/10/16|8|BT Scheme Reservation|9|INDIGO|16|1234567890|';
        
        $line = new Mmx_Fsascii_Model_Format_GoodsReceivedNote();
        $line->setWarehouse(11)
                ->setProduct('INBTRESERVATION')
                ->setQty('1.00')
                ->setReceiptDate('13/10/16')
                ->setComment('BT Scheme Reservation')
                ->setBin('INDIGO')
                ->setSerial('1234567890');
        
        $this->assertEquals($line->__toString(), $expected);
    }

}
