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

require_once($serverpath['objects'] . '/ObjFact_Book.php');



class BoekDAO extends PropertyObject {
    const tablename = 'Boek';
    private $BookObjFactory ;

    
    function __construct($ObjFactory) {
        parent::__construct(null);
     
        $this->BookObjFactory = $ObjFactory;
        $this->propertyTable['id'] = new propertyDef('id', null, 'int');
        $this->propertyTable['titel'] = new propertyDef('titel');
        $this->propertyTable['isbnnr'] = new propertyDef('isbnnr');
        $this->propertyTable['prijs'] = new propertyDef('prijs', null, 'double');
        $this->propertyTable['auteur'] = new propertyDef('auteur');
        $this->propertyTable['uitgever'] = new propertyDef('uitgever');

    }
    /**
     * Creates an object of the type book
     * its based on ObjFact_book 
    **/
    private function GetBookObject(){
        $object = $this->BookObjFactory->create($this->data);
        return $object;
   }
   
     /**
     * @return gives back an empty book object
    **/
    public function GetEmptyBook () {
        return $this->GetBookObject();
    }
   
 
    /**
     * @param1 is the id of the searched boek
     * @return nothing --get the return back by GetObject
    **/
    public function retrieveByID($id) {

        $query = $this->getDatafieldname('id') . '=' . $id; // get the database name from getDatafield
        $this->convertdata(OpslagMedium::getObjectdataOnQuery($this::tablename, $query));
        return $this->GetBookObject();
    }
     /**

     * @param1 is the isbn in form of 000-0000000000
     * @return the function does return an object of the form book
    **/
    function retrieveByISBN($isbnnr) {

        $query = $this->getDatafieldname('isbnnr') . '=\'' . $isbnnr . '\' '; // get the database name from getDatafield
        $this->convertdata(OpslagMedium::getObjectdataOnQuery($this::tablename, $query));
        return $this->GetBookObject();
    }
    
    
    /**
     * @param1 is the book I want to save
     * @return the saved object
    **/
  public function save(Boek $book) {
        $properties = $book->giveAllPropertiesArray(); // gets all the properties in the form of a array
        foreach ($properties as $key => $value) {
            $this->valtodata($key, $value);
        }
        $id = $book->id;
        if ($book->id == 0) {
            $retid = OpslagMedium::storeObject($this::tablename, $properties);
        } else {
            $retid = OpslagMedium::updateObject($this::tablename, $book->id, $book->changedProperties);
        }
        if ($retid != 0) {

            $query = $this->getDatafieldname('id') . '=' . $retid . ' ';
            $this->convertdata(OpslagMedium::getObjectdataOnQuery($this::tablename, $query));
            $retobj->result = true;
            return $this->GetBookObject();
        }
    }

 
   
    /**
     * Converts the data from the query = $data and puts it into $this->data
     * 
     * @param1 data of the object 
     * @return is the $this->$data object 
     * */
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

}

?>