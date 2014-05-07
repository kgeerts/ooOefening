<?php

Class propertyDef {

    public $fieldName = '';
    public $logicdefined;
    public $convert = '';

    function __construct($fieldname, $logicdefined = false, $convert = null) {
        $this->fieldName = $fieldname;

        if ($logicdefined != false) {
            $this->logicdefined = true;
        } else {
            $this->logicdefined = false;
        }
        switch ($convert) {
            case 'int':
                $this->convert = $convert;
                break;
            case 'bool':
                $this->convert = $convert;
                break;
            case 'time':
                $this->convert = $convert;
                break;
            case 'double':
                $this->convert = $convert;
                break;
            default:
                $this->convert = 'none';
                break;
        };
    }

}

?>
