<?php

class Mmx_Fsascii_Model_File_IndigoCienaSalesOrderTest extends PHPUnit_Framework_TestCase {

    /**
     *
     * @var Mmx_Fsascii_Model_File_IndigoCienaSalesOrder
     */
    protected $model;
    
    /**
     *
     * @var Mage_Sales_Model_Order
     */
    protected $order;

    public function setUp() {
        
        $this->order = Mage::getModel('sales/order')->load(1296);   // Order that was split programatically and contains only INCIENABOM and INBTRESERVATION products

        $this->model = new Mmx_Fsascii_Model_File_IndigoCienaSalesOrder();
        $this->model->setOrder($this->order);
    }

    public function testGetOrder() {
        $this->model->setOrder($this->order);
        $this->assertEquals(1296, $this->model->getOrder()->getId());
    }
    
    public function testIsValid() {
        $this->assertEquals(true, $this->model->isValid());
    }

}
