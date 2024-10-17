<?php
class Database {
    private $host = "localhost"; // Nome do host
    private $db_name = "andorinhas"; // Nome do banco de dados
    private $username = "root"; // Usuário do banco
    private $password = ""; // Senha do banco
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            echo "Erro de conexão: " . $exception->getMessage();
        }
        return $this->conn;
    }
}
?>
