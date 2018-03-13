<?php

    //entete permettant d'eviter les problèmes de CORS (Cross Origin Resource Sharing)
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: text/plain charset=UTF-8");
    header("Access-Control-Allow-Headers: Accept,Authorization,Cache-Control,Content-Type,DNT,If-Modified-Since,Keep-Alive,Origin,User-Agent,X-Requested-With");

    /*
        Url de type : localhost/meviance/Rest/ApiConversion.php
    */

    require_once './Maviance.php';

	$db = new Maviance();

    //si les données sont envoyées par un formulaire et sont toutes les 2 présentes
    if (isset($_POST['currency']) and isset($_POST['amount'])) {
        //on verifie que le montant saisi comporte un seul point et qu'il n'est pas négatif
        //les virgules ne sont pas acceptées
        if(preg_match("'^[0-9]{1,}[.]{0,1}[0-9]{0,2}$'isU", $_POST['amount']) && floatval($_POST['amount']) > 0){

            $reponse = $db->CONVERT($_POST['currency'], floatval($_POST['amount']));
            return ($reponse);
        }
        else
        {
            echo ("le montant saisi est invalide");
        }
    }
    else
    {
        echo ("donnees non fournies");
    }

    

?>