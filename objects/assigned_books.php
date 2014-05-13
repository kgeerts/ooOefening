<?php

require_once($serverpath['db'].'/propertyobject.php');
require_once($serverpath['db'].'/opslagmedium.php');
require_once($serverpath['db'].'/propDefinition.php');
require_once($serverpath['db'].'/logger.php');   
 

define('TABLE_ASSIGNEDBOOKS', 'bestelling_boek'); 
        
class assigned_book extends PropertyObject
{
      
     
	function __construct($book = null,$aantal = null)
	{
      	parent::__construct(null);
        

      	$this->propertyTable['id'] = new propertyDef('id',null,'int');
        $this->propertyTable['aantal']= new propertyDef('aantal',null,'double');
        $this->propertyTable['boek']= new propertyDef(null,true,'object');
        
        if (is_null($book) == false){
            $this->createNew($book, $aantal);
        } 
        return $this;
        }
    
   private function createNew($book,$aantal=0){
        
        if (is_null($book)==false){
            $this->boek=$book;
        }
        
         $this->aantal=$aantal;
    
        return $this;
   }
            
    public function getfromId($id){
        $query =$this->getDatafieldname('id').'='.$id.' ';
       // $returnobj = OpslagMedium::getObjectdataOnQuery(TABLE_ASSIGNEDBOOKS,$query);
        $this->convertdata(OpslagMedium::getObjectdataOnQuery(TABLE_ASSIGNEDBOOKS,$query)); 
        
        $retobj->result =true;
        
              

    }
    
 

   
    
    //gives back the data from this object
    public function getData()
    {
        return $this->data;
    }
    //Unlocks an account 
  
  
    
     /// PRIVATE ///// 
      private function save($orderid) {
          $id=$this->id;
        $retid=OpslagMedium::updateObject(TABLE_ASSIGNEDBOOKS,$this->id,$this->changedProperties);
                 if ($retid!=0){
                         
                               $query =$this->getDatafieldname('id').'='.$this->id.' ';
                               $this->convertdata(OpslagMedium::getObjectdataOnQuery(TABLE_ASSIGNEDBOOKS,$query)); 
                               $retobj->result =true;
        }
        
   }
                 
                 
   
    
    //puts the queried data into the dataobject
    private function convertdata($data) {
       $this->data = null;
       if (is_null($data )){return;}
       else {
       $this->copyValueToKey($data); // first copys in all the data that has one to one relation
        
        foreach($this->propertyTable as $key =>&$obj){  //then copy the logical properties over 
              $value = null;
               $datafieldname = $this->getDatafieldname($key);
             
              if (is_null($datafieldname)==false){
                   $value = $data->{$datafieldname};
              }
      
                  
               if ($obj->logicdefined == true){
                
                  if ($key== 'boek'){ 
                    $book = new boek(null);
                    $booktoget = intval($data->boekid);
                    $book->retrieveByID($booktoget);
                    $this->data->$key = $book;      
                          //   $this->valtodata($key,false);
                          
                  }      
                  // here logic comes in of the object
                   else {
                       $msg = 'objectfield \''.$key.'\' should be logicdefined while no definition in objectDef '.'<br/>';
                       Logger::log($msg);
                       die();
                   }
               }  
              }
              unset($obj);
           
             } }  
             
    // makes a return object 
    private function getRetObj ($result=false,$number=0,$mess=''){
        $retObj = null;
        $retObj->result = $result;
        $retObj->number = $number;
        $retObj->mess = $mess;
        return $retObj;
           
    }           
             
    // checkes if isbnr complies to the rules
    private function IsIsbnNrOk($isbnnr) {
        return true; //for the time being ;
        if (preg_match('/[A-Za-z]/',$password)&& preg_match('/[0-9]/', $password)){
            if (strlen($password)>6){
                return true;
            }
            else {
                return false;
            }}
        else {
            return false;
        }
    }
    

   
    }
?>