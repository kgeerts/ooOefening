<?php

// dit zijn de oude testen gemaakt voor de database layer 
require_once ('../config.php');
require_once($serverpath['objects'] . '/klant.php');
require_once($serverpath['objects'] . '/boek.php');
require_once($serverpath['objects'] . '/bestelling.php');
$klant = new Klant('kjtgeerts@gmail.com', 'password');
print_r($klant);
//$klant->setLeverAdres("trammezandlei 44 shoten");
echo "nieuwe klant: " . (string) $klant->getKlantId() . "<br>";
echo "adres klant: " . $klant->getLeverAdres() . "<br>";
// $boek10 = new Boek("test","12345",25,'test','uitgever');

$boek1 = new Boek('AJAX and PHP: Building Modern Web Applications 2nd Edition Paperback', '978-1847197726', 24, 'B Brinzarea-Iamani', 'pact publishing ltd');
$boek2 = new Boek('jQuery in Action, Second Edition', '978-1935182320', 15, 'Bear Bibeault', 'Manning publications');
$boek3 = new Boek('Mijn eigen testboek', '123-1234567', 55, 'krisg', 'kweetniet');
$klant->login('kjtgeerts@gmail.com', 'password'); //try to comment it to see the error
//$titel=$boek1->getTitel();
//echo $titel ;
echo "twee nieuwe boeken in stock;<br> boek 1 heeft als titel :" . $boek1->getTitel() . " en boek 2:" . $boek2->getTitel();
$bestelling = Nieuwebestelling($klant);


// $bestelling = new Bestelling($klant);

$bestelling->voegBoekToe($boek1);
print_r($bestlling);
// $bestelling->voegBoekToe($boek2);

alterbestelling($bestelling, $boek2, true);
// $bestelling->sluitOrderAf(); to test if I get the exception thrown 

alterbestelling($bestelling, $boek3, true);
//$bestelling->printboeken();
echo "<h3> boeken uit bestelling :</h3>" . $bestelling->getBoeken();
$bestelling->verwijderBoek($boek3);
echo "<hr><br>Na verwijdering boek 3<br>";
echo "<h3> boeken uit bestelling :</h3>" . $bestelling->getBoeken();
//$bestelling->printboeken();
// wil nu een object maken zonder klant 

$bestelling3 = Nieuwebestelling($emtyclient);

$bestelling->sluitOrderAf();

function alterbestelling(&$bestelRef, &$boekref, $add = true) {   // to be able to catch the eception 
    try {
        if ($add == ktrue) {
            $bestelRef->voegBoekToe($boekref);
        } else {
            $bestelRef->verwijderBoek($boekref);
        }
    } catch (Exception $e) {
        echo "<br><br>error<br>";
        echo $e->getMessage();
        exit(1);
    }
    return $bestelRef;
}

function Nieuwebestelling(&$klantref) {
    try {
        $bestelRef = new Bestelling($klantref);
    } catch (Exception $e) {
        echo "<br><br>error<br>";
        echo $e->getMessage();
        exit(1);
    }
    return $bestelRef;
}

?>