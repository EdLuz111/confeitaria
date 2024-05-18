<?php

    include './conexao.php';

    // Lista todos os alunos
    // comando de seleção
    $id = $_REQUEST['id'];
    $sql = "SELECT id,nome, numero FROM clientes WHERE id = :id";
    $stmt = $conexao->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $cliente = $stmt->fetch(PDO::FETCH_OBJ);
    
    

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="editar.php" method="post">
        <input type="hidden" name="id" value="<?php echo $cliente->id?>">
        <span>Nome: </span><input type="text" name="nome" value="<?php echo $cliente->nome?>">
        <br><br>
        <span>Numero: </span>
        <input type="tel" pattern="\(\d{2}\) \d{5}-\d{4}" name="numero" value="<?php echo $cliente->numero?>">
        <small>Formato: (XX) XXXXX-XXXX</small>
        <br><br>
        <input type="submit" value="Editar">
    </form>
</body>
</html>