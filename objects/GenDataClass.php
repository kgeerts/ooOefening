<?php


/**
 * GenClass services few of generic dataObject properties
 * 
 *
 * @author adminkris
 */
abstract class GenDataClass {
protected $changedProperties = array();  
    /**
     * Funtion returns the object in a string table
    **/
    function __toString() {
        $properties = get_object_vars($this);
        $str = "<table width=75% border=1 cellpadding=5 cellspacing=5 >";
        foreach ($properties as $key => $value) {
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
    /**
     * Magical function to return a propertyvalue even it is private 
     * 
     * @parm1 is the propertyName I want to have the value off
     * 
     */
    function __get($propertyName) {
        
        if (method_exists($this, 'get' . $propertyName)) {      // kris --- eigenlijk is deze get een soort default . Als getvoornaam bv bestaat in het obect wordt dat gebruikt anders wordt default
            //gebruikt default = gewoon het ophalen van het veld uit de tabel dus een link van 1 veld op 1 property
            return call_user_func(array($this, 'get' . $propertyName));
        } else {
            if (property_exists($this, $propertyName)) {
                return $this->$propertyName;
            } else {
                return null;
            }
        }
    }
    
    
    /**
     * Magical function to set a propertyvalue even it is private 
     * 
     * @parm1 is the propertyName I want to set
     * @parm2 is the value I awant to give it
     * 
     */
    function __set($propertyName, $value) { 
       
        if (method_exists($this, 'set' . $propertyName)) {
             $this->AddToChangedProperties( $propertyName);
            return call_user_func(
                    array($this, 'set' . $propertyName), $value
            );
            
        } else {
            $this->AddToChangedProperties( $propertyName);
          
            $this->{$propertyName} = $value; //update the value
        }
    }

    
     /**
     * Gives a list of all properties that are changed after creating the object
     * 
     * @return an array of all changed properties
     * 
     */
    
    function getChangedProperties(){
        return $this->changedProperties;
    }
    
    /**
     * Gives a list of all properties this is done here because this way also the private once go back
     * 
     * @parm1 is the propertyName I want to have the value off
     * @return an array of all properties  minus the changed properties array
     * 
     */
    function giveAllPropertiesArray(){
            $arrayToReturn =get_object_vars($this);
                    
            unset($arrayToReturn['changedProperties']);// return all except the changed properties
            return $arrayToReturn;
    }
    
    
    
    /**
     * If neccesary adds the propertyName to the changedPropertiesTable
     * 
     * @param1  propertyname to check
     * 
     */
    private function AddToChangedProperties ($propertyName){
         if ( !in_array($propertyName, $this->changedProperties)& !$propertyName=='id') {
                             $this->changedProperties[] = $propertyName;
            };
    }
    
    public function InitChanged(){
        $this->changedProperties= array();
        
    }
    
    
}
