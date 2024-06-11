<?php

include './conexao.php';

$id_cliente = $_REQUEST['id_cliente'];
$id_pedido = $_REQUEST['id_pedido'];
$tamanho = $_REQUEST['tamanho'];
$observacoes = $_REQUEST['observacoes'];
$data_para_entrega = $_REQUEST['data_para_entrega'];
$preco = $_REQUEST['preco']; 

$sql_pedidos = "UPDATE pedidos SET tamanho = :tamanho, data_para_entrega = :data_para_entrega, observacoes = :observacoes, preco = :preco WHERE id = :id";
$stmt_pedidos = $conexao->prepare($sql_pedidos);
$stmt_pedidos->bindParam(':id', $id_pedido);
$stmt_pedidos->bindParam(':tamanho', $tamanho);
$stmt_pedidos->bindParam(':data_para_entrega', $data_para_entrega);
$stmt_pedidos->bindParam(':observacoes', $observacoes);
$stmt_pedidos->bindParam(':preco', $preco);
$stmt_pedidos->execute();

header("Location: cliente.php?id=$id_cliente");
?>
