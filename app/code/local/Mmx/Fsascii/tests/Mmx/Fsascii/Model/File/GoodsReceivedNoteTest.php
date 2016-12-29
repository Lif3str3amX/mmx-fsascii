<?php

class Mmx_Fsascii_Model_File_GoodsReceivedNoteTest extends PHPUnit_Framework_TestCase {

    /**
     *
     * @var Mmx_Fsascii_Model_File_GoodsReceivedNote
     */
    protected $model;
    
    /**
     *
     * @var Mage_Sales_Model_Order
     */
    protected $order;

    public function setUp() {
        
        $this->order = Mage::getModel('sales/order')->load(1036);

        $this->model = new Mmx_Fsascii_Model_File_GoodsReceivedNote();
        $this->model->setOrder($this->order);
    }

    public function testGetOrder() {
        $this->assertEquals(1036, $this->model->getOrder()->getId());
    }
    
    public function testIsValid() {
        $this->assertEquals(true, $this->model->isValid());
    }    
    
    public function testGetRunControlRecord() {
        $this->assertEquals('00|25||Goods Received Note|', $this->model->_getRunControlRecord()->__toString());
    }
    
    public function testGetGoodsReceivedNote() {
        $this->assertEquals('25|1|11|2|INBTRESERVATION|3|1.00|4|02/11/16|8|BT Scheme Reservation|9|INDIGO|16|SCHREF001|', $this->model->_getGoodsReceivedNote()->__toString());
    }

    public function testGenerateFilename() {
        $this->assertEquals('SCHREF001.txt', $this->model->generateFilename());
    }

}
