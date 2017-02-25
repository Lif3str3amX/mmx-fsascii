<?php

class Mmx_Fsascii_Model_Format_SalesOrderAllocationTest extends PHPUnit_Framework_TestCase {

    /**
     *
     * @var Mmx_Fsascii_Model_Format_SalesOrderAllocation
     */
    protected $model;
    
    public function setUp() {
        $this->model = new Mmx_Fsascii_Model_Format_SalesOrderAllocation();
    }

    public function testSalesOrderAllocation() {

        $expected = '72|0|"I001150"|2|0001|3| INCIENABOM |7|2135511|8|H|9|1|';

        $line = new Mmx_Fsascii_Model_Format_SalesOrderAllocation();
        $line->setSalesOrder('"I001150"')
                ->setWarehouse('0001')
                ->setProduct(' INCIENABOM ')
                ->setSerialNumber('2135511')
                ->setAllocationType('H')
                ->setAllocatedQty(1);

        $this->assertEquals($line->__toString(), $expected);
    }

}

