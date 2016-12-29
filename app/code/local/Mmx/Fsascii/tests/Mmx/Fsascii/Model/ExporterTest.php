<?php

class Mmx_Fsascii_Model_ExporterTest extends PHPUnit_Framework_TestCase {

    /**
     *
     * @var Mmx_Fsascii_Model_Exporter
     */
    protected $model;
    
    /**
     *
     * @var Mage_Sales_Model_Order
     */
    protected $order;

    public function setUp() {
        
        $this->order = Mage::getModel('sales/order')->load(1037);

        $this->exporter = new Mmx_Fsascii_Model_Exporter();
        $this->exporter->setOrder($this->order);
    }

    public function testGetOrder() {
        $this->assertEquals(1037, $this->exporter->getOrder()->getId());
    }
    
    public function testAddWriter() {
        $writer = new Mmx_Fsascii_Helper_FileWriter();
        $this->exporter->addWriter($writer);

        foreach ($this->exporter->getWriters() as $writer) {
            if ($writer instanceof Mmx_Fsascii_Helper_FileWriter) {
                $class = 'Mmx_Fsascii_Helper_FileWriter';
            }
        }
        
        $this->assertEquals('Mmx_Fsascii_Helper_FileWriter', $class);
    }

    public function testAddOutput() {
        $output = new Mmx_Fsascii_Model_File_BtSalesOrder();
        $this->exporter->addOutput($output);

        foreach ($this->exporter->getOutputs() as $output) {
            if ($output instanceof Mmx_Fsascii_Model_File_BtSalesOrder) {
                $class = 'Mmx_Fsascii_Model_File_BtSalesOrder';
            }
        }
        
        $this->assertEquals('Mmx_Fsascii_Model_File_BtSalesOrder', $class);
    }
    
    public function testBtExport() {
        
        $order = Mage::getModel('sales/order')->load(1037);
        
        $writer = new Mmx_Fsascii_Helper_FileWriter();
        $writer->setOutputDir('/tmp');

        $writer2 = new Mmx_Fsascii_Helper_FileWriter();
        $writer2->setOutputDir('/Users/gn/Sites/magento/magento-mmx/SageData/Out');
        
        $this->exporter = new Mmx_Fsascii_Model_Exporter();
        $this->exporter->setOrder($order)
                 ->addWriter($writer)
                 ->addWriter($writer2)
                 ->addOutput(new Mmx_Fsascii_Model_File_BtSalesOrder())
                 ->addOutput(new Mmx_Fsascii_Model_File_GoodsReceivedNote())
                 ->addOutput(new Mmx_Fsascii_Model_File_IndigoSalesOrder())
                 ->export();
        
    }
    
    public function testIndigoExport() {
        
        $order = Mage::getModel('sales/order')->load(1045);
        
        $writer = new Mmx_Fsascii_Helper_FileWriter();
        $writer->setOutputDir('/Users/gn/Sites/magento/magento-mmx/SageData/Out');
        
        $this->exporter = new Mmx_Fsascii_Model_Exporter();
        $this->exporter->setOrder($order)
                 ->addWriter($writer)
                 ->addOutput(new Mmx_Fsascii_Model_File_BtSalesOrder())
                 ->addOutput(new Mmx_Fsascii_Model_File_GoodsReceivedNote())
                 ->addOutput(new Mmx_Fsascii_Model_File_IndigoSalesOrder())
                 ->export();
        
    } 
    
    public function testcancelOrderExport() {
        
        $order = Mage::getModel('sales/order')->load(1295); // cancelled order
        
        $writer = new Mmx_Fsascii_Helper_FileWriter();
        $writer->setOutputDir('/Users/gn/Sites/magento/magento-mmx/SageData/Out');
        
        $this->exporter = new Mmx_Fsascii_Model_Exporter();
        $this->exporter->setOrder($order)
                 ->addWriter($writer)
                 ->addOutput(new Mmx_Fsascii_Model_File_SalesOrderDeletion())
                 ->addOutput(new Mmx_Fsascii_Model_File_IndigoStockWriteOff())
                 ->export();
        
    }     

}
