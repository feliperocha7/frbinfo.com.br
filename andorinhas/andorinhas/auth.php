<?php
// Remova a linha session_start(); se ela já estiver no arquivo que está incluindo auth.php
require_once 'db.php';

class Auth {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();

        // Verifica se a conexão com o banco foi bem-sucedida
        if ($this->conn === null) {
            die("Erro: Não foi possível conectar ao banco de dados.");
        }
    }

    public function login($username, $password) {
        try {
            $query = "SELECT * FROM usuarios WHERE username = :username AND ativo = 1";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':username', $username);
            $stmt->execute();

            if ($stmt->rowCount() == 1) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if (password_verify($password, $user['senha'])) {
                    $_SESSION['user'] = $user['username'];
                    $_SESSION['perfil'] = $user['perfil'];
                    $_SESSION['user_id'] = $user['id'];
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } catch (PDOException $exception) {
            echo "Erro: " . $exception->getMessage();
            return false;
        }
    }

    public function checkSession() {
        return isset($_SESSION['user']);
    }

    public function logout() {
        session_destroy();
        header("Location: index.php");
        exit();
    }
}
?>
