<table id="tblVentasxProducto" class="table table-bordered table-hover table table-sm small">
    <thead>
        <tr>
            <th class="text-center">#</th>
            <th data-sortable="true" class="">Producto</th>
            <th class="text-center">Barras</th>
            <th class="text-end" data-sortable="true" data-footer-formatter="formatTotal">Cantidad Vendida (Base)</th>
            <th class="text-end" data-sortable="true" data-footer-formatter="formatTotal">Total Vendido</th>
            <th class="text-end" data-sortable="true" data-footer-formatter="formatTotal">Ganancia</th>
            <th class="text-center">Movimientos</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($listado as $item) : ?>
            <tr>
                <td><?php echo $item['CODIGO'] ?></td>
                <td><b><?php echo $item['PRODUCTO'] ?></b></td>
                <td><?php echo $item['prod_cod1'] ?></td>
                <td><?php echo $item['cantidadvendida'] ?></td>
                <td><?php echo $item['importevendido'] ?></td>
                <td><?php echo round($item['ganancia'], 3) ?></td>
                <td><a target="_blank" rel="noopener noreferrer" href="<?php echo "/inventarios/kardex?coda=" . $item['CODIGO'] . "&producto=" . $item['PRODUCTO'] . "&alma=" . $nalma . "&fecha=" . $fechaf . "&fechainicial=" . $fechai ?>"><?php echo 'Kardex' ?></a></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<script>
    reportetablebt("#tblVentasxProducto");
    graficarproductosmasvendidos()
    graficarproductosmenosvendidos();

    function graficarproductosmasvendidos() {
        const ctx = document.getElementById('myChart');
        myChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: <?php echo json_encode(array_column($listagrafico1, 'PRODUCTO')); ?>,
                datasets: [{
                    label: 'Cantidad Vendida',
                    data: <?php echo json_encode(array_column($listagrafico1, 'cantidadvendida')); ?>,
                    backgroundColor: <?php echo json_encode(array_column($listagrafico1, 'color')); ?>,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    title: {
                        display: true,
                        text: 'Los 20 productos más vendidos',
                        padding: {
                            top: 10,
                            bottom: 30
                        }
                    }
                }
            }
        });
        myChart.resize(500, 500);
    }

    function graficarproductosmenosvendidos() {
        const ctx = document.getElementById('myChart2');
        myChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: <?php echo json_encode(array_column($listagrafico2, 'PRODUCTO')); ?>,
                datasets: [{
                    label: 'Cantidad Vendida',
                    data: <?php echo json_encode(array_column($listagrafico2, 'cantidadvendida')); ?>,
                    backgroundColor: <?php echo json_encode(array_column($listagrafico2, 'color')); ?>,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    title: {
                        display: true,
                        text: 'Los 20 productos menos vendidos',
                        padding: {
                            top: 10,
                            bottom: 30
                        }
                    }
                }
            }
        });
        myChart.resize(500, 500);
    }
</script>