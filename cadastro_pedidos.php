<?php
include './conexao.php';

// Verifica se todos os campos obrigatórios foram recebidos via POST
if (
    isset($_POST['tamanho']) && 
    isset($_POST['data_para_entrega']) && 
    isset($_POST['id_cliente']) && 
    isset($_POST['preco'])
) {
    $tamanho = $_POST['tamanho'];
    $data_para_entrega = $_POST['data_para_entrega'];
    $observacoes = isset($_POST['observacoes']) ? $_POST['observacoes'] : null;
    $id_cliente = $_POST['id_cliente'];
    $preco = $_POST['preco'];

    if (empty($tamanho) || empty($data_para_entrega) || empty($id_cliente) || empty($preco)) {
        echo "<script>alert('Por favor, preencha todos os campos obrigatórios.'); window.location.href='index.php?section=pedidos';</script>";
        exit();
    }

    try {
        $sql = 'INSERT INTO pedidos (tamanho, data_para_entrega, observacoes, id_cliente, preco) VALUES (:tamanho, :data_para_entrega, :observacoes, :id_cliente, :preco)';
        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(':tamanho', $tamanho);
        $stmt->bindParam(':data_para_entrega', $data_para_entrega);
        $stmt->bindParam(':observacoes', $observacoes);
        $stmt->bindParam(':id_cliente', $id_cliente);
        $stmt->bindParam(':preco', $preco);
        $stmt->execute();

        echo "<script>alert('Pedido cadastrado com sucesso!'); window.location.href='index.php?section=pedidos';</script>";
        exit();
    } catch (PDOException $e) {
        echo 'Erro: ' . $e->getMessage();
    }
} else {
    echo "<script>alert('Por favor, preencha todos os campos obrigatórios.'); window.location.href='index.php?section=pedidos';</script>";
}
?>
