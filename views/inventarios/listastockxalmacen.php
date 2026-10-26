<table id="table" class="table table-bordered table-hover table-sm small">
    <thead>
        <tr>
            <th data-sortable="true">Producto</th>
            <th data-sortable="true">Cod. Barras</th>
            <th data-sortable="true">Cod. Prov</th>
            <th class="text-end" data-footer-formatter="formatTotal" data-sortable="true">Cantidad Existente</th>
            <th class="text-center">Ver</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $fecha = date('Y-m-d');
        //$nalma = $_SESSION['idalmacen'];
        ?>
        <?php foreach ($listado as $item) :
            $alma = (float) $item['alma'];
            if ($cmbfiltrostock === 'N' && $alma > 0) {
                continue;
            }
            if ($cmbfiltrostock === 'P' && $alma <= 0) {
                continue;
            } ?>
            <tr>
                <td><?php echo $item['descri'] ?></td>
                <td><?php echo $item['prod_cod1'] ?></td>
                <td><?php echo $item['coda1'] ?></td>
                <td class="text-end"><?php echo $item['alma'] ?></td>
                <td><a target="_blank" rel="noopener noreferrer" href="<?php echo "/inventarios/kardex?coda=" . $item['idart'] . "&producto=" . str_replace('"', "'", $item['descri']) . "&alma=" . $nalma . "&fecha=" . $fecha ?>"><?php echo 'Kardex' ?></a></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<script>
    reportetablebt("#table");
</script>