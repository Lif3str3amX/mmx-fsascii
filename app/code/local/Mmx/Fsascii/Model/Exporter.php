<?php

class Mmx_Fsascii_Model_Exporter {

    /**
     *
     * @var Mage_Sales_Model_Order
     */
    protected $order;

    /**
     *
     * @var array
     */    
    protected $outputs = array();
    
    /**
     *
     * @var array
     */
    protected $writers = array();

    public function setOrder($order) {
        $this->order = $order;
        return $this;
    }
    
    public function getOrder() {
        return $this->order;
    }

    public function addOutput($output) {
        $this->outputs[] = $output;
        return $this;
    }
    
    public function getOutputs() {
        return $this->outputs;
    }    

    public function addWriter($writer) {
        $this->writers[] = $writer;
        return $this;
    }
    
    public function getWriters() {
        return $this->writers;
    }
    
    public function export() {
        
        foreach ($this->outputs as $output) {
            $output->setOrder($this->order);
            if ($output->isValid()) {
                foreach ($this->writers as $writer) {
                    $writer->write($output->generateFilename(), $output->generateAscii());
                }
            }
        }

        return;
    }

}
