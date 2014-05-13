<?php

class superclass {

    private $a = 'a - uit de superclass ';

    public function getObjectVars() {
        echo '</br>'.get_class($this).'</br>';
        return get_object_vars($this);
    }
    public function getObjectVars2($obj) {
        return get_object_vars($obj);
    }

    public function getClassVars($classname) {
        return get_class_vars($classname);
    }
    
    public function getPublicVars() {
        return call_user_func('get_object_vars', $this);
    }
    
    public function json_trick (){
        return json_decode(json_encode($this), true);
    }
    
    public function array_trick (){
        $array = (array) $this;
        return $array;
    }
    
    
}

class subclass extends superclass {

    private $b = 'b uit de subclass';

}

$obj = new subclass();

$vars = $obj->getObjectVars();
echo 'properties zichtbaar: </br> ';
foreach ($vars as $name => $value) {

    echo $name . ': ' . $value.'</br>';

}

$vars = $obj->getObjectVars2($obj);
echo 'properties zichtbaar: </br> ';
foreach ($vars as $name => $value) {

    echo $name . ': ' . $value.'</br>';

}
