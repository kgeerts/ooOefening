<?php
require_once($serverpath['objects'].'/boek.php');
/**
 * ObjFact_book creates a object of the type book out of a database return
 * 

 * 
 * @package ObjectFactory
 * @author ADMINKRIS <kjtgeerts@gmail.com>
 * @version 1.1
 * @Copyright Never
 * @link http://www.it8-projects.com
 * 
 */
class ObjFact_Book  {
  
    
     /**
     * Creates an object of the form book from the dao data object
     * 
     * @param1  data object as formed by the dao
     * 
     */
    public function create($data){

        $object = new Boek();
        
    
        $object->id=$data->id;
        $object->setIsbn($data->isbnnr);
        $object->setPrice($data->prijs);
        $object->setTitel($data->titel) ;
        $object->setUitgever($data->uitgever);
        $object->setAuteur($data->auteur);
        $object->InitChanged();/// resets the changed table which is influeced by the above settings

    return $object;
    }
}
