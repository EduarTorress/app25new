<table id="table" class="table table-bordered table-hover table-sm small" data-page-size="14">
    <thead>
        <tr>
            <th class="text-center">Código</th>
            <th data-sortable="true">Descripción</th>
            <th data-sortable="true" data-footer-formatter="formatTotal" class="text-end">Stock</th>
            <th data-sortable="true" class="text-end">Costo</th>
            <th data-sortable="true" data-footer-formatter="formatTotal" class="text-end">Importe</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($listado as $item) : ?>
            <?php $importe = 0; ?>
            <tr>
                <td><?php echo $item['idart'] ?></td>
                <td><?php echo $item['descri'] ?></td>
                <td><?php echo number_format($item['stock'], 2, '.', ''); ?></td>
                <?php if ($tipocosto == 'P'): ?>
                    <td><?php echo number_format($item['costo'], 2, '.', '') ?></td>
                    <?php $importe = $item['costo'] * $item['stock']; ?>
                <?php else: ?>
                    <td><?php echo number_format($item['prec'], 2, '.', '') ?></td>
                    <?php $importe = $item['prec'] * $item['stock']; ?>
                <?php endif; ?>
                <td><?php echo number_format($importe, 2, '.', '') ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<script>
    reportetablebt("#table");
    // $("#table").attr("data-page-size", '15');
</script>