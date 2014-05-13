<?php
require_once($serverpath['objects'].'/klant.php');
require_once($serverpath['objects'].'/boek.php');
require_once($serverpath['db'].'/boekDAO.php');
require_once($serverpath['objects'].'/bestelling.php');
require_once($serverpath['objects'].'/service.php');

/**
 * Boek_DI_Container creates the objects in with the right dependancies
 * 
 * Its important that objects that other objects are depending on are NOT created in
 * the superobject itself but are created before .
 * With this container the knowledge about which object is needed to create another is 
 * taken care of 
 * 
 * @package DI
 * @author ADMINKRIS <kjtgeerts@gmail.com>
 * @version 1.1
 * @Copyright Never
 * @link http://www.it8-projects.com
 * 
 */


/*
 * licences to it8-projects.com
 * author = kjtgeerts@gmail.com  * 
 */

/**
 * Description of Boek_DI_Container
 *
 * @author adminkris
 */
class Boek_DI_Container {
    protected $book;
    private $bookfact;
    protected $Dao;
    //put your code here
    public function __construct()
    {
         $this->bookFact = new ObjFact_Book();
         $this->Dao = new BoekDAO($this->bookFact);
         
    }       
                
     public function GetDao(){
        return $this->Dao;
    }
    public function GetBook(){
        $this->book;
    }
  
   
    
}
