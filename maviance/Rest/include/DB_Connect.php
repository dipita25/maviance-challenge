<?php
require_once 'include/Config.php';
class DB_Connect {

    private $con;
    // constructor
    function __construct() {
         
        // connecting to mysql
        //$con = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD) or die(mysql_error());
        $this->con = new PDO('mysql:host='. DB_HOST .';dbname='.DB_DATABASE.';charset=utf8',DB_USER, DB_PASSWORD);

        return $this->con;
    }
    // destructor
    function __destruct() {
        // $this->close();
    }

    public function inserer($requete){

        $this->con->query($requete);
    }

    public function effacer($requete){

        $this->con->query($requete);
    }

    public function execute($requete){

        $donnees = $this->con->query($requete);

        return $donnees;
    }

    
    // Closing database connection
    public function close() {
        mysql_close();
    }

}

?>
