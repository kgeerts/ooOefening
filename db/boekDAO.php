<?php
/**
 * BoekDAO regulates the saving and rebuilding of the object boek
 * 
 * een veel langere beschrijving kan hier volgen <br>
 * en je kan er zelfs tags in gebruiken <br>
 * selected <b>HTML-tags</b> kunnen gebruikt worden
 * 
 * @package DAO
 * @author ADMINKRIS <kjtgeerts@gmail.com>
 * @version 1.1
 * @Copyright Never
 * @link http://www.it8-projects.com
 * 
 */


require_once($serverpath['db'] . '/propertyobject.php');
require_once($serverpath['db'] . '/opslagmedium.php');
require_once($serverpath['db'] . '/propDefinition.php');
require_once($serverpath['db'] . '/logger.php');
require_once($serverpath['objects'].'/boek.php');




class BoekDAO extends PropertyObject {
    const tablename = 'Boek';
    function __construct($isbnnr = null) {
        parent::__construct(null);
     

        $this->propertyTable['id'] = new propertyDef('id', null, 'int');
        $this->propertyTable['titel'] = new propertyDef('titel');
        $this->propertyTable['isbnnr'] = new propertyDef('isbnnr');
        $this->propertyTable['prijs'] = new propertyDef('prijs', null, 'double');
        $this->propertyTable['auteur'] = new propertyDef('auteur');
        $this->propertyTable['uitgever'] = new propertyDef('uitgever');


        if (is_null($isbnnr) == false) {
            $this->retrieveByISBN($isbnnr);
        } else {
            $this->data = null;
        }
    }

    public function MakeObject(){
        $object = new Boek();
        $object->setId($this->data->id);
        $object->setIsbn($this->data->isbnnr);
        $object->setPrice($this->data->prijs);
        $object->setTitel($this->data->titel);
        $object->setUitgever($this->data->uitgever);
        $object->setAuteur($this->data->auteur);

        return $object;
   }
    public function retrieveByID($id) {

        $query = $this->getDatafieldname('id') . '=' . $id; // get the database name from getDatafield
        $this->convertdata(OpslagMedium::getObjectdataOnQuery($this::tablename, $query));
        
    }
     /**
     * 
     * 
     * @isbnnr is the isbn in form of 000-0000000000
     * @param2 is het tweede getal dat we willen optellen 
     * @return the function does return an object of the form book
     * 
     * @version 1.1
     * 
    **/
    function retrieveByISBN(string $isbnnr) {

        $query = $this->getDatafieldname('isbnnr') . '=\'' . $isbnnr . '\' '; // get the database name from getDatafield
        $this->convertdata(OpslagMedium::getObjectdataOnQuery($this::tablename, $query));
        
    }
    
    

    //gives back the data from this object
    public function getData() {
        return $this->data;
    }

    //Unlocks an account 
    // gives back true if the account is active


    
    
    public function save($book) {

        
        $properties = $book->giveAllPropertiesArray();// gets all the properties in the form of a array
        foreach ($properties as $key => $value) {
            $this->valtodata($key, $value);
        }
        $id = $book->id;
        if ($book->id == 0 ){
            $retid = OpslagMedium::storeObject($this::tablename, $properties) ;
  
        }
        else {
            $retid = OpslagMedium::updateObject(tablename, $this->id, $this->changedProperties);
        
        }

        if ($retid != 0) {

            $query = $this->getDatafieldname('id') . '=' . $retid . ' ';
            $this->convertdata(OpslagMedium::getObjectdataOnQuery($this::tablename, $query));

            $retobj->result = true;
            return $this->MakeObject();
        }
    }

    private function valtodata($key, $value) {
        $datafieldname = $this->getDatafieldname($key);
        if (is_null($datafieldname) == false) {  //if there is a datafield
            $this->data->{$datafieldname} = $value;
        } else {
            $this->data->{$key} = $value; //if there is NO translation we expect the name of the data is the same as the key
        }
    }

    //puts the queried data into the dataobject
    private function convertdata($data) {
        $this->data = null;
        if (is_null($data)) {
            return;
        } else {
            $this->copyValueToKey($data); // first copys in all the data that has one to one relation

            foreach ($this->propertyTable as $key => &$obj) {  //then copy the logical properties over 
                $value = null;
                $datafieldname = $this->getDatafieldname($key);

                if (is_null($datafieldname) == false) {
                    $value = $data->{$datafieldname};
                }


                if ($obj->logicdefined == true) {

                    if ($key == 'loggedIn') {

                        //   $this->valtodata($key,false);
                    }
                    // here logic comes in of the object
                    else {
                        $msg = 'objectfield \'' . $key . '\' should be logicdefined while no definition in objectDef ' . '<br/>';
                        Logger::log($msg);
                        die();
                    }
                }
            }
            unset($obj);
        }
    }

    // makes a return object 
    private function getRetObj($result = false, $number = 0, $mess = '') {
        $retObj = null;
        $retObj->result = $result;
        $retObj->number = $number;
        $retObj->mess = $mess;
        return $retObj;
    }

  

}

?>