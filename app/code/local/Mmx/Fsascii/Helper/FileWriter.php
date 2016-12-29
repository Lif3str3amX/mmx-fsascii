<?php

class Mmx_Fsascii_Helper_FileWriter {

    /**
     *
     * @var string
     */
    protected $output_dir;

    public function setOutputDir($output_dir) {
        $this->output_dir = $output_dir;
        return $this;
    }

    public function getOutputDir() {
        return $this->output_dir;
    }

    public function write($filename, $content) {
        return file_put_contents(
                $this->output_dir . DIRECTORY_SEPARATOR . $filename, $content
        );
    }

}
