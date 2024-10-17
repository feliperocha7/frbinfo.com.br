<?php
class DatabaseEmpSilva {
    private $host = "mysql.frbinfo.com.br"; // Nome do host
    private $db_name = "frbinfo02"; // Nome do banco de dados
    private $username = "frbinfo02"; // Usuário do banco
    private $password = "Ffu180139"; // Senha do banco
    public $conn;  // Declare a propriedade $conn explicitamente
    // private $host = "localhost"; // Nome do host
    // private $db_name = "frbinfo"; // Nome do banco de dados
    // private $username = "root"; // Usuário do banco
    // private $password = ""; // Senha do banco
    // public $conn;

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