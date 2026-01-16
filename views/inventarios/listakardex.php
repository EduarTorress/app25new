<table id="table" class="table table-bordered table-hover table-sm small">
    <thead>
        <tr>
            <th>Fecha</th>
            <th>Documento</th>
            <th>Detalle</th>
            <th class="text-end">Ingresos (Base)</th>
            <th class="text-end">Salidas (Base)</th>
            <th class="text-end">Mov. Stock (Base)</th>
            <th class="text-center">Moneda</th>
            <th class="text-end">Precio Pres.</th>
            <th class="text-center">Usuario</th>
            <th class="text-center">Fecha/Hora</th>
            <th class="text-center">Autorizo</th>
            <th class="text-center">Tipo</th>
            <th class="text-center">Presen.</th>
            <th class="text-center">Cant. Pres.</th>
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
                <td><?php echo $item['kar_unid'] ?></td>
                <td><?php echo $item['cantpres'] ?></td>
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