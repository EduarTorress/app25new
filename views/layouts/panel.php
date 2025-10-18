<?php
$this->setLayout('layouts/admin');
?>
<?php
$this->startSection('contenido');
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
<div class="content-wrapper" id="container">
    <div class="content">
        <br>
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-2 col-sm-6 mb-xl-0 mb-4">
                    <div class="card mb-3" style="color: #03326a;">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-8">
                                    <div class="numbers">
                                        <h4 class="text-sm mb-0 text-capitalize font-weight-bold">Total Ventas</h4>
                                        <h5 class="font-weight-bolder mb-0">
                                            <?php echo $totalventas; ?>
                                            <span class="text-success text-sm font-weight-bolder"></span>
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                        <i class="ni ni-world text-lg opacity-10" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-sm-6 mb-xl-0 mb-4">
                    <div class="card">
                        <div class="card-body p-3" style="color: #03326a;">
                            <div class="row">
                                <div class="col-8">
                                    <div class="numbers">
                                        <h4 class="text-sm mb-0 text-capitalize font-weight-bold">Monto Ventas S/</h4>
                                        <h5 class="font-weight-bolder mb-0">
                                            <?php echo $montoventassoles; ?>
                                            <span class="text-success text-sm font-weight-bolder"></span>
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                        <i class="ni ni-world text-lg opacity-10" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-sm-6 mb-xl-0 mb-4">
                    <div class="card">
                        <div class="card-body p-3" style="color: #03326a;">
                            <div class="row">
                                <div class="col-8">
                                    <div class="numbers">
                                        <h4 class="text-sm mb-0 text-capitalize font-weight-bold">Monto Ventas $</h4>
                                        <h5 class="font-weight-bolder mb-0">
                                            <?php echo $montoventasdolares; ?>
                                            <span class="text-success text-sm font-weight-bolder"></span>
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                        <i class="ni ni-world text-lg opacity-10" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-sm-6 mb-xl-0 mb-4">
                    <div class="card">
                        <div class="card-body p-3" style="color: #03326a;">
                            <div class="row">
                                <div class="col-8">
                                    <div class="numbers">
                                        <h4 class="text-sm mb-0 text-capitalize font-weight-bold">Total Pedidos</h4>
                                        <h5 class="font-weight-bolder mb-0">
                                            <?php echo $totalpedidos; ?>
                                            <span class="text-success text-sm font-weight-bolder"></span>
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                        <i class="ni ni-world text-lg opacity-10" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-sm-6 mb-xl-0 mb-4">
                    <div class="card">
                        <div class="card-body p-3" style="color: #03326a;">
                            <div class="row">
                                <div class="col-8">
                                    <div class="numbers">
                                        <h4 class="text-sm mb-0 text-capitalize font-weight-bold">Total Clientes</h4>
                                        <h5 class="font-weight-bolder mb-0">
                                            <?php echo $totalclientes; ?>
                                            <span class="text-success text-sm font-weight-bolder"></span>
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                        <i class="ni ni-world text-lg opacity-10" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-sm-6 mb-xl-0 mb-4">
                    <div class="card">
                        <div class="card-body p-3" style="color: #03326a;">
                            <div class="row">
                                <div class="col-8">
                                    <div class="numbers">
                                        <h4 class="text-sm mb-0 text-capitalize font-weight-bold">Total Productos</h4>
                                        <h5 class="font-weight-bolder mb-0">
                                            <?php echo $totalproductos; ?>
                                            <span class="text-success text-sm font-weight-bolder"></span>
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                        <i class="ni ni-world text-lg opacity-10" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="card border-primary">
                        <div class="card-header">Ventas por mes</div>
                        <div class="card-body text-primary">
                            <canvas id="circular" style="width:100%;max-width:600px"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card border-primary ">
                        <div class="card-header">Monto total de ventas por año</div>
                        <div class="card-body text-primary">
                            <canvas id="barra" style="width:100%;max-width:600px;height:180px;"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card border-primary ">
                        <div class="card-header">Pedidos por mes</div>
                        <div class="card-body text-primary">
                            <canvas id="circularpedidos" style="width:50%;max-width:300px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$this->endSection('contenido');
?>

<?php
$this->startSection('javascript');
?>
<script>
    graficobarras();
    graficocircular();
    graficocircularpedidos();

    function graficocircular() {
        var xValues = [<?php echo ("'" . (empty($totalventasporano[0]['mes']) ? '' : $totalventasporano[0]['mes']) . "'" . "," . "'" . (empty($totalventasporano[1]['mes']) ? '' : $totalventasporano[1]['mes']) . "'" . "," .
                            "'" . (empty($totalventasporano[2]['mes']) ? '' : $totalventasporano[2]['mes']) . "'" . "," .  "'" . (empty($totalventasporano[3]['mes']) ? '' : $totalventasporano[3]['mes']) . "'"  . "," .
                            "'" . (empty($totalventasporano[4]['mes']) ? '' : $totalventasporano[4]['mes']) . "'" . "," .  "'" . (empty($totalventasporano[5]['mes']) ? '' : $totalventasporano[5]['mes']) . "'" . "," .
                            "'" . (empty($totalventasporano[6]['mes']) ? '' : $totalventasporano[6]['mes']) .  "'" . "," .
                            "'" . (empty($totalventasporano[7]['mes']) ? '' : $totalventasporano[7]['mes']) . "'" . "," . "'" . (empty($totalventasporano[8]['mes']) ? '' : $totalventasporano[8]['mes']) . "'" . "," .
                            "'" . (empty($totalventasporano[9]['mes']) ? '' : $totalventasporano[9]['mes']) . "'" . "," . "'" . (empty($totalventasporano[10]['mes']) ? '' : $totalventasporano[10]['mes']) . "'" . "," .
                            "'" . (empty($totalventasporano[11]['mes']) ? '' : $totalventasporano[11]['mes']) . "'")
                        ?>];
        var yValues = [<?php echo ("'" . (empty($totalventasporano[0]['total']) ? '0' : $totalventasporano[0]['total']) . "'" . "," . "'" . (empty($totalventasporano[1]['total']) ? '0' : $totalventasporano[1]['total']) . "'" . "," .
                            "'" . (empty($totalventasporano[2]['total']) ? '0' : $totalventasporano[2]['total']) . "'" . "," .  "'" . (empty($totalventasporano[3]['total']) ? '0' : $totalventasporano[3]['total']) . "'"  . "," .
                            "'" . (empty($totalventasporano[4]['total']) ? '0' : $totalventasporano[4]['total']) . "'" . "," .  "'" . (empty($totalventasporano[5]['total']) ? '0' : $totalventasporano[5]['total']) . "'" . "," .
                            "'" . (empty($totalventasporano[6]['total']) ? '0' : $totalventasporano[6]['total']) .   "'" . "," .
                            "'" . (empty($totalventasporano[7]['total']) ? '0' : $totalventasporano[7]['total']) . "'" . "," . "'" . (empty($totalventasporano[8]['total']) ? '0' : $totalventasporano[8]['total']) . "'" . "," .
                            "'" . (empty($totalventasporano[9]['total']) ? '0' : $totalventasporano[9]['total']) . "'" . "," . "'" . (empty($totalventasporano[10]['total']) ? '0' : $totalventasporano[10]['total']) . "'" . "," .
                            "'" . (empty($totalventasporano[11]['total']) ? '0' : $totalventasporano[11]['total']) . "'")
                        ?>];
        var barColors = [
            "#b91d47",
            "#00aba9",
            "#2b5797",
            "#e8c3b9",
            "#1e7145",
            "#00c71b",
            "#0045c7",
            "#ea960d",
            "#f4611d",
            "#3621a2",
            "#ae2488",
            "#54c16f"
        ];

        new Chart("circular", {
            type: "pie",
            data: {
                labels: xValues,
                datasets: [{
                    backgroundColor: barColors,
                    data: yValues
                }]
            },
            options: {
                title: {
                    display: true,
                    text: "Cantidad total de ventas"
                }
            }
        });
    }

    function graficobarras() {
        var xValues = [<?php echo ("'" . (empty($totalmontoventas[0]['ano']) ? '' : $totalmontoventas[0]['ano']) . "'" . "," . "'" . (empty($totalmontoventas[1]['ano']) ? '' : $totalmontoventas[1]['ano']) . "'" . "," .
                            "'" . (empty($totalmontoventas[2]['ano']) ? '' : $totalmontoventas[2]['ano']) . "'")
                        ?>];
        var yValues = [<?php echo ("'" . (empty($totalmontoventas[0]['total']) ? '0' : $totalmontoventas[0]['total']) . "'" . "," . "'" . (empty($totalmontoventas[1]['total']) ? '0' : $totalmontoventas[1]['total']) . "'" . "," .
                            "'" . (empty($totalmontoventas[2]['total']) ? '0' : $totalmontoventas[2]['total']) . "'")
                        ?>];
        var barColors = ["red", "green", "blue"];

        new Chart("barra", {
            type: "horizontalBar",
            data: {
                labels: xValues,
                datasets: [{
                    backgroundColor: barColors,
                    data: yValues
                }]
            },
            options: {
                legend: {
                    display: false
                },
                responsive: true,
                maintainAspectRatio: false,
                title: {
                    display: true,
                    text: "Monto total de ventas x año en S/"
                },
                scales: {
                    xAxes: [{
                        ticks: {
                            min: 10,
                            max: 50000
                        }
                    }]
                }
            }
        });
    }

    function graficocircularpedidos() {

        var xValues = [<?php echo ("'" . (empty($totalpedidosporano[0]['mes']) ? '' : $totalpedidosporano[0]['mes']) . "'" . "," . "'" . (empty($totalpedidosporano[1]['mes']) ? '' : $totalpedidosporano[1]['mes']) . "'" . "," .
                            "'" . (empty($totalpedidosporano[2]['mes']) ? '' : $totalpedidosporano[2]['mes']) . "'" . "," .  "'" . (empty($totalpedidosporano[3]['mes']) ? '' : $totalpedidosporano[3]['mes']) . "'"  . "," .
                            "'" . (empty($totalpedidosporano[4]['mes']) ? '' : $totalpedidosporano[4]['mes']) . "'" . "," .  "'" . (empty($totalpedidosporano[5]['mes']) ? '' : $totalpedidosporano[5]['mes']) . "'" . "," .
                            "'" . (empty($totalpedidosporano[6]['mes']) ? '' : $totalpedidosporano[6]['mes']) .  "'" . "," .
                            "'" . (empty($totalpedidosporano[7]['mes']) ? '' : $totalpedidosporano[7]['mes']) . "'" . "," . "'" . (empty($totalpedidosporano[8]['mes']) ? '' : $totalpedidosporano[8]['mes']) . "'" . "," .
                            "'" . (empty($totalpedidosporano[9]['mes']) ? '' : $totalpedidosporano[9]['mes']) . "'" . "," . "'" . (empty($totalpedidosporano[10]['mes']) ? '' : $totalpedidosporano[10]['mes']) . "'" . "," .
                            "'" . (empty($totalpedidosporano[11]['mes']) ? '' : $totalpedidosporano[11]['mes']) . "'")
                        ?>];
        var yValues = [<?php echo ("'" . (empty($totalpedidosporano[0]['total']) ? '0' : $totalpedidosporano[0]['total']) . "'" . "," . "'" . (empty($totalpedidosporano[1]['total']) ? '0' : $totalpedidosporano[1]['total']) . "'" . "," .
                            "'" . (empty($totalpedidosporano[2]['total']) ? '0' : $totalpedidosporano[2]['total']) . "'" . "," .  "'" . (empty($totalpedidosporano[3]['total']) ? '0' : $totalpedidosporano[3]['total']) . "'"  . "," .
                            "'" . (empty($totalpedidosporano[4]['total']) ? '0' : $totalpedidosporano[4]['total']) . "'" . "," .  "'" . (empty($totalpedidosporano[5]['total']) ? '0' : $totalpedidosporano[5]['total']) . "'" . "," .
                            "'" . (empty($totalpedidosporano[6]['total']) ? '0' : $totalpedidosporano[6]['total']) .   "'" . "," .
                            "'" . (empty($totalpedidosporano[7]['total']) ? '0' : $totalpedidosporano[7]['total']) . "'" . "," . "'" . (empty($totalpedidosporano[8]['total']) ? '0' : $totalpedidosporano[8]['total']) . "'" . "," .
                            "'" . (empty($totalpedidosporano[9]['total']) ? '0' : $totalpedidosporano[9]['total']) . "'" . "," . "'" . (empty($totalpedidosporano[10]['total']) ? '0' : $totalpedidosporano[10]['total']) . "'" . "," .
                            "'" . (empty($totalpedidosporano[11]['total']) ? '0' : $totalpedidosporano[11]['total']) . "'")
                        ?>];
        var barColors = [
            "#b91d47",
            "#00aba9",
            "#2b5797",
            "#e8c3b9",
            "#1e7145",
            "#00c71b",
            "#0045c7",
            "#ea960d",
            "#f4611d",
            "#3621a2",
            "#ae2488",
            "#54c16f"
        ];

        new Chart("circularpedidos", {
            type: "pie",
            data: {
                labels: xValues,
                datasets: [{
                    backgroundColor: barColors,
                    data: yValues
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                title: {
                    display: true,
                    text: "Cantidad total de pedidos"
                }
            }
        });
    }
</script>
<?php
$this->endSection('javascript');
?>