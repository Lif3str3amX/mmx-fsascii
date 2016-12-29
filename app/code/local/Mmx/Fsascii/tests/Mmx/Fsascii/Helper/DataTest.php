<?php

class Mmx_Fsascii_Helper_DataTest extends PHPUnit_Framework_TestCase {

    /**
     *
     * @var Mmx_Fsascii_Helper_Writer
     */
    protected $helper;
    
    public function setUp() {
        $this->helper = new Mmx_Fsascii_Helper_Data();
    }

    /**
     * TODO: Confirm requirements. Should this allow ASCII table chars only?
     */
    public function testSanitize() {

        $string = "Here's a comment with \"quotes\" and \nline-breaks!";
        $expected = "Here's a comment with quotes and line-breaks!";
        $result = $this->helper->sanitize($string);
        
        $this->assertEquals($expected, $result);
    }

}
