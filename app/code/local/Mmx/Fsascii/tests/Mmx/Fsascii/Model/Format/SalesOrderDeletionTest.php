<?php

class Mmx_Fsascii_Model_Format_SalesOrderDeletionTest extends PHPUnit_Framework_TestCase {

    /**
     *
     * @var Mmx_Fsascii_Model_Format_SalesOrderDeletion
     */
    protected $model;
    
    public function setUp() {
        $this->model = new Mmx_Fsascii_Model_Format_SalesOrderDeletion();
    }

    public function testSalesOrderDeletion() {

        $expected = '71|0|W000003|';

        $line = new Mmx_Fsascii_Model_Format_SalesOrderDeletion();
        $line->setSalesorder('W000003');

        $this->assertEquals($line->__toString(), $expected);
    }

}
