<?php
include './conexao.php';

$tamanho = $_REQUEST['tamanho'];
$data_para_entrega = $_REQUEST['data_para_entrega'];
$observacoes = $_REQUEST['observacoes'];
$id_cliente = $_REQUEST['id_cliente'];
$preco = $_REQUEST['preco'];

try {
    $sql = 'INSERT INTO pedidos (tamanho, data_para_entrega, observacoes, id_cliente, preco) VALUES (:tamanho, :data_para_entrega, :observacoes, :id_cliente, :preco)';
    $stmt = $conexao->prepare($sql);
    $stmt->bindParam(':tamanho', $tamanho);
    $stmt->bindParam(':data_para_entrega', $data_para_entrega);
    $stmt->bindParam(':observacoes', $observacoes);
    $stmt->bindParam(':id_cliente', $id_cliente);
    $stmt->bindParam(':preco', $preco);
    $stmt->execute();

    echo "Pedido cadastrado com sucesso!";
    header('Location: index.php');
    exit();
} catch (PDOException $e) {
    echo 'Erro: ' . $e->getMessage();
}
?>
