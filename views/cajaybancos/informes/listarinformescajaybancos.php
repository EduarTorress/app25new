<table id="tablainforme" class="table table-bordered table-hover table-sm small">
    <thead>
        <tr>
            <th>Nro. Correlativo</th>
            <th>Fecha de Operacion</th>
            <th>Medio de Pago</th>
            <th>Descripción de Operacion</th>
            <th>Razon Social</th>
            <th>Numero de Transaccion Bancaria</th>
            <th>Código</th>
            <th>Denominación</th>
            <th>Deudor</th>
            <th>Acreedor</th>
            <th>Saldo</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($listarsaldoinicial as $item) : ?>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td>Saldo Inicial</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td><?php echo $item['si'] ?></td>
                <td></td>
               <td><?php echo $item['si'] ?></td>
            </tr>
        <?php endforeach; ?>
        <?php foreach ($listado as $item) : ?>
            <tr>
                <td><?php echo $item['cban_ndoc'] ?></td>
                <td><?php echo $item['cban_fech'] ?></td>
                <td><?php echo $item['pago_codi'] ?></td>
                <td><?php echo $item['pago_deta'] ?></td>
                <td><?php echo $item['razon'] ?></td>
                <td><?php echo $item['cban_nume'] ?></td>
                <td><?php echo $item['ncta'] ?></td>
                <td><?php echo $item['nomb'] ?></td>
                <td><?php echo $item['cban_debe'] ?></td>
                <td><?php echo $item['cban_haber'] ?></td>
                <td><?php echo $item['cban_debe'] ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<script>
    reportetablebt("#tablainforme");
</script>