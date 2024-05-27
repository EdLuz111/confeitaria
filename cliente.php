<?php
include './conexao.php';

// Captura o ID do cliente
$id = $_REQUEST['id'];

// Busca os dados do cliente
$sql_cliente = "SELECT id, nome, numero FROM clientes WHERE id = :id";
$stmt_cliente = $conexao->prepare($sql_cliente);
$stmt_cliente->bindParam(':id', $id, PDO::PARAM_INT);
$stmt_cliente->execute();
$cliente = $stmt_cliente->fetch(PDO::FETCH_OBJ);

// Busca os pedidos do cliente
$sql_pedidos = "SELECT id, tamanho, data_para_entrega, observacoes FROM pedidos WHERE id_cliente = :id_cliente";
$stmt_pedidos = $conexao->prepare($sql_pedidos);
$stmt_pedidos->bindParam(':id_cliente', $id, PDO::PARAM_INT);
$stmt_pedidos->execute();
$pedidos = $stmt_pedidos->fetchAll(PDO::FETCH_OBJ);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Cliente</title>
    <script>
        function goBack() {
            window.history.back();
        }
    </script>
</head>
<body>
    
    <form action="editar.php" method="post">
        <input type="hidden" name="id" value="<?php echo $cliente->id ?>">
        <span>Nome: </span><input type="text" name="nome" value="<?php echo $cliente->nome ?>">
        <br><br>
        <span>Número: </span>
        <input type="tel" pattern="\(\d{2}\) \d{5}-\d{4}" name="numero" value="<?php echo $cliente->numero ?>">
        <small>Formato: (XX) XXXXX-XXXX</small>
        <br><br>
        <input type="submit" value="Editar">
    </form>
    <button onclick="goBack()">Voltar</button>
    <?php foreach ($pedidos as $pedido): ?>
        <form action="editar_pedido.php" method="post">
            <input type="hidden" name="id_cliente" value="<?php echo $cliente->id ?>">
            <input type="hidden" name="id_pedido" value="<?php echo $pedido->id ?>">
            <span>Tamanho: </span><input type="text" name="tamanho" value="<?php echo $pedido->tamanho ?>">
            <br><br>
            <span>Data para entrega:</span>
            <input type="date" name="data_para_entrega" value="<?php echo $pedido->data_para_entrega ?>">
            <br><br>
            <span>Observações:</span>
            <input type="text" name="observacoes" value="<?php echo $pedido->observacoes ?>">
            <input type="submit" value="Editar pedido">
        </form>
        <br>
    <?php endforeach; ?>
</body>
</html>
