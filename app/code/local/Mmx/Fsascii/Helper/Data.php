<?php

class Mmx_Fsascii_Helper_Data extends Mage_Core_Helper_Abstract {

    // Remove line-breaks, quotes and pipes - would this be better as allowed chars validation on front-end form?
    public static function sanitize($string) {
        $string = str_replace(array("\r", "\n", "\"", "|"), '', $string);
        return $string;
    }

}
