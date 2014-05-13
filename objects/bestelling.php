<?php

require_once '../config.php';

require_once($serverpath['db'] . '/logger.php');

require_once($serverpath['objects'] . '/assigned_books.php');
require_once($serverpath['objects'] . '/boek.php');

class Bestelling {

    private $id;
    private $klantid;
    private $Ordernr;
    private $totaalaankoop;
    private $betaald;
    private $geleverd;
    private $assignedbooks = array();

    function __construct() {

        $this->klantid = 0;
        $this->id = 0;
        $this->ordernr = uniqid();
        $this->totaalaankoop = 0;
        $this->betaald = false;
        $this->geleverd = false;
    }

    Public function setKlantid($klantid) {
        $this->klantid = $klantid;
    }

    Public function setid($id) {
        $this->id = $id;
    }

    Public function setOrderNr($ordernr) {
        $this->Ordernr = $ordernr;
    }

    Public function setTotaalaankoop($totaalaankoop) {
        $this->totaalaankoop = $totaalaankoop;
    }

    Public function setBetaald($betaald) {
        $this->betaald = $betaald;
    }

    Public function setGeleverd($geleverd) {
        $this->geleverd = $geleverd;
    }

    Public function setAssignedbooks($assignedbooks) {
        $this->assignedbooks = $assignedbooks;
    }

    public function addboek($isbnnr, $aantal) {
        $boek = new Boek($isbnnr);
        $assigned_books = new assigned_books($boek, $aantal);
    }

}

?>