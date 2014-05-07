<?php

abstract class PropertyObject {

    protected $propertyTable = array();     //stores name/value pairs
    //that hook properties to
    //database field names
    protected $changedProperties = array(); //List of properties that
    //have been modified
    protected $data;                        //Actual data from / for
    //the database
    protected $errors = array();            //Any validation errors

    //that might have occurred

    public function __construct($arData) {
        $this->data = $arData;
    }

    function __get($propertyName) {
        if (!array_key_exists($propertyName, $this->propertyTable))
            throw new Exception("Invalid property \"$propertyName\"!");

        if (method_exists($this, 'get' . $propertyName)) {      // kris --- eigenlijk is deze get een soort default . Als getvoornaam bv bestaat in het obect wordt dat gebruikt anders wordt default
            //gebruikt default = gewoon het ophalen van het veld uit de tabel dus een link van 1 veld op 1 property
            return call_user_func(array($this, 'get' . $propertyName));
        } else {
            if ($this->doesdatapropexist($propertyName)) {
                return $this->data->$propertyName;
            } else {
                return null;
            }
        }
    }

    function __set($propertyName, $value) {
        if (!array_key_exists($propertyName, $this->propertyTable))
            throw new Exception("Invalid property \"$propertyName\"!");

        if (method_exists($this, 'set' . $propertyName)) {
            return call_user_func(
                    array($this, 'set' . $propertyName), $value
            );
        } else {
            $valuetoupdate = $this->copyvalue($this->propertyTable[$propertyName]->convert, $propertyName, $value); // copy over the real value with the datatype
            //what follows records the properties that have been changed ; used to update


            if ($this->$propertyName != $valuetoupdate &&
                    !in_array($propertyName, $this->changedProperties)) {
                $this->changedProperties[] = $propertyName;
            };
            $this->data->{$propertyName} = $valuetoupdate; //update the value
        }
    }

    function hyphenNeeded($propertyName) {  //gives yes or no when normally the data should be in a query with hyphens --like strings
        if (!array_key_exists($propertyName, $this->propertyTable))
            throw new Exception("Invalid property \"$propertyName\"!");

        if (method_exists($this, 'isString' . $propertyName)) {
            return call_user_func(array($this, 'get' . $propertyName));
        } else {
            switch ($this->propertyTable[$propertyName]->convert) {
                case 'int':
                    $Hyphen = false;
                    break;
                case 'bool':
                    $Hyphen = false;
                    break;
                case 'time':
                    $Hyphen = true;
                    break;
                default:
                    $Hyphen = true;
            }
            return $Hyphen;
        }
    }

    function getDatafieldname($propertyName) { // takes the propertyname and gives back the datafieldname
        if (!array_key_exists($propertyName, $this->propertyTable))
            throw new Exception("Invalid property \"$propertyName\"!");
        else {
            return $this->propertyTable[$propertyName]->fieldName; // so we get the object and from there the fieldname of the object
        }
    }

    //loops through the data and copies alle the data into the right properties
    protected function copyValueToKey($data) {

        foreach ($this->propertyTable as $key => &$obj) {
            $value = null;
            $datafieldname = $this->getDatafieldname($key);

            if (is_null($datafieldname) == false) {
                $value = $data->{$datafieldname};
            }
            $valuetoupdate = $this->copyvalue($obj->convert, $key, $value);
            $this->data->$key = $valuetoupdate;
        }
    }

    private function doesdatapropexist($prop) {
        if (is_null($this->data) == true) {
            $ret = false;
        } else {
            $ret = property_exists($this->data, $prop);
        }

        return $ret;
    }

    //copies one value into the right property with right datatype
    private function copyvalue($convert, $key, $value) {
        switch ($convert) {
            case 'int':
                $value = intval($value);
                break;
            case 'bool':
                $value = (( $value == '1' or $value == 'true' or $value == true) ? true : false);
                break;
            case 'time':
                $value = DateTime::createFromFormat('Y-m-d H:i:s', $value);
                break;
            case 'double':
                $value = doubleval($value);
                break;
            default :
                $value = $value;
        }
        return ($value);
    }

    // gives back tot total dataobject
    protected function getObjectData() {
        return $this->data;
    }

    function __toString() {
        $str = "<table width=75% border=1 cellpadding=5 cellspacing=5 >";
        foreach ($this->data as $key => $value) {
            if (gettype($value) == "boolean") {
                if ($value === TRUE) {
                    $value = 'Boolean IS True'; // because I want to check if it is really ok 
                } else {
                    $value = 'Boolean IS false';
                }
            }
            $str .= "<tr><td valign=\"top\">$key</td><td>$value</td></tr>";
        }
        $str.="</table>";
        return $str;
    }

}

?>
