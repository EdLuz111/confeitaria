<?php
include './conexao.php';
session_start(); // Inicie a sessão no início do arquivo

// Funções para calcular métricas
function calcularGanhoMensal($conexao) {
    $mes_atual = date('Y-m');
    $sql = "SELECT SUM(preco) AS ganho_mensal FROM pedidos WHERE data_para_entrega LIKE '$mes_atual%'";
    $stmt = $conexao->prepare($sql);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC)['ganho_mensal'];
}

function calcularGanhosFuturos($conexao) {
    $data_atual = date('Y-m-d');
    $sql = "SELECT SUM(preco) AS ganhos_futuros FROM pedidos WHERE data_para_entrega > '$data_atual'";
    $stmt = $conexao->prepare($sql);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC)['ganhos_futuros'];
}

function contarPedidosPendentes($conexao) {
    $data_atual = date('Y-m-d');
    $sql = "SELECT COUNT(*) AS pedidos_pendentes FROM pedidos WHERE data_para_entrega > '$data_atual' AND status != 'entregue'";
    $stmt = $conexao->prepare($sql);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC)['pedidos_pendentes'];
}

function obterGanhosMensais($conexao) {
    $ganhosMensais = [];
    for ($i = 1; $i <= 12; $i++) {
        $mes = str_pad($i, 2, '0', STR_PAD_LEFT);
        $ano = date('Y');
        $sql = "SELECT SUM(preco) AS ganho_mensal FROM pedidos WHERE DATE_FORMAT(data_para_entrega, '%Y-%m') = '$ano-$mes'";
        $stmt = $conexao->prepare($sql);
        $stmt->execute();
        $ganho_mensal = $stmt->fetch(PDO::FETCH_ASSOC)['ganho_mensal'] ?? 0;
        $ganhosMensais[] = $ganho_mensal;
    }
    return $ganhosMensais;
}

function calcularGanhosTotais($conexao) {
    $sql = "SELECT SUM(preco) AS ganhos_totais FROM pedidos";
    $stmt = $conexao->prepare($sql);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC)['ganhos_totais'];
}

$ganhosMensais = obterGanhosMensais($conexao);

$section = isset($_GET['section']) ? $_GET['section'] : 'dashboard';

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
    <title>Painel de Controle</title>
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@200..700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        <?php include './style.css' ?>
    </style>
</head>
<body>

<div class="d-flex">
    <aside class="sidebar">
        <nav class="nav flex-column">
            <h2 class="nav-link disabled">Confeitaria</h2>
            <a class="nav-link" href="index.php?section=dashboard">
                <i class="bi bi-speedometer2"></i>Painel
            </a>
            <a class="nav-link" href="index.php?section=calendario">
                <i class="bi bi-calendar"></i>Calendário
            </a>
            <a class="nav-link" href="index.php?section=clientes">
                <i class="bi bi-file-earmark-person-fill"></i>Clientes
            </a>
            <a class="nav-link" href="index.php?section=pedidos">
                <i class="bi bi-check2-square"></i>Pedidos
            </a>
        </nav>
    </aside>

    <main class="flex-grow-1">
        <div class="content">
            <?php if (isset($flash_message)): ?>
                <div class="flash-message <?= $flash_type ?>">
                    <?= $flash_message ?>
                </div>
            <?php endif; ?>

            <?php
            if ($section == 'dashboard') {
                $ganho_mensal = calcularGanhoMensal($conexao);
                $ganhos_futuros = calcularGanhosFuturos($conexao);
                $pedidos_pendentes = contarPedidosPendentes($conexao);
                $ganhos_totais = calcularGanhosTotais($conexao);
                ?>
                <section id="dashboard">
                    <h2>Dashboard</h2>
                    <div class="card">
                        <h3>Ganhos Mensais</h3>
                        <p>R$ <?php echo $ganho_mensal; ?></p>
                    </div>
                    <div class="card">
                        <h3>Ganhos Futuros</h3>
                        <p>R$ <?php echo $ganhos_futuros; ?></p>
                    </div>
                    <div class="card">
                        <h3>Pedidos Pendentes</h3>
                        <p><?php echo $pedidos_pendentes; ?></p>
                    </div>
                    <div class="card">
                        <h3>Ganhos Totais</h3>
                        <p>R$ <?php echo $ganhos_totais; ?></p>
                    </div>
                    <div class="card">
                        <h3>Visão Geral dos Ganhos</h3>
                        <canvas id="earningsChart"></canvas>
                    </div>
                </section>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        var ctx = document.getElementById('earningsChart').getContext('2d');
                        var earningsChart = new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                                datasets: [{
                                    label: 'Ganhos',
                                    data: <?php echo json_encode($ganhosMensais); ?>,
                                    borderColor: 'rgba(75, 192, 192, 1)',
                                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                    fill: true,
                                    tension: 0.1
                                }]
                            },
                            options: {
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        ticks: {
                                            callback: function(value) {
                                                return 'R$ ' + value;
                                            }
                                        }
                                    }
                                }
                            }
                        });
                    });
                </script>
                <?php
            } elseif ($section == 'calendario') {
                ?>
                <section id="calendario">
                    <h2>Calendário</h2>
                    <div id='calendar'></div>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            var calendarEl = document.getElementById('calendar');
                            var calendar = new FullCalendar.Calendar(calendarEl, {
                                initialView: 'dayGridMonth',
                                events: {
                                    url: 'eventos.php',
                                    method: 'POST',
                                    extraParams: {
                                        custom_param: 'calendario'
                                    }
                                }
                            });
                            calendar.render();
                        });
                    </script>
                </section>
                <?php
            } elseif ($section == 'clientes') {
                ?>
                <section id="clientes">
                <h2>Cadastro</h2>
                    <h3>Cadastrar Cliente</h3>
                    <form action="cadastrar.php" method="post">
                        <label>Nome: </label><input type="text" name="nome" required>
                        <br><br>
                        <label>Número: </label>
                        <input type="tel" pattern="\(\d{2}\) \d{5}-\d{4}" name="numero" placeholder="(XX) XXXXX-XXXX" required>
                        <br><br>
                        <input type="submit" value="Cadastrar Cliente">
                    </form>
                    <h2>Clientes</h2>
                    <table>
                        <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Número</th>
                            <th>Ações</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $sql = "SELECT * FROM clientes";
                        $consulta_2 = $conexao->query($sql);
                        while ($linha = $consulta_2->fetch(PDO::FETCH_OBJ)) {
                            ?>
                            <tr>
                                <td><?php echo $linha->nome ?></td>
                                <td><?php echo $linha->numero ?></td>
                                <td>
                                    <a href="cliente.php?id=<?php echo $linha->id ?>">Editar</a>
                                    <a href="excluir.php?id=<?php echo $linha->id ?>">Excluir</a>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                        </tbody>
                    </table>
                </section>
                <?php
            } elseif ($section == 'pedidos') {
                ?>
                <section id="pedidos">
                    <h2>Pedidos</h2>
                    <table>
                        <thead>
                        <tr>
                            <th>Tamanho</th>
                            <th>Data</th>
                            <th>Preço</th>
                            <th>Observações</th>
                            <th>Cliente</th>
                            <th>Ações</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $sql = "SELECT pedidos.*, clientes.nome as nome_cliente 
                                FROM pedidos 
                                JOIN clientes ON pedidos.id_cliente = clientes.id";
                        $stmt = $conexao->query($sql);
                        while ($linha = $stmt->fetch(PDO::FETCH_OBJ)) {
                            ?>
                            <tr>
                                <td><?php echo $linha->tamanho ?></td>
                                <td><?php echo $linha->data_para_entrega ?></td>
                                <td><?php echo $linha->preco ?></td>
                                <td><?php echo $linha->observacoes ?></td>
                                <td><?php echo $linha->nome_cliente ?></td>
                                <td>
                                    <a href="excluir_pedido.php?id=<?php echo $linha->id ?>">Excluir</a>
                                    <a href="marcar_entregue.php?id=<?php echo $linha->id ?>">Entregue</a>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                        </tbody>
                    </table>
                </section>
                <?php
            }
            ?>
        </div>
    </main>
</div>

<?php include './footer.php'?>

</body>
</html>
