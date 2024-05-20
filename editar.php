<?php
    // conexao com o bd
    include './conexao.php';

    $id = $_REQUEST['id'];
    $nome = $_REQUEST['nome'];
    $numero = $_REQUEST['numero'];

    $sql_cliente = "UPDATE clientes SET nome = :nome, numero = :numero WHERE id = :id ";

    $stmt_cliente = $conexao->prepare($sql_cliente);
    $stmt_cliente->bindParam(':nome', $nome);
    $stmt_cliente->bindParam(':numero', $numero);
    $stmt_cliente->bindParam(':id', $id);
    $stmt_cliente->execute(); 


    header("Location: cliente.php?id=$id");
?>