<?php

require_once($serverpath['db'] . '/propertyobject.php');
require_once($serverpath['db'] . '/opslagmedium.php');
require_once($serverpath['db'] . '/propDefinition.php');
require_once($serverpath['db'] . '/logger.php');
require_once($serverpath['objects'] . '/GenDataClass.php');

/**
 * The book object
 * 
 * The book object is a value object of book <br>
 * its the intermediat object between the book table(s) and the object <br>

 * 
 * @package Objects
 * @author ADMINKRIS <kjtgeerts@gmail.com>
 * @version 1.1
 * @Copyright Never
 * @link http://kjtgeerts@gmail.com
 * 
 */

class Boek extends GenDataClass {
    

    protected $id;
    protected $titel;
    protected $isbnnr;
    protected $prijs;
    protected $auteur;
    public $uitgever;
    
    
    
    function __construct() {  
        $this->id=0;
        $this->titel='';
        $this->isbnnr='';
        $this->prijs='';  
        $this->auteur='';
        $this->uitgever='';
    }
    public function setIsbn ($isbn){
        $this->isbnnr=$isbn;
    }
     public function setPrice ($prijs){
        $this->prijs=$prijs;
    }
    public function setTitel ($title){
        $this->titel=$title;
    }
     public function setAuteur ($auteur){
        $this->auteur=$auteur;
    }
      public function setUitgever ($uitgever){
        $this->uitgever=$uitgever;
    }


    public function getId() {
        return $this->id;
    }

    public function getISBNnr() {
        return $this->isbnnr;
    }
   
}

?>