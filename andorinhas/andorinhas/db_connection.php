
<?php
// Configurações do banco de dados
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "andorinhas";

// Conectar ao banco de dados
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}
?>