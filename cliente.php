<?php
include './conexao.php';

// Captura o ID do cliente
$id = $_REQUEST['id'];

// Verifica se o formulário de edição foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Atualiza os dados do cliente
    $sql_update = "UPDATE clientes SET nome = :nome, numero = :numero WHERE id = :id";
    $stmt_update = $conexao->prepare($sql_update);
    $stmt_update->bindParam(':nome', $_POST['nome'], PDO::PARAM_STR);
    $stmt_update->bindParam(':numero', $_POST['numero'], PDO::PARAM_STR);
    $stmt_update->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt_update->execute();
}

// Busca os dados do cliente
$sql_cliente = "SELECT id, nome, numero FROM clientes WHERE id = :id";
$stmt_cliente = $conexao->prepare($sql_cliente);
$stmt_cliente->bindParam(':id', $id, PDO::PARAM_INT);
$stmt_cliente->execute();
$cliente = $stmt_cliente->fetch(PDO::FETCH_OBJ);

// Busca os pedidos do cliente
$sql_pedidos = "SELECT id, tamanho, data_para_entrega, observacoes, preco FROM pedidos WHERE id_cliente = :id_cliente";
$stmt_pedidos = $conexao->prepare($sql_pedidos);
$stmt_pedidos->bindParam(':id_cliente', $id, PDO::PARAM_INT);
$stmt_pedidos->execute();
$pedidos = $stmt_pedidos->fetchAll(PDO::FETCH_OBJ);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Cliente</title>
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
    <style>
        <?php include './style.css'?>
    </style>
</head>
<body>
<div class="wrapper">
    <div class="sidebar">
        <h2>Dashboard</h2>
        <a href="index.php#painel">Painel</a>
        <a href="index.php#calendario">Calendário</a>
        <a href="index.php#clientes">Clientes</a>
        <a href="index.php#pedidos">Pedidos</a>
    </div>

    <div class="content">
        <section id="cliente">
            <h2>Editar Cliente</h2>
            <form action="" method="post">
                <input type="hidden" name="id" value="<?php echo $cliente->id ?>">
                <label>Nome: </label><input type="text" name="nome" value="<?php echo $cliente->nome ?>">
                <br><br>
                <label>Número: </label>
                <input type="tel" pattern="\(\d{2}\) \d{5}-\d{4}" name="numero" value="<?php echo $cliente->numero ?>">
                <small>Formato: (XX) XXXXX-XXXX</small>
                <br><br>
                <input type="submit" value="Editar">
            </form>
            <br>
            <button onclick="window.location.href='index.php'">Voltar</button>
            <?php foreach ($pedidos as $pedido): ?>
            <form action="editar_pedido.php" method="post">
                <input type="hidden" name="id_cliente" value="<?php echo $cliente->id ?>">
                <input type="hidden" name="id_pedido" value="<?php echo $pedido->id ?>">
                <label>Tamanho: </label><input type="text" name="tamanho" value="<?php echo $pedido->tamanho ?>">
                <br><br>
                <label>Data para entrega:</label>
                <input type="date" name="data_para_entrega" value="<?php echo $pedido->data_para_entrega ?>">
                <br><br>
                <label>Observações:</label>
                <input type="text" name="observacoes" value="<?php echo $pedido->observacoes ?>">
                <br><br>
                <label>Preço:</label>
                <input type="number" step="0.01" name="preco" value="<?php echo $pedido->preco ?>">
                <input type="submit" value="Editar pedido">
            </form>
            <br>
            <?php endforeach; ?>
        </section>
    </div>
</div>

<?php include './footer.php'?>

</body>
</html>
