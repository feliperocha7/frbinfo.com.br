<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/frbinfo.com.br/db.php'; 

class Auth {
    private $conn;
    const SESSION_TIMEOUT = 1800; // 30 minutos em segundos

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();

        if ($this->conn === null) {
            die("Erro: Não foi possível conectar ao banco de dados.");
        }
    }

    public function login($username, $password) {
        try {
            if (empty($username) || empty($password)) {
                error_log("Login falhou: nome de usuário ou senha vazios.");
                echo "Por favor, preencha todos os campos.";
                return false;
            }

            $query = "SELECT * FROM usuarios WHERE usuario = :username AND ativo = 1";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':username', $username);
            $stmt->execute();
    
            if ($stmt->rowCount() == 1) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                if (password_verify($password, $user['senha'])) {
                    session_regenerate_id(true);
                    $_SESSION['user'] = $user['usuario'];
                    $_SESSION['perfil'] = $user['perfil'];
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['produto'] = $user['produto'];
                    $_SESSION['loja'] = $user['loja'];
                    $_SESSION['session_id'] = session_id(); // Armazena o ID da sessão
                    $_SESSION['last_activity'] = time(); // Armazena o timestamp da última atividade

                    // Atualiza o banco de dados com o novo ID da sessão
                    $updateQuery = "UPDATE usuarios SET session_id = :session_id WHERE id = :user_id";
                    $updateStmt = $this->conn->prepare($updateQuery);
                    $updateStmt->bindParam(':session_id', $_SESSION['session_id']);
                    $updateStmt->bindParam(':user_id', $_SESSION['user_id']);
                    if ($updateStmt->execute()) {
                        return true;
                    } else {
                        error_log("Erro ao atualizar o ID da sessão no banco de dados.");
                        echo "Erro interno. Tente novamente mais tarde.";
                        return false;
                    }
                } else {
                    error_log("Senha incorreta para o usuário: $username.");
                    echo "Senha incorreta!";
                    return false;
                }
            } else {
                error_log("Usuário não encontrado ou inativo: $username.");
                echo "Usuário não encontrado ou inativo!";
                return false;
            }
        } catch (PDOException $exception) {
            error_log("Erro de banco de dados: " . $exception->getMessage());
            echo "Erro de conexão com o banco de dados.";
            return false;
        }
    }

    public function checkSession() {
        try {
            // Verifica se a sessão está ativa
            if (!isset($_SESSION['user'])) {
                error_log("Tentativa de acesso sem sessão ativa.");
                header("Location: /frbinfo.com.br/login.php");
                exit();
            }

            // Verifica se o tempo de inatividade excede o limite
            if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > self::SESSION_TIMEOUT)) {
                error_log("Sessão expirada devido à inatividade.");
                $this->logout(); // Encerra a sessão
            }

            // Atualiza o timestamp da última atividade
            $_SESSION['last_activity'] = time();
            
            // Verifica se o ID da sessão armazenado no banco de dados corresponde ao ID atual
            $query = "SELECT session_id FROM usuarios WHERE id = :user_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':user_id', $_SESSION['user_id']);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user === false) {
                error_log("Usuário não encontrado no banco de dados.");
                echo "Erro interno. Tente novamente mais tarde.";
                $this->logout();
            } elseif ($user['session_id'] !== session_id()) {
                error_log("ID da sessão não corresponde para o usuário: " . $_SESSION['user']);
                echo "Sessão inválida. Faça login novamente.";
                $this->logout();
            } else {
                error_log("Sessão válida para o usuário: " . $_SESSION['user']);
                return true;
            }

            return false;
        } catch (PDOException $exception) {
            error_log("Erro de banco de dados durante a verificação da sessão: " . $exception->getMessage());
            echo "Erro de conexão com o banco de dados.";
            $this->logout();
        }
    }

    public function logout() {
        try {
            // Limpa o ID da sessão no banco de dados
            $query = "UPDATE usuarios SET session_id = NULL WHERE id = :user_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':user_id', $_SESSION['user_id']);
            $stmt->execute();
        } catch (PDOException $exception) {
            error_log("Erro ao limpar o ID da sessão durante o logout: " . $exception->getMessage());
        }

        session_unset();
        session_destroy();
        session_regenerate_id(true);
        header("Location: ./login.php");
        exit();
    }
}
?>
