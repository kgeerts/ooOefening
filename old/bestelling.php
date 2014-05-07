<?php

require_once($serverpath['db'].'/propertyobject.php');
require_once($serverpath['db'].'/opslagmedium.php');
require_once($serverpath['db'].'/propDefinition.php');
require_once($serverpath['db'].'/logger.php');   
require_once($serverpath['db'].'/datamanager.php');
require_once($serverpath['objects'].'/assigned_books.php');
require_once($serverpath['objects'].'/boek.php');

define('TABLE_BEST', 'Bestelling'); 
        
class Bestelling extends PropertyObject
{
	function __construct($klantid = null, $ordernr = null) {
        parent::__construct(null);


        $this->propertyTable['id'] = new propertyDef('id', null, 'int');
        $this->propertyTable['klantid'] = new propertyDef('klantid');
        $this->propertyTable['ordernr'] = new propertyDef('orderNr');
        $this->propertyTable['totaalaankoop'] = new propertyDef('totaalaankoop', null, 'double');
        $this->propertyTable['betaald'] = new propertyDef('betaald', null, 'bool');
        $this->propertyTable['geleverd'] = new propertyDef('betaald', null, 'bool');
        $this->propertyTable['assigned_books'] = new propertyDef(null, true, 'array');

        if (is_null($ordernr) == false) {
            $this->retrieveByOrderNr($ordernr);
        } else {
            $this->create($klantid);
        }
    }
        
    function retrieveByOrderNr($ordernr) {

        $query = $this->getDatafieldname('ordernr') . '=\'' . $ordernr . '\' '; // get the database name from getDatafield
        $this->convertdata(OpslagMedium::getObjectdataOnQuery(TABLE_BEST, $query));

        $query = ' orderid=' . $this->id;
        $dataRowsAssigned = OpslagMedium::getReturnSetOnQuery('bestelling_boek', $query);
        while ($row = mysql_fetch_assoc($dataRowsAssigned)) {
            $assigned = new assigned_book();
            $assigned->getfromid($row['id']);

            $this->data->assigned_books[] = $assigned; // is this the way to do this ??? 
            //$val = $this->data->assigned_books;
        }
    }
	
    
    public function addboek($isbnnr,$aantal){
        $boek = new Boek($isbnnr);  
        $assigned_books = new assigned_books($boek,$aantal);

        
    }
    public function create($klantid) {


        $retobj = null;
        $this->klantid = $klantid;
        $this->id = 0;
        $this->ordernr = uniqid();
        ;
        $this->totaalaankoop = 0;
        $this->betaald = false;
        $this->geleverd = false;



        $retid = OpslagMedium::storeObject(TABLE_BEST, $this->changedProperties);
        if ($retid != 0) {
            $this->id = $retid;
            $query = $this->getDatafieldname('id') . '=' . $this->id . ' ';
            $this->convertdata(OpslagMedium::getObjectdataOnQuery(TABLE_BEST, $query));
            $retobj->result = true;
        }
        return $retobj;
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
    public function Unlock() {
        $this->incorrectAttempts = 0;
        OpslagMedium::updateObject(TABLE_BEST, $this->id, $this->changedProperties);
        $query = $this->getDatafieldname('id') . '=' . $this->id . ' ';
        $this->convertdata(OpslagMedium::getObjectdataOnQuery(TABLE_BEST, $query));
        return $this->IsLocked();
    }

    // gives back true if the account is active
    /// PRIVATE ///// 
    private function save() {
        $id = $this->id;
        $retid = OpslagMedium::updateObject(TABLE_BEST, $this->id, $this->changedProperties);
        if ($retid != 0) {

            $query = $this->getDatafieldname('id') . '=' . $this->id . ' ';
            $this->convertdata(OpslagMedium::getObjectdataOnQuery(TABLE_BEST, $query));
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

                    if ($key == 'assigned_books') {
                        $this->data->$key = array();

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