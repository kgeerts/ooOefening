<?php

require_once($serverpath['db'] . '/propertyobject.php');
require_once($serverpath['db'] . '/opslagmedium.php');
require_once($serverpath['db'] . '/propDefinition.php');
require_once($serverpath['db'] . '/logger.php');


define('TABLE_BOEK', 'Boek');

class Boek extends PropertyObject {

    function __construct($isbnnr) {
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

    function retrieveByISBN($isbnnr) {

        $query = $this->getDatafieldname('isbnnr') . '=\'' . $isbnnr . '\' '; // get the database name from getDatafield
        $this->convertdata(OpslagMedium::getObjectdataOnQuery(TABLE_BOEK, $query));
    }

    public function retrieveByID($id) {

        $query = $this->getDatafieldname('id') . '=' . $id; // get the database name from getDatafield
        $this->convertdata(OpslagMedium::getObjectdataOnQuery(TABLE_BOEK, $query));
    }

    public function create($titel, $isbnnr, $prijs = 0, $auteur = null, $uitgever = null) {
        $retobj = null;

        $this->id = 0;
        $this->isbnnr = $isbnnr;
        $this->titel = $titel;
        $this->prijs = $prijs;
        $this->auteur = $auteur;
        $this->uitgever = $uitgever;


        $retid = OpslagMedium::storeObject(TABLE_BOEK, $this->changedProperties);
        if ($retid != 0) {
            $this->id = $retid;
            $query = $this->getDatafieldname('id') . '=' . $this->id . ' ';
            $this->convertdata(OpslagMedium::getObjectdataOnQuery(TABLE_BOEK, $query));
            $retobj->result = true;
        }
        return $retobj;
    }

    public function getId() {
        return $this->id;
    }

    public function getISBNnr() {
        return $this->isbnnr;
    }

    //gives yes if there is an instance in the object 
    public function isInstance() {
        if ($this->id <> 0) {
            return true;
        } else {
            return false;
        }
    }

    //gives back the data from this object
    public function getData() {
        return $this->data;
    }

    //Unlocks an account 
    // gives back true if the account is active

    public function getCurrentISBN() {
        return $this->isbnnr;
    }

    /// PRIVATE ///// 
    private function save() {
        $id = $this->id;
        $retid = OpslagMedium::updateObject(TABLE_BOEK, $this->id, $this->changedProperties);
        if ($retid != 0) {

            $query = $this->getDatafieldname('id') . '=' . $this->id . ' ';
            $this->convertdata(OpslagMedium::getObjectdataOnQuery(TABLE_BOEK, $query));
            $retobj->result = true;
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

    // checkes if isbnr complies to the rules
    private function IsIsbnNrOk($isbnnr) {
        return true; //for the time being ;
        if (preg_match('/[A-Za-z]/', $password) && preg_match('/[0-9]/', $password)) {
            if (strlen($password) > 6) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

}

?>