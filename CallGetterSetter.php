<?php
require_once ('config.php');
require_once 'objects/boek.php';
require_once 'GetterSetterGen.php';
$book = new Boek();
$getter = new GetterSetterGen(objects/boek.php);
$string = $getter->generate();
echo $string;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

