<table id="table" class="table table-bordered table-hover table-sm small">
    <thead>
        <tr>
            <th>Código</th>
            <th data-sortable="true">Descripción</th>
            <th data-sortable="true">Stock</th>
            <th data-sortable="true">Costo Promedio</th>
            <th data-sortable="true">Importe</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($listado as $item) : ?>
            <tr>
                <td><?php echo $item['idart'] ?></td>
                <td><?php echo $item['descri'] ?></td>
                <td><?php echo $item['stock'] ?></td>
                <td><?php echo $item['costo'] ?></td>
                <td><?php echo $item['importe'] ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<script>
    reportetablebt("#table");
</script>