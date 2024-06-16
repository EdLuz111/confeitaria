<?php
session_start(); // Inicie a sessão no início do arquivo

include './conexao.php';

$id = $_REQUEST['id'];
$nome = $_REQUEST['nome'];
$numero = $_REQUEST['numero'];

if (empty($nome) || empty($numero)) {
    $_SESSION['flash_message'] = 'Por favor, preencha todos os campos obrigatórios.';
    $_SESSION['flash_type'] = 'error';
    header("Location: cliente.php?id=$id");
    exit();
}

try {
    $sql_cliente = "UPDATE clientes SET nome = :nome, numero = :numero WHERE id = :id";
    $stmt_cliente = $conexao->prepare($sql_cliente);
    $stmt_cliente->bindParam(':nome', $nome);
    $stmt_cliente->bindParam(':numero', $numero);
    $stmt_cliente->bindParam(':id', $id);
    $stmt_cliente->execute();

    $_SESSION['flash_message'] = 'Dados do cliente atualizados com sucesso!';
    $_SESSION['flash_type'] = 'success';
    header("Location: cliente.php?id=$id");
    exit();
} catch (PDOException $e) {
    $_SESSION['flash_message'] = 'Erro: ' . $e->getMessage();
    $_SESSION['flash_type'] = 'error';
    header("Location: cliente.php?id=$id");
    exit();
}
?>
