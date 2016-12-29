<?php

class Mmx_Fsascii_Model_Format_SalesOrderDetailTest extends PHPUnit_Framework_TestCase {

    /**
     *
     * @var Mmx_Fsascii_Model_Format_SalesOrderDetail
     */
    protected $model;
    
    public function setUp() {
        $this->model = new Mmx_Fsascii_Model_Format_SalesOrderDetail();
    }

    public function testSalesOrderDetailProductLine() {

        $expected = '04|0|B000025|2|0001|3|BI000001|4|P|5|11|20|1|21|1|';

        $line = new Mmx_Fsascii_Model_Format_SalesOrderDetail();
        $line->setSalesorder('B000025')
                ->setSequence('0001')
                ->setProduct('BI000001')
                ->setType('P')
                ->setWarehouse(11)
                ->setQty(1)
                ->setAllocatedQty(1);

        $this->assertEquals($line->__toString(), $expected);
    }
    
    public function testSalesOrderDetailOrderAttributeLine() {

        $expected = '04|0|B000025|2|0003|4|C|5|d|10|Scheme Reference xxxxxxxxxxxxxxxxxxxxxxxxxxxxxx|';

        $line = new Mmx_Fsascii_Model_Format_SalesOrderDetail();
        $line->setSalesorder('B000025')
                ->setSequence('0003')
                ->setType('C')
                ->setWarehouse('d')
                ->setLongDescription('Scheme Reference xxxxxxxxxxxxxxxxxxxxxxxxxxxxxx');

        $this->assertEquals($line->__toString(), $expected);
    }    

}
