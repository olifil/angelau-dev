<?php


header('Content-Type: application/json');
$r = array('data' => "Réponse de la requete");
$r = json_encode($r);
echo $r;
