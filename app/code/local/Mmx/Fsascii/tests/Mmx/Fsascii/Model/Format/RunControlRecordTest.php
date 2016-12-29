<?php

class Mmx_Fsascii_Model_Format_RunControlRecordTest extends PHPUnit_Framework_TestCase {

    /**
     *
     * @var Mmx_Fsascii_Model_Format_RunControlRecord
     */
    protected $model;
    
    public function setUp() {
        $this->model = new Mmx_Fsascii_Model_Format_RunControlRecord();
    }

    public function testGetRunControlRecord() {
        
        $expected = '00|SR25|B000025|Sales Order|';
        
        $line = new Mmx_Fsascii_Model_Format_RunControlRecord();
        $line->setCode('SR25')
                ->setSalesOrderNumber('B000025')
                ->setHeader('Sales Order');

        $this->assertEquals($line->__toString(), $expected);
    }

    public function testRunControlRecordWithEmptySalesOrderNumberForGoodsRecievedNote() {
        
        $expected = '00|25||Goods Received Note|';
        
        $line = new Mmx_Fsascii_Model_Format_RunControlRecord();
        $line->setCode('25')
                ->setSalesOrderNumber('')
                ->setHeader('Goods Received Note');

        $this->assertEquals($line->__toString(), $expected);
    }
    
}
