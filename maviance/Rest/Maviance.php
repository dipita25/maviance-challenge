<?php
require_once 'include/Config.php';

class Maviance {

    private $con;
    private $rate_list = array();
    private $currency_list = array();
    private $rate;
    private $tableau = array('currency' =>array() , 'rate'=> array());

    // constructor
    function __construct() {
        $this->con = new PDO('mysql:host='. DB_HOST .';dbname='.DB_DATABASE.';charset=utf8',DB_USER, DB_PASSWORD);

        return $this->con;
    }

    //cette methode sert à rechercher les devises et leurs valeurs sur le site de ECB
    //et les sauvegarder en Base de données toutes les 05 minutes
    public function GetCurrencies(){

        try{
           $xml=simplexml_load_file("http://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml");
               for($i=0;$i<sizeof($xml->Cube->Cube->Cube);$i++){ 

                    array_push($this->tableau['currency'], $xml->Cube->Cube->Cube[$i]['currency']);
                    array_push($this->tableau['rate'], $xml->Cube->Cube->Cube[$i]['rate']);

                    //sauvegarde en Base de données
                    $today = date("Y-m-d H:i:s");
                    $requete_insertion = "INSERT INTO conversion(currency,rate,dateObtaining) VALUES('".$xml->Cube->Cube->Cube[$i]['currency']."','".$xml->Cube->Cube->Cube[$i]['rate']."','".$today."')";
                    $reponse = $this->con->query($requete_insertion);
                }
            
            echo (json_encode($this->tableau['currency']));
        }
        catch (Throwable $t)
        {
           echo ("erreur de connection lors de la recuperation du fichier");
        }
    }

    public function CONVERT($currency, $amount){
        try{
            //on recupere le fichier contenant les devises supportées par ECB
            $xml=simplexml_load_file("http://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml");

            $trouve = false;//ce booléen passera à TRUE si la devise saisi par l'utilisateur est supportée par ECB

            //on remplit les tableaux respectifs comprenant les devises et leurs valeurs aux memes indices de tableaux
            for($i=0;$i<sizeof($xml->Cube->Cube->Cube);$i++){ 

                array_push($this->tableau['currency'], $xml->Cube->Cube->Cube[$i]['currency']);
                array_push($this->tableau['rate'], $xml->Cube->Cube->Cube[$i]['rate']);
            }

            //on parcourt les tableaux precedemment remplis pour verifier si la devise saisie par l'utilisateur s'y trouve
            //si elle s'y trouve on recupere sa valeur dans le second tableau et on fait la conversion
            for($i=0 ; $i < sizeof($this->tableau['currency']) ; $i++){

                if($currency == $this->tableau['currency'][$i]){
                    $trouve = true;
                    $rate = (float)$this->tableau['rate'][$i];
                    $resultat = (float)$amount / $rate ;
                    echo (json_encode($resultat));
                }
            }
            if($trouve == false){
                echo "cette devise n'est pas prise en charge par la ECB";
            }
        }
        catch (Throwable $t)
        {
           echo ("erreur de connection lors de la recuperation du fichier");
        }
    }

    public function REVERSE($currency, $amount){
        try{
            //on recupere le fichier contenant les devises supportées par ECB
            $xml=simplexml_load_file("http://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml");

            $trouve = false;//ce booléen passera à TRUE si la devise saisi par l'utilisateur est supportée par ECB

            //on remplit les tableaux respectifs comprenant les devises et leurs valeurs aux memes indices de tableaux
            for($i=0;$i<sizeof($xml->Cube->Cube->Cube);$i++){ 

                array_push($this->tableau['currency'], $xml->Cube->Cube->Cube[$i]['currency']);
                array_push($this->tableau['rate'], $xml->Cube->Cube->Cube[$i]['rate']);
            }

            //on parcourt les tableaux precedemment remplis pour verifier si la devise saisie par l'utilisateur s'y trouve
            //si elle s'y trouve on recupere sa valeur dans le second tableau et on fait la conversion
            for($i=0 ; $i < sizeof($this->tableau['currency']) ; $i++){

                if($currency == $this->tableau['currency'][$i]){
                    $trouve = true;
                    $rate = (float)$this->tableau['rate'][$i];
                    $resultat = (float)$amount * $rate ;
                    echo ($resultat);
                }
            }
            
            if($trouve == false){
                echo "cette devise n'est pas prise en charge par la ECB";
            }
        }
        catch (Throwable $t)
        {
           echo ("erreur de connection lors de la recuperation du fichier");
        }
    }   
}

?>
