<?php
class Database {
    private $host = "localhost";
    private $dbname = "my_shop";
    private $username = "root";
    private $password = "";
    public $connexion;

    public function connect() {
        try {

            $this->connexion = new PDO("mysql:host={$this->host};dbname={$this->dbname};charset=utf8", $this->username, $this->password);
            $this->connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // echo "connexion succesfully";

        } catch (PDOException $e) {

            error_log("[" . date("D, d M Y H:i:s") . "] " . $e->getMessage() . "\n", 3, "./error_log_file.log");
            echo("Erreur de connexion à la base de données.");
        }
        return $this->connexion;
    }
}
?>