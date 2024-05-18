<?php 
include './conexao.php';

$nome = $_REQUEST['nome'];
$numero = $_REQUEST['numero'];

try {
    $sql = 'INSERT INTO clientes (nome, numero) VALUES (:nome, :numero)';
    $stmt = $conexao->prepare($sql);
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':numero', $numero);
    $stmt->execute();

    echo "Cliente cadastrado com sucesso!";
    header('Location: index.php');
    exit();
} catch (PDOException $e) {
    echo 'Erro: ' . $e->getMessage();
}
?>
