<?php

/**
 * propDefinition defines an object definition
 * 
 * This is an object that defines the databasefieldname, if it is property is logic defined
 * that is not from the database 
 * and if the data has to be converted when we put it in the property value
 * so that the type is correct  
 * 
 * @package DAO
 * @author ADMINKRIS <kjtgeerts@gmail.com>
 * @version 1.1
 * @Copyright Never
 * @link http://www.it8-projects.com
 * 
 */




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
