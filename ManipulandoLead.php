<?php
require_once("RDStationAPI.php");
$rdAPI = new RDStationAPI();

$rdAPI->setClienteId("*****");
$rdAPI->setClienteSecret("****");
$rdAPI->setRefreshToken("******");
$rdAPI->setEmail("luiz@jornada.com");

//$rdAPI->mudarEstagioFunil(array("lifecycle_stage"=>"Lead", "opportunity"=>false));   // Altera Funil 😎  
//$rdAPI->manipularLead("OPPORTUNITY"); // Marca Oportunidade 😎
//$rdAPI->manipularLead("SALE", 2); // Marca Venda 😎
// $rdAPI->manipularLead("OPPORTUNITY_LOST",NULL, "PERDEMO A VENDA"); // Marca Venda como Perdida :( 😎
