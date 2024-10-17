<?php
require_once 'db.php';

class Auth {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();

        if ($this->conn === null) {
            die("Erro: Não foi possível conectar ao banco de dados.");
        }
    }

    public function login($username, $password) {
        try {
            $query = "SELECT * FROM usuarios WHERE usuario = :username AND ativo = 1";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':username', $username);
            $stmt->execute();

            if ($stmt->rowCount() == 1) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                // Exibe os dados do usuário para depuração
                //var_dump($user);
                //echo password_hash("123", PASSWORD_BCRYPT) . "<br>";
                // Verifica se a senha fornecida corresponde ao hash armazenado
                if (password_verify($password, $user['senha'])) {
                    // Verifica se o usuário já está logado
                    if (!empty($user['session_id'])) {
                        return false; // Usuário já está logado em outra sessão
                    }

                    // Regenerar a sessão
                    session_regenerate_id(true);
                    $_SESSION['user'] = $user['usuario'];
                    $_SESSION['perfil'] = $user['perfil'];
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['produto'] = $user['produto'];
                    $_SESSION['session_id'] = session_id(); // Armazena o ID da sessão

                    // Atualiza o banco de dados com o novo ID da sessão
                    $updateQuery = "UPDATE usuarios SET session_id = :session_id WHERE id = :user_id";
                    $updateStmt = $this->conn->prepare($updateQuery);
                    $updateStmt->bindParam(':session_id', $_SESSION['session_id']);
                    $updateStmt->bindParam(':user_id', $_SESSION['user_id']);
                    $updateStmt->execute();

                    return true;
                } else {
                    echo "Senha incorreta!"; // Mensagem de senha incorreta
                    return false;
                }
            } else {
                echo "Usuário não encontrado ou inativo!"; // Mensagem de usuário não encontrado
                return false;
            }
        } catch (PDOException $exception) {
            echo "Erro de banco de dados: " . $exception->getMessage(); // Exibe erro se ocorrer
            return false;
        }
    }

    public function checkSession() {
        // Verifica se a sessão está ativa
        if (!isset($_SESSION['user'])) {
            header("Location: login.php");
            exit();
        }
    
        // Verifica se o ID da sessão armazenado no banco de dados corresponde ao ID atual
        $query = "SELECT session_id FROM usuarios WHERE id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $_SESSION['user_id']);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
        // Verifica se o usuário foi encontrado e se o ID da sessão corresponde
        if ($user === false || $user['session_id'] !== session_id()) {
            $this->logout(); // Se o ID não corresponder, faz logout
        }
    
        return true; // Retorna true se a sessão estiver ativa
    }

    public function logout() {
        // Limpa o ID da sessão no banco de dados
        $query = "UPDATE usuarios SET session_id = NULL WHERE id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $_SESSION['user_id']);
        $stmt->execute();

        session_unset();
        session_destroy();
        header("Location: index.php");
        exit();
    }
}
?>
