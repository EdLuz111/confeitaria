<?php
    // conexao com o bd
    include './conexao.php';

    $id = $_REQUEST['id'];
    $nome = $_REQUEST['nome'];
    $numero = $_REQUEST['numero'];

    $sql = "UPDATE clientes SET nome = :nome, numero = :numero WHERE id = :id ";

    $stmt = $conexao->prepare($sql);
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':numero', $numero);
    $stmt->bindParam(':id', $id);
    $stmt->execute(); 

    header("Location: cliente.php?id=$id");
?>