<?php
session_start(); // Inicie a sessão

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
        $_SESSION['flash_message'] = 'Por favor, preencha todos os campos obrigatórios.';
        $_SESSION['flash_type'] = 'error';
        header("Location: cliente.php?id=$id_cliente");
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

        $_SESSION['flash_message'] = 'Pedido cadastrado com sucesso!';
        $_SESSION['flash_type'] = 'success';
        header("Location: cliente.php?id=$id_cliente");
        exit();
    } catch (PDOException $e) {
        $_SESSION['flash_message'] = 'Erro: ' . $e->getMessage();
        $_SESSION['flash_type'] = 'error';
        header("Location: cliente.php?id=$id_cliente");
        exit();
    }
} else {
    $_SESSION['flash_message'] = 'Por favor, preencha todos os campos obrigatórios.';
    $_SESSION['flash_type'] = 'error';
    header("Location: cliente.php?id=$id_cliente");
    exit();
}
?>
