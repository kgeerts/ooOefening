<?php
require_once($serverpath['di'].'/Boek_DI_Container.php');
/*
 * licences to it8-projects.com
 * author = kjtgeerts@gmail.com  * 
 */

/**
 * Description of bookService
 *
 * @author adminkris
 */
class BoekService extends Boek_DI_Container {
    //put your code here
    public function GetbookByISBN($isbn){
        $this->book = $this->Dao->retrieveByISBN($isbn);
        return $this->book;
    }
    
    public function GetbookById($id){
          $this->book = $this->Dao->retrieveByid($id);
        return $this->book;
    }
    

    
    
    
}
