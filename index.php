<?php

    include './conexao.php';

    // Lista todos os alunos
    // comando de seleção
    

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
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
        <select name="id_cliente" id="nome_cliente">
            <?php
                $sql = "SELECT * FROM clientes";
                // execução do comando select
                $consulta = $conexao->query($sql);
                while ($linhas = $consulta->fetch(PDO::FETCH_OBJ)) {
            ?>
                <option value="<?php  echo $linhas->id ?>"><?php  echo $linhas->nome ?></option>
            <?php
                }
            ?>
        </select>
        <input type="submit" value="Cadastrar">
    </form>
    <form action="cadastrar.php" method="post">
        <span>Nome: </span><input type="text" name="nome">
        <br><br>
        <span>Numero: </span>
        <input type="tel" pattern="\(\d{2}\) \d{5}-\d{4}" name="numero" placeholder="(XX) XXXXX-XXXX">
        <br><br>
        <input type="submit" value="Cadastrar">
    </form>
    <table width="100%" border="1">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Numero</th>
                <th>Ações</th>
            </tr>
        </thead>
        <?php
            $sql = "SELECT * FROM clientes";
            // execução do comando select
            $consulta_2 = $conexao->query($sql);
            while ($linha = $consulta_2->fetch(PDO::FETCH_OBJ)) {
        ?>
            <tr>
                <td><?php  echo $linha->nome ?></td>
                <td><?php  echo $linha->numero ?></td>
                <td>
                    <a href="cliente.php?id=<?php echo $linha->id ?>">Editar</a>
                    <a href="excluir.php?id=<?php echo $linha->id ?>">Excluir</a>
                </td>
            </tr>
        <?php
            }
        ?>
    </table>
    <table width="100%" border="1">
        <thead>
            <tr>
                <th>Tamanho</th>
                <th>Data</th>
                <th>Observações</th>
                <th>Ações</th>
            </tr>
        </thead>
        <?php
            $sql = "SELECT * FROM pedidos";
            // execução do comando select
            $consulta_2 = $conexao->query($sql);
            while ($linha = $consulta_2->fetch(PDO::FETCH_OBJ)) {
        ?>
            <tr>
                <td><?php  echo $linha->tamanho ?></td>
                <td><?php  echo $linha->data_para_entrega ?></td>
                <td><?php  echo $linha->observacoes ?></td>
                <td>
                    <a href="excluir_pedido.php?id=<?php echo $linha->id ?>">Excluir</a>
                </td>
            </tr>
        <?php
            }
        ?>
    </table>
</body>
</html>