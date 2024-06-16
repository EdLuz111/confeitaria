<?php 
session_start(); // Inicie a sessão

include './conexao.php';

if (isset($_POST['nome']) && isset($_POST['numero'])) {
    $nome = $_POST['nome'];
    $numero = $_POST['numero'];

    if (empty($nome) || empty($numero)) {
        $_SESSION['flash_message'] = 'Por favor, preencha todos os campos.';
        $_SESSION['flash_type'] = 'error';
        header('Location: index.php?section=clientes');
        exit();
    }

    try {
        $sql = 'INSERT INTO clientes (nome, numero) VALUES (:nome, :numero)';
        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':numero', $numero);
        $stmt->execute();

        $_SESSION['flash_message'] = 'Cliente cadastrado com sucesso!';
        $_SESSION['flash_type'] = 'success';
        header('Location: index.php?section=clientes');
        exit();
    } catch (PDOException $e) {
        $_SESSION['flash_message'] = 'Erro: ' . $e->getMessage();
        $_SESSION['flash_type'] = 'error';
        header('Location: index.php?section=clientes');
        exit();
    }
} else {
    $_SESSION['flash_message'] = 'Por favor, preencha todos os campos.';
    $_SESSION['flash_type'] = 'error';
    header('Location: index.php?section=clientes');
    exit();
}
?>
