<?php

class Mmx_Fsascii_Model_File_IndigoSalesOrderTest extends PHPUnit_Framework_TestCase {

    /**
     *
     * @var Mmx_Fsascii_Model_File_IndigoSalesOrder
     */
    protected $model;
    
    /**
     *
     * @var Mage_Sales_Model_Order
     */
    protected $order;

    public function setUp() {
        
        $this->order = Mage::getModel('sales/order')->load(1045);

        $this->model = new Mmx_Fsascii_Model_File_IndigoSalesOrder();
        $this->model->setOrder($this->order);
    }

    public function testGetOrder() {
        $this->model->setOrder($this->order);
        $this->assertEquals(1045, $this->model->getOrder()->getId());
    }
    
    public function testIsValid() {
        $this->assertEquals(true, $this->model->isValid());
    }

    public function testGetRunControlRecord() {
        $this->assertEquals('00|SR25|I001094|Indigo Sales Order|', $this->model->_getRunControlRecord()->__toString());
    }
    
    public function testGenerateFilename() {
        $this->assertEquals('I001094.txt', $this->model->generateFilename());
    }

}
