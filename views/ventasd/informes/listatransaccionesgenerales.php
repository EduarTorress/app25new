<div class="card card-success card-outline table-responsive">
    <div class="card-header">
        <strong> Movimientos por Ventas / Compras</strong>
    </div>
    <div class="card-body">
        <table id="table" class="table table-bordered table-hover table-sm small">
            <thead>
                <tr>
                    <th style="width:8%;" class="text-center">Tipo</th>
                    <th style="width:7%;" class="text-end" data-footer-formatter="formatTotal">Efectivo</th>
                    <th style="width:7%;" class="text-end" data-footer-formatter="formatTotal">Crédito</th>
                    <th style="width:7%;" class="text-end" data-footer-formatter="formatTotal">Deposito</th>
                    <th style="width:7%;" class="text-end" data-footer-formatter="formatTotal">Yape</th>
                    <th style="width:7%;" class="text-end" data-footer-formatter="formatTotal">Plin</th>
                    <th style="width:7%;" class="text-end" data-footer-formatter="formatTotal">Tarjeta</th>
                    <th style="width:7%;" class="text-end" data-footer-formatter="formatTotal">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($listatransacciones as $item) : ?>
                    <tr>
                        <td><?php echo $item['tipo'] ?></td>
                        <td><?php echo $item['efectivo'] ?></td>
                        <td><?php echo $item['credito'] ?></td>
                        <td><?php echo $item['deposito'] ?></td>
                        <td><?php echo $item['yape'] ?></td>
                        <td><?php echo $item['plin'] ?></td>
                        <td><?php echo $item['tarjeta'] ?></td>
                        <td><?php echo $item['total'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<div class="card card-success card-outline table-responsive">
    <div class="card-header">
        <strong> Cuentas por Cobrar y Cuentas por Pagar</strong>
    </div>
    <div class="card-body">
        <table id="tablectasxpagarxcobrar" class="table table-bordered table-hover table-sm small">
            <thead>
                <tr>
                    <th class="text-center">Tipo</th>
                    <th class="text-end" data-footer-formatter="formatTotal">Crédito</th>
                    <th class="text-end" data-footer-formatter="formatTotal">Cancelado</th>
                    <th class="text-end" data-footer-formatter="formatTotal">Diferencia</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($listacuentas as $item) : ?>
                    <tr>
                        <td><?php echo $item['tipo'] ?></td>
                        <td><?php echo $item['impo'] ?></td>
                        <td><?php echo $item['acta'] ?></td>
                        <td><?php echo $item['diferencia'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<script>
    reportetablebt("#table");
    reportetablebt("#tablectasxpagarxcobrar");
</script>