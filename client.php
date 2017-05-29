<?php
$soapClient = new SoapClient("http://localhost/exam-service/server/cinema.wsdl");

$functions = $soapClient->__getFunctions();
$cinemaList  = $soapClient->getCinemas();
echo "<pre>";
print_r($cinemaList);
?>