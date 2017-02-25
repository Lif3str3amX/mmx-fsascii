<?php

class Mmx_Fsascii_Model_File_IndigoStockWriteOffTest extends PHPUnit_Framework_TestCase {

    /**
     *
     * @var Mmx_Fsascii_Model_File_IndigoStockWriteOff
     */
    protected $model;
    
    /**
     *
     * @var Mage_Sales_Model_Order
     */
    protected $order;

    public function setUp() {
        
        $this->order = Mage::getModel('sales/order')->load(1045);   // Indigo order

        $this->model = new Mmx_Fsascii_Model_File_IndigoStockWriteOff();
        $this->model->setOrder($this->order);
    }

    public function testGetOrder() {
        $this->assertEquals(1045, $this->model->getOrder()->getId());
    }
    
    public function testShouldNotBeValidForIndigoStoreOrder() {
        $this->assertFalse($this->model->isValid());
    }
    
    public function testGenerateFilename() {
        $this->assertEquals('SWO_0411161150.txt', $this->model->generateFilename());
    }

}
