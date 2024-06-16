<?php
include './conexao.php';
    $id_pedido = $_GET['id'];

    $sql_atualiza_status = "UPDATE pedidos SET status = 'entregue' WHERE id = :id";
    $stmt = $conexao->prepare($sql_atualiza_status);
    $stmt->bindParam(':id', $id_pedido);
    $stmt->execute();

    header('Location: index.php#pedidos');
    exit();
?>
