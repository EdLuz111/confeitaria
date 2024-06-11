<?php
include './conexao.php';

// Calcular o ganho mensal
$mes_atual = date('Y-m');
$sql_ganho_mensal = "SELECT SUM(preco) AS ganho_mensal FROM pedidos WHERE data_para_entrega LIKE '$mes_atual%'";
$stmt_ganho_mensal = $conexao->prepare($sql_ganho_mensal);
$stmt_ganho_mensal->execute();
$ganho_mensal = $stmt_ganho_mensal->fetch(PDO::FETCH_ASSOC);

// Calcular os ganhos futuros
$data_atual = date('Y-m-d');
$sql_ganhos_futuros = "SELECT SUM(preco) AS ganhos_futuros FROM pedidos WHERE data_para_entrega > '$data_atual'";
$stmt_ganhos_futuros = $conexao->prepare($sql_ganhos_futuros);
$stmt_ganhos_futuros->execute();
$ganhos_futuros = $stmt_ganhos_futuros->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teste</title>
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
    <style>
        <?php include './style.css'?>
    </style>
</head>
<body>

<div class="sidebar">
    <h2>Teste</h2>
    <a href="#painel">Painel</a>
    <a href="#calendario">Calendário</a>
    <a href="#clientes">Clientes</a>
    <a href="#pedidos">Pedidos</a>
</div>

<div class="content">
    <section id="painel">
        <h2>Painel</h2>
        <p>Bem-vindo ao Painel Administrativo!</p>
        <!-- Exibir ganho mensal e ganhos futuros -->
        <div>
            <h3>Ganhos Mensais</h3>
            <p>O ganho total deste mês é: R$ <?php echo $ganho_mensal['ganho_mensal']; ?></p>
        </div>
        <div>
            <h3>Ganhos Futuros</h3>
            <p>O total de ganhos futuros é: R$ <?php echo $ganhos_futuros['ganhos_futuros']; ?></p>
        </div>
    </section>

    <section id="calendario">
        <h2>Calendário</h2>
        <div id='calendar'></div>
    </section>

    <section id="clientes">
        <h2>Clientes</h2>
        <form action="cadastrar.php" method="post">
            <span>Nome: </span><input type="text" name="nome">
            <br><br>
            <span>Número: </span>
            <input type="tel" pattern="\(\d{2}\) \d{5}-\d{4}" name="numero" placeholder="(XX) XXXXX-XXXX">
            <br><br>
            <input type="submit" value="Cadastrar">
        </form>
        <table width="100%" border="1">
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

    <section id="pedidos">
        <h2>Pedidos</h2>
        <form action="cadastro_pedidos.php" method="post">
            <span>Tamanho: </span>
            <input type="text" name="tamanho">
            <br><br>
            <span>Data para entrega:</span>
            <input type="date" name="data_para_entrega">
            <br><br>
            <span>Observações:</span>
            <input type="text" name="observacoes">
            <br><br>
            <span>Preço:</span>
            <input type="text" name="preco">
            <br><br>
            <select name="id_cliente" id="nome_cliente">
                <?php
                $sql = "SELECT * FROM clientes";
                $consulta = $conexao->query($sql);
                while ($linhas = $consulta->fetch(PDO::FETCH_OBJ)) {
                ?>
                <option value="<?php echo $linhas->id ?>"><?php echo $linhas->nome ?></option>
                <?php
                }
                ?>
            </select>
            <input type="submit" value="Cadastrar">
        </form>
        <table width="100%" border="1">
            <thead>
                <tr>
                    <th>Tamanho</th>
                    <th>Data</th>
                    <th>Preço</th>
                    <th>Observações</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT pedidos.*, clientes.nome as nome_cliente FROM pedidos JOIN clientes ON pedidos.id_cliente = clientes.id";
                $consulta_2 = $conexao->query($sql);
                while ($linha = $consulta_2->fetch(PDO::FETCH_OBJ)) {
                ?>
                <tr>
                    <td><?php echo $linha->tamanho ?></td>
                    <td><?php echo $linha->data_para_entrega ?></td>
                    <td><?php echo $linha->preco ?></td>
                    <td><?php echo $linha->observacoes ?></td>
                    <td>
                        <a href="excluir_pedido.php?id=<?php echo $linha->id ?>">Excluir</a>
                    </td>
                </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </section>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            events: [
                <?php
                $sql = "SELECT pedidos.*, clientes.nome as nome_cliente FROM pedidos JOIN clientes ON pedidos.id_cliente = clientes.id";
                $consulta_2 = $conexao->query($sql);
                while ($linha = $consulta_2->fetch(PDO::FETCH_OBJ)) {
                    echo "{
                        title: '{$linha->nome_cliente}',
                        start: '{$linha->data_para_entrega}'
                    },";
                }
                ?>
            ]
        });
        calendar.render();
    });
</script>

</body>
</html>
