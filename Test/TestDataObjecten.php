<?php
require_once ('../config.php');
require_once($serverpath['objects'].'/klant.php');
require_once($serverpath['objects'].'/boek.php');
require_once($serverpath['objects'].'/bestelling.php');
require_once($serverpath['objects'].'/service.php');

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
		<?php
               /*            
			$boek = new Boek('978-1935182320');
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
               
                       ; $service = new Service();
                       $bestelling = Service::getBetstelling('2014-4-26:0001');
                      
		//	$bestelling = new Bestelling(null,'2014-4-26:0001');
              
			echo $bestelling->__toString();
            
                      
		?>     
                    
	</body>
</html>

<?php
?>
