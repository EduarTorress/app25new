<table id="table" class="table table-bordered table-hover table-sm small">
    <thead>
        <tr>
            <th class="text-center">Código</th>
            <th data-sortable="true">Descripción</th>
            <th data-sortable="true" data-footer-formatter="formatTotal" class="text-end">Stock</th>
            <?php if ($tipocosto == 'P'): ?>
                <th data-sortable="true" class="text-end">Promedio</th>
            <?php else: ?>
                <th data-sortable="true" class="text-end">Ultima Compra</th>
            <?php endif; ?>
            <th data-sortable="true" class="text-end">Importe</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($listado as $item) : ?>
            <tr>
                <td><?php echo $item['idart'] ?></td>
                <td><?php echo $item['descri'] ?></td>
                <td><?php echo $item['stock'] ?></td>
                <?php if ($tipocosto == 'P'): ?>
                    <td><?php echo $item['costo'] ?></td>
                <?php else: ?>
                    <td><?php echo $item['prec'] ?></td>
                <?php endif; ?>
                <td><?php echo $item['importe'] ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<script>
    reportetablebt("#table");
</script>