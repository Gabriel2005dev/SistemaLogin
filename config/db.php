<?php 

class Database {
    private $host = "localhost";
    private $db_name = "sistema_login";
    private $username = "root";
    private $password = "";

    public function conectar() {
        try {
            return new PDO(
                "mysql:host={$this->host};dbname={$this->db_name}",
                $this->username,
                $this->password,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
        } catch (PDOException $e) {
            die("Erro: " . $e->getMessage());
        }
    }
}


?>