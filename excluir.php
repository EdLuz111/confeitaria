<?php
// Conexão com o banco de dados
include './conexao.php';

$id = $_REQUEST['id'];

// Verificar se o cliente possui pedidos ativos
$checkOrdersSql = "SELECT COUNT(*) as total FROM pedidos WHERE id_cliente = :id";
$checkStmt = $conexao->prepare($checkOrdersSql);
$checkStmt->bindParam(':id', $id);
$checkStmt->execute();
$result = $checkStmt->fetch(PDO::FETCH_ASSOC);

if ($result['total'] > 0) {
    // Cliente possui pedidos ativos, exibir mensagem
    echo "<script>alert('Este cliente possui pedidos ativos');window.location.href='index.php?section=clientes';</script>";
} else {
    // Cliente não possui pedidos ativos, pode ser excluído
    $sql = "DELETE FROM clientes WHERE id = :id";
    $stmt = $conexao->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute(); 

    // Redirecionar para a página inicial
    Header('Location: index.php?section=clientes');
}
?>
