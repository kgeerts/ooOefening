<?php

require_once($serverpath['db'].'/propertyobject.php');
require_once($serverpath['db'].'/opslagmedium.php');
require_once($serverpath['db'].'/propDefinition.php');
require_once($serverpath['db'].'/logger.php');   
        
define('DEFTABLE', 'Klant'); 
        
class Klant extends PropertyObject
{
	function __construct($email)
	{
      	parent::__construct(null);
      	$this->propertyTable['id'] = new propertyDef('id',null,'int');
      	$this->propertyTable['emailadres'] = new propertyDef('emailadres');
      	$this->propertyTable['passwoord'] = new propertyDef('passwoord');
        $this->propertyTable['leveradres']= new propertyDef('leveradres');
        $this->propertyTable['loggedIn']= new propertyDef(null,true,'bool');// So this is a propery NOt in the database defined in logic here

        
        if (is_null($email)==false){
                 $this->retrieveByEmail($email);
        }
        else {$this->data = null;}
        }
        
      function retrieveByEmail ($email)
    {
          
         $query =$this->getDatafieldname('emailadres').'=\''. $email.'\' '; // get the database name from getDatafield
         $this->convertdata(OpslagMedium::getObjectdataOnQuery(DEFTABLE,$query));              
	
        
    }  
    
    
    
    
    public function create($email,$password=null,$leveradres=null){
        $retobj = null;
        if( $this->IsEmailUnique($email)==true){
             $passwok = $this->IspasswordOk($password);
                if ($passwok == false){
                     $ret = OpslagMedium::callsp('select getRandomPassw() as passw');
                     $password = $ret->passw;
                     $retobj = $this->getRetObj(false,2,'account added with generated passw');}
                else {
                     $retobj = $this->getRetObj(false,3,'account added with own password'); }
                     
                 $this->id=0;
                 $this->emailadres=$email;
                 $this->passwoord=$password;
                 $this->leveradres=$leveradres;
                

                 $retid=OpslagMedium::storeObject(DEFTABLE,$this->changedProperties);
                 if ($retid!=0){
                              $this->id = $retid;
                               $query =$this->getDatafieldname('id').'='.$this->id.' ';
                               $this->convertdata(OpslagMedium::getObjectdataOnQuery(DEFTABLE,$query)); 
                               $retobj->result =true;
        }}
             else {
                   $retobj = $this->getRetObj(false,1,'email not unique');  }        
        return $retobj;
    }
    
      function IsklantIdValid ($Id)
    {
          
         $query =$this->getDatafieldname('id').'='. $Id; // get the database name from getDatafield
         $this->convertdata(OpslagMedium::getObjectdataOnQuery(DEFTABLE,$query));              
	
        
    } 
           
   
           
    
    //gives yes if there is an instance in the object 
    public function isInstance(){
        if ($this->id<>0)
            {
        return true;
            }
        else { 
            return false;}
    }
   

   public function login($emailadres,$passw){
                if ( $this->passwoord == $passw){          
                    $this->loggedIn=true;	
                }
                else {
                    $this->loggedIn=false;
                }
                return $this->loggedIn;
        }
    
    //gives back the unique key
    public function getUniqueid(){
    return $this->data->uniqueKey;
    }    
    
    //gives back the data from this object
    public function getData()
    {
        return $this->data;
    }
    //Unlocks an account 
    public function Unlock(){
         $this->incorrectAttempts=0;
         OpslagMedium::updateObject(DEFTABLE,$this->id,$this->changedProperties);
         $query =$this->getDatafieldname('id').'='.$this->id.' ';
         $this->convertdata(OpslagMedium::getObjectdataOnQuery(DEFTABLE,$query));        
         return $this->IsLocked();
    }
            
    // gives back true if the account is active
    public function IsActivated(){
        if ($this->Enabled == true){
        return true;} 
        else {return false; }}
    // gives back true if acccount is locked
    public function IsLocked(){
        if ($this->lockedout == true){
                     return true;}
        else { return false;}}
        
    public function ZetLeverAdres($adres){

		$this->leveradres=$adres;
                $this->save();
	
	}
    public function getCurrentName(){
            return $this->emailadres;
    }
 

    public function zetNieuwPasswoord($passwold,$passwnew){
                if ( $this->passwoord == $passwold){          
                    $this->passwoord=$passwnew;	
                    return true;
                }
                else {
                    return false;
                }
        }
    public function forgotPassword($email){
                if ( $this->emailadres == $email){          
                    return $this->passwoord;	
           
                }
                else {
                    return null;
                }
                
        }
	public function logout(){
		$this->loggedIn=false;	
	}
	public function getKlantId (){		
		return $this->id;	
	}
        public function getCurrentEmail (){		
		return $this->emailadres;	
	}
        public function ingelogd(){
                return $this->loggedIn;	
        } 
        
        
        
        
    
     /// PRIVATE ///// 
      private function save() {
          $id=$this->id;
        $retid=OpslagMedium::updateObject(DEFTABLE,$this->id,$this->changedProperties);
                 if ($retid!=0){
                         
                               $query =$this->getDatafieldname('id').'='.$this->id.' ';
                               $this->convertdata(OpslagMedium::getObjectdataOnQuery(DEFTABLE,$query)); 
                               $retobj->result =true;
        }
        
   }
                 
                 
    private function valtodata($key,$value){
      $datafieldname = $this->getDatafieldname($key);
      if (is_null($datafieldname)==false){  //if there is a datafield
          $this->data->{$datafieldname}=$value;
       }
      else {
          $this->data->{$key}=$value ; //if there is NO translation we expect the name of the data is the same as the key
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
                
                   if ($key== 'loggedIn'){ 
                    
                             $this->valtodata($key,false);
                          
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
             
    // checkes if password complies to rules       
    private function IspasswordOk($password) {
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
    
    // chekes if the given email is unique 
    private function isEmailUnique($email){
        $flag =	  !OpslagMedium::doesRecordExist (DEFTABLE,'emailadres',$email);
        return $flag;
    }
    }
?>