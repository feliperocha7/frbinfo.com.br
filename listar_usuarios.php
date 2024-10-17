<?php
require_once 'db.php';

header('Content-Type: application/json');

try {
    $database = new Database();
    $conn = $database->getConnection();

    $query = "SELECT id, usuario, perfil, produto FROM usuarios";
    $stmt = $conn->prepare($query);
    $stmt->execute();

    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($usuarios);
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
?>
