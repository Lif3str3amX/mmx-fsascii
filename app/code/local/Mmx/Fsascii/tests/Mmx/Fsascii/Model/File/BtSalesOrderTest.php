<?php

class Mmx_Fsascii_Model_File_BtSalesOrderTest extends PHPUnit_Framework_TestCase {

    /**
     *
     * @var Mmx_Fsascii_Model_File_BtSalesOrder
     */
    protected $model;
    
    /**
     *
     * @var Mage_Sales_Model_Order
     */
    protected $order;

    public function setUp() {
        
        $this->order = Mage::getModel('sales/order')->load(1036);

        $this->model = new Mmx_Fsascii_Model_File_BtSalesOrder();
        $this->model->setOrder($this->order);
    }

    public function testGetOrder() {
        $this->model->setOrder($this->order);
        $this->assertEquals(1036, $this->model->getOrder()->getId());
    }
    
    public function testIsValid() {
        $this->assertEquals(true, $this->model->isValid());
    }

    public function testGetRunControlRecord() {
        $this->assertEquals('00|SR25|B001085|Sales Order|', $this->model->_getRunControlRecord()->__toString());
    }
    
    public function testGenerateFilename() {
        $this->assertEquals('SOA B001085.txt', $this->model->generateFilename());
    }

}
