<?php

class Mmx_Fsascii_Model_Format_SalesOrderHeaderTest extends PHPUnit_Framework_TestCase {

    /**
     *
     * @var Mmx_Fsascii_Model_Format_SalesOrderHeader
     */
    protected $model;
    
    public function setUp() {
        $this->model = new Mmx_Fsascii_Model_Format_SalesOrderHeader();
    }

    public function testSalesOrderHeader() {

        $expected = '03|0|B000025|3|ZZINDIGO|4|John Page|5|BT Reservation Order|12|13/10/16|13|13/10/16|14|13/10/16|24|5|45|7|';

        $line = new Mmx_Fsascii_Model_Format_SalesOrderHeader();
        $line->setSalesorder('B000025')
                ->setCustomer('ZZINDIGO')
                ->setDeliveryAddr1('John Page')
                ->setDeliveryAddr2('BT Reservation Order')
                ->setDateEntered('13/10/16')
                ->setDateReceived('13/10/16')
                ->setDateRequired('13/10/16')
                ->setOrderStatus(5)
                ->setCtolnolines(7);

        $this->assertEquals($line->__toString(), $expected);
    }

}
