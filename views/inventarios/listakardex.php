<table id="table" class="table table-bordered table-hover table-sm small">
    <thead>
        <tr>
            <th>Fecha</th>
            <th>Documento</th>
            <th>Detalle</th>
            <th>Ingresos</th>
            <th>Salidas</th>
            <th>Stock</th>
            <th>Mone.</th>
            <th>Precio</th>
            <th>Usuario</th>
            <th>Fecha/Hora</th>
            <th>Autorizo</th>
            <th>Tipo</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($listado as $item) : ?>
            <tr>
                <td><?php echo $item['fecha'] ?></td>
                <td><?php echo $item['dcto'] ?></td>
                <td><?php echo $item['razo'] ?></td>
                <td><?php echo $item['ingr'] ?></td>
                <td><?php echo $item['egre'] ?></td>
                <td><?php echo $item['saldo'] ?></td>
                <td><?php echo $item['moneda'] ?></td>
                <td><?php echo $item['precio'] ?></td>
                <td><?php echo $item['usua'] ?></td>
                <td><?php echo $item['fusua'] ?></td>
                <td><?php echo $item['usua1'] ?></td>
                <td><?php echo $item['tipomvto'] ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<script>
    // $(document).ready(function() {
    //     reporteTabla('#table');
    // });
    reportetablebt("#table");
</script>