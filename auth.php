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
    
                // Verifica se a senha fornecida corresponde ao hash armazenado
                if (password_verify($password, $user['senha'])) {
    
                    // Desconectar a sessão anterior, se existir
                    if (!empty($user['session_id'])) {
                        $updateQuery = "UPDATE usuarios SET session_id = NULL WHERE id = :user_id";
                        $updateStmt = $this->conn->prepare($updateQuery);
                        $updateStmt->bindParam(':user_id', $user['id']);
                        $updateStmt->execute();
                    }
    
                    // Regenerar a sessão após o login bem-sucedido
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
                    if ($updateStmt->execute()) {
                        error_log("Sessão atualizada no banco de dados com sucesso.");
                    } else {
                        error_log("Falha ao atualizar sessão no banco de dados.");
                    }
    
                    return true;
                } else {
                    error_log("Senha incorreta.");
                    echo "Senha incorreta!"; // Mensagem de senha incorreta
                    return false;
                }
            } else {
                error_log("Usuário não encontrado ou inativo.");
                echo "Usuário não encontrado ou inativo!"; // Mensagem de usuário não encontrado
                return false;
            }
        } catch (PDOException $exception) {
            error_log("Erro de banco de dados: " . $exception->getMessage()); // Exibe erro se ocorrer
            return false;
        }
    }
    

    public function checkSession() {
        // Verifica se a sessão está ativa
        if (!isset($_SESSION['user'])) {
            error_log("Sessão não definida.");
            header("Location: ./login.php"); // Caminho absoluto
            exit();
        }
    
        // Verifica se o ID da sessão armazenado no banco de dados corresponde ao ID atual
        $query = "SELECT session_id FROM usuarios WHERE id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $_SESSION['user_id']);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
        // Verifica se o ID da sessão corresponde
        if ($user === false) {
            error_log("Usuário não encontrado no banco de dados.");
            $this->logout();
        } elseif ($user['session_id'] !== session_id()) {
            error_log("ID da sessão não corresponde. Sessão: " . session_id() . ", Sessão do banco: " . $user['session_id']);
            $this->logout();
        } else {
            error_log("Sessão válida para o usuário: " . $_SESSION['user']);
            return true;
        }
    
        return false;
    }
    

    public function logout() {
        // Limpa o ID da sessão no banco de dados
        $query = "UPDATE usuarios SET session_id = NULL WHERE id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $_SESSION['user_id']);
        $stmt->execute();

        session_unset();
        session_destroy();
        session_regenerate_id(true);  // Gera um novo ID de sessão para segurança
        header("Location: ./index.php");  // Caminho absoluto
        exit();
    }
}
?>
