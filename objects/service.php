<?php



require_once($serverpath['db'] . '/bestellingDAO.php');
require_once($serverpath['objects'] . '/bestelling.php');



class Service {
    
      public function getBetstelling($bestelnr ) {
        $bestDAO = new BestellingDAO($bestelnr);
        
        $bestelling = $bestDAO->MakeObject();
       ; $data = BestellingDAO::retrieveByOrderNr($bestelnr);
        ;return new mailsetting($data);
    }

    //put your code here
}

?>
