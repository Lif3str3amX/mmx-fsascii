<?php

class Mmx_Fsascii_Helper_FileWriterTest extends PHPUnit_Framework_TestCase {

    /**
     *
     * @var Mmx_Fsascii_Helper_FileWriter
     */
    protected $helper;

    public function setUp() {
        $this->helper = new Mmx_Fsascii_Helper_FileWriter();
    }

    public function testSetGetOutputDir() {
        $this->helper->setOutputDir('/tmp');
        $this->assertEquals('/tmp', $this->helper->getOutputDir());
    }

}
