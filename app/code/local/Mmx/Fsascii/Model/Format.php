<?php

class Mmx_Fsascii_Model_Format {

    /**
     *
     * @var array
     */
    protected $fields = array();
    
    public function __toString() {

        $fields = $this->fields;
        ksort($fields);

        $line = static::FILE_CODE . '|';
        foreach ($fields as $key => $value) {
            $line .= sprintf('%s|%s|', $key, $value);
        }
        
        return $line;
    }

}
