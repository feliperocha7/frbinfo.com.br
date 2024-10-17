<?php
require_once 'db.php';

header('Content-Type: application/json');

try {
    $database = new Database();
    $conn = $database->getConnection();

    $query = "SELECT id, nome AS nome, caminho FROM empresa";
    $stmt = $conn->prepare($query);
    $stmt->execute();

    $empresas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($empresas);
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
?>
