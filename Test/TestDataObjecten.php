<?php
require_once ('../config.php');
require_once($serverpath['objects'].'/boekService.php');

?>

<html>
	<head>
		<meta http-equiv="Content-Type" value="text/html; charset=utf-8" />
		<title> Datamanager - PHP-cursus</title>
	</head>
	<body>
            
		<h1>Opvragenklant - Detail</h1>
		<?php
                 /*
			$klant = new Klant('kjtgeerts@gmail.com');
			echo $klant->__toString();
                    */  
		?>
                <h1>Inloggen klant - </h1>
		<?php
                     /*
                       $passw ="password";
                       $passwordok = $klant->login($klant->getCurrentEmail(), $passw);
                       if ($passwordok == true) {
                           echo 'password \''.$passw.'\' is correct ';
                       }
                       else{
                               echo 'password \''.$passw.'\' is NIET  correct ';
                       }
                       echo "even testen in het object";
                       echo $klant->__toString(); 
                     */       
                        
		?>
                
                 <h1>Maken en bewaren klant - </h1>
		<?php                        
                   /*
                        $email= 'emadil@gmail.com';
                        $klant = new Klant($email);
                        $passw ="a123456";
                     
                        $retobj = $klant->create($email, $passw);
                        if($retobj->result==FALSE){
                            echo 'create niet gedaan want emailbestaat al <br>';
                        }
                        else {
                            echo 'Nieuw account gemaakt ';
                            $klant->ZetLeverAdres('testadres straatnr gemeente');
                            echo "even testen in het <br>";
                             echo $klant->__toString();                         
                        }
                    */     
                ?>
                 
               <h1>Opvragenboek - </h1>
		<?
 
                $bookServ = new BoekService;
                $book = new boek();    
                $book->titel='Nieuwboek';
                $book->setIsbn('0123-1235455');
                $book->setPrice(15.89);
                $book->setUitgever('DeUitgever');
                $book->setAuteur('auteur');
                $book->InitChanged();
                $book = $bookServ->GetDao()->save($book);// new book object saved

                
                
                    
                $book = $bookServ->GetbookByISBN("978-1935182320");   
                $book2 = $book;
                
                 /*   
                $book =  $bookServ->GetbookById(1);
                $book->titel='Nieuwtitel';
                bookServ->GetDao()->save($book);
                */
              //  $bookServ->GetNewBook();          
                $Dao = $bookServ->GetDao();
                
                
                
                
               // $book = $boekdao->GetObject();
               /* 
                $boekDAO = new BoekDAO('');   
                
                
                
                         $book = new Boek();
                         $book->setIsbn('012-456789');
                         $book->setPrice(15,23);
                         $book->setTitel('dit is een titel');
                         $book->setUitgever('uitgever');
                         $book->setAuteur('auteur');
                         
                         
                $book = $boekDAO->save($book);
                      


                /*
                $boekDAO = new BoekDAO('978-1935182320');   
                        $boek = $boekDAO->MakeObject();
		
			echo $boek->__toString();    
                   */   
		?>     
                    <h1>Aanmakenbestelling - </h1>
		<?php
                     /* 
			$bestelling = new Bestelling(1);
			echo $bestelling->__toString();
                    */
                      
		?>  
		
                  <h1>GetBestelling - </h1>
		<?php
               
                 //      ; $service = new Service();
                  //     $bestelling = Service::getBetstelling('2014-4-26:0001');
                      
		//	$bestelling = new Bestelling(null,'2014-4-26:0001');
              
		//	echo $bestelling->__toString();
            
                      
		?>     
                    
	</body>
</html>

<?php
?>
