<?php
require_once '../session_check.php';
require_once 'db.php'; // Inclua seu arquivo de conexão

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $loanId = $_POST['loanId'];

    // Conexão com o banco de dados
    $database = new DatabaseEmpSilva();
    $conn = $database->getConnection();

    // Consulta para obter as parcelas
    $query = "SELECT * FROM parcelas WHERE id_emprestimo = :loanId"; // Ajuste conforme seu banco
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':loanId', $loanId);
    $stmt->execute();
    $parcelas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Gera a tabela de parcelas
    echo '<table class="table table-bordered">';
    echo '<thead><tr><th>Parcela</th><th>Valor</th><th>Valor c/ 15%</th><th>Data de Vencimento</th><th>Ações</th></tr></thead>';
    echo '<tbody>';
    
    foreach ($parcelas as $index => $parcela) {
        $valorComJuros = $parcela['valor'] * 1.15; // 15% de juros
        $dataVencimento = date('Y-m-d', strtotime("+$index month", strtotime($parcela['data_contratacao'])));
        
        echo '<tr>';
        echo '<td>Parcela ' . ($index + 1) . '</td>';
        echo '<td>' . number_format($valorComJuros, 2, ',', '.') . '</td>';
        echo '<td>' . $parcela['valor'] . '</td>';
        echo '<td>' . $dataVencimento . '</td>';
        echo '<td><button class="btn btn-success" onclick="payInstallment(' . $parcela['id'] . ', ' . ($index + 1) . ')">Pagar</button></td>';
        echo '</tr>';
    }
    
    echo '</tbody></table>';
}
?>
