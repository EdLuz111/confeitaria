<?php
include './conexao.php';

session_start(); // Inicie a sessão no início do arquivo

function calcularGanhoTotal($conexao, $id_cliente) {
    $sql = "SELECT SUM(preco) AS ganho_total FROM pedidos WHERE id_cliente = :id_cliente";
    $stmt = $conexao->prepare($sql);
    $stmt->bindValue(':id_cliente', $id_cliente, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC)['ganho_total'];
}

function calcularGanhosFuturos($conexao, $id_cliente) {
    $data_atual = date('Y-m-d');
    $sql = "SELECT SUM(preco) AS ganhos_futuros FROM pedidos WHERE id_cliente = :id_cliente AND data_para_entrega > :data_atual";
    $stmt = $conexao->prepare($sql);
    $stmt->bindValue(':id_cliente', $id_cliente, PDO::PARAM_INT);
    $stmt->bindValue(':data_atual', $data_atual, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC)['ganhos_futuros'];
}

function contarPedidosPendentes($conexao, $id_cliente) {
    $data_atual = date('Y-m-d');
    $sql = "SELECT COUNT(*) AS pedidos_pendentes FROM pedidos WHERE id_cliente = :id_cliente AND data_para_entrega > :data_atual AND status != 'entregue'";
    $stmt = $conexao->prepare($sql);
    $stmt->bindValue(':id_cliente', $id_cliente, PDO::PARAM_INT);
    $stmt->bindValue(':data_atual', $data_atual, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC)['pedidos_pendentes'];
}

// Captura o ID do cliente
$id_cliente = $_REQUEST['id'];

// Calcula os ganhos e pedidos pendentes do cliente específico
$ganho_total = calcularGanhoTotal($conexao, $id_cliente);
$ganhos_futuros = calcularGanhosFuturos($conexao, $id_cliente);
$pedidos_pendentes = contarPedidosPendentes($conexao, $id_cliente);

// Busca os dados do cliente
$sql_cliente = "SELECT id, nome, numero FROM clientes WHERE id = :id";
$stmt_cliente = $conexao->prepare($sql_cliente);
$stmt_cliente->bindParam(':id', $id_cliente, PDO::PARAM_INT);
$stmt_cliente->execute();
$cliente = $stmt_cliente->fetch(PDO::FETCH_OBJ);

// Busca os pedidos do cliente
$sql_pedidos = "SELECT id, tamanho, data_para_entrega, observacoes, preco FROM pedidos WHERE id_cliente = :id_cliente";
$stmt_pedidos = $conexao->prepare($sql_pedidos);
$stmt_pedidos->bindParam(':id_cliente', $id_cliente, PDO::PARAM_INT);
$stmt_pedidos->execute();
$pedidos = $stmt_pedidos->fetchAll(PDO::FETCH_OBJ);

if (isset($_SESSION['flash_message'])) {
    $flash_message = $_SESSION['flash_message'];
    $flash_type = $_SESSION['flash_type'];

    // Remova a mensagem flash da sessão após exibi-la
    unset($_SESSION['flash_message']);
    unset($_SESSION['flash_type']);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Cliente</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@200..700&display=swap" rel="stylesheet">

    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        <?php include './style.css'?>
    </style>
</head>
<body>

<div class="wrapper d-flex">
    <div class="sidebar bg-light p-3">
        <h2>Dashboard</h2>
        <aside class="sidebar">
            <nav class="nav flex-column">
                <h2 class="nav-link disabled">Confeitaria</h2>
                <a class="nav-link" href="index.php?section=dashboard">
                    <i class="bi bi-speedometer2"></i>Painel
                </a>
                <a class="nav-link" href="cliente.php?section=dados&id=<?php echo $cliente->id; ?>">
                    <i class="bi bi-calendar"></i>Dados
                </a>
            </nav>
        </aside>
    </div>

    <div class="content p-4">
        <?php if (isset($flash_message)): ?>
            <div class="alert alert-<?php echo $flash_type; ?>" role="alert">
                <?php echo $flash_message; ?>
            </div>
        <?php endif; ?>

        <div class="container" id="main">
            <div class="row">
                <div class="col-md-4">
                    <section id="cliente">
                        <h2>Dados do Cliente</h2>
                        <form action="editar.php" method="post" class="card">
                            <div class="card-header">Dados do Cliente</div>
                            <div class="form-group">
                                <label for="nome">Nome:</label>
                                <input type="text" id="nome" name="nome" class="form-control" value="<?php echo $cliente->nome ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="numero">Número:</label>
                                <input type="tel" id="numero" pattern="\(\d{2}\) \d{5}-\d{4}" name="numero" class="form-control" value="<?php echo $cliente->numero ?>" required>
                                <small>Formato: (XX) XXXXX-XXXX</small>
                            </div>
                            <input type="hidden" name="id" value="<?php echo $cliente->id ?>">
                            <input type="submit" value="Editar" class="btn btn-primary">
                        </form>
                    </section>

                    <section id="cadastrar-pedido" class="mt-4">
                        <h2>Cadastrar Pedido</h2>
                        <form action="cadastro_pedidos.php" method="post" class="card">
                            <div class="form-group">
                                <label for="tamanho">Tamanho:</label>
                                <input type="text" id="tamanho" name="tamanho" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="data_para_entrega">Data para entrega:</label>
                                <input type="date" id="data_para_entrega" name="data_para_entrega" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="observacoes">Observações:</label>
                                <input type="text" id="observacoes" name="observacoes" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="preco">Preço:</label>
                                <input type="number" id="preco" name="preco" class="form-control" step="0.01" required>
                            </div>
                            <input type="hidden" name="id_cliente" value="<?php echo $cliente->id ?>">
                            <input type="submit" value="Cadastrar Pedido" class="btn btn-primary">
                        </form>
                    </section>
                    <section id="ganhos-cliente" class="mt-4">
                        <h2>Ganhos do Cliente</h2>
                        <div class="card">
                            <div class="card-body">
                                <p><strong>Ganho Total: </strong>R$ <?php echo number_format($ganho_total, 2, ',', '.'); ?></p>
                                <p><strong>Ganhos Futuros: </strong>R$ <?php echo number_format($ganhos_futuros, 2, ',', '.'); ?></p>
                                <p><strong>Pedidos Pendentes: </strong><?php echo $pedidos_pendentes; ?></p>
                            </div>
                        </div>
                    </section>
                </div>
                <div class="col-md-8">
                    <section id="main">
                        <h2>Pedidos do Cliente</h2>
                        <div class="container-fluid pedidos_lista" >
                        <?php foreach ($pedidos as $pedido): ?>
                            <form action="editar_pedido.php" method="post" class="card" >
                                <div class="card-header">Pedido #<?php echo $pedido->id; ?></div>
                                <input type="hidden" name="id_cliente" value="<?php echo $cliente->id ?>">
                                <input type="hidden" name="id_pedido" value="<?php echo $pedido->id ?>">
                                <div class="form-group">
                                    <label for="tamanho-<?php echo $pedido->id; ?>">Tamanho:</label>
                                    <input type="text" id="tamanho-<?php echo $pedido->id; ?>" name="tamanho" class="form-control" value="<?php echo $pedido->tamanho ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="data_para_entrega-<?php echo $pedido->id; ?>">Data para entrega:</label>
                                    <input type="date" id="data_para_entrega-<?php echo $pedido->id; ?>" name="data_para_entrega" class="form-control" value="<?php echo $pedido->data_para_entrega ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="observacoes-<?php echo $pedido->id; ?>">Observações:</label>
                                    <input type="text" id="observacoes-<?php echo $pedido->id; ?>" name="observacoes" class="form-control" value="<?php echo $pedido->observacoes ?>">
                                </div>
                                <div class="form-group">
                                    <label for="preco-<?php echo $pedido->id; ?>">Preço:</label>
                                    <input type="number" id="preco-<?php echo $pedido->id; ?>" step="0.01" name="preco" class="form-control" value="<?php echo $pedido->preco ?>" required>
                                </div>
                                <input type="submit" value="Editar pedido" class="btn btn-primary">
                            </form>
                        <?php endforeach; ?>
                        </div>
                        
                    </section>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include './footer.php'?>

</body>
</html>
