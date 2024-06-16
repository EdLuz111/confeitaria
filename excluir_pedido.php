<?php

    include './conexao.php';

    $id = $_REQUEST['id'];

    $sql = 'DELETE FROM pedidos WHERE id = :id';
    $stmt = $conexao->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    header('Location: index.php?section=pedidos');

?>
