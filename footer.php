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
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
<script src="vendor/datatables/jquery.dataTables.min.js"></script>
<script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>