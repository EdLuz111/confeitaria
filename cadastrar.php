<?php 
include './conexao.php';

if (isset($_POST['nome']) && isset($_POST['numero'])) {
    $nome = $_POST['nome'];
    $numero = $_POST['numero'];

    if (empty($nome) || empty($numero)) {
        echo "<script>alert('Por favor, preencha todos os campos.'); window.location.href='index.php?section=clientes';</script>";
        exit();
    }

    try {
        $sql = 'INSERT INTO clientes (nome, numero) VALUES (:nome, :numero)';
        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':numero', $numero);
        $stmt->execute();

        echo "<script>alert('Cliente cadastrado com sucesso!'); window.location.href='index.php?section=clientes';</script>";
        exit();
    } catch (PDOException $e) {
        echo 'Erro: ' . $e->getMessage();
    }
} else {
    echo "<script>alert('Por favor, preencha todos os campos.'); window.location.href='index.php?section=clientes';</script>";
}
?>
