<table id="table" class="table table-bordered table-hover table-sm small">
    <thead>
        <tr>
            <th>Código</th>
            <th>Descripción</th>
            <th>Unid</th>
            <th>Stock</th>
            <th>Costo Promedio</th>
            <th>Importe</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($listado as $item) : ?>
            <tr>
                <td><?php echo $item['idart'] ?></td>
                <td><?php echo $item['descri'] ?></td>
                <td><?php echo $item['unid'] ?></td>
                <td><?php echo $item['stock'] ?></td>
                <td><?php echo $item['costo'] ?></td>
                <td><?php echo $item['importe'] ?></td>
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