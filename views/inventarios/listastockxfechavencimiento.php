<table id="table" class="table table-bordered table-hover table-sm small">
    <thead>
        <tr>
            <th data-sortable="true">Producto</th>
            <th class="text-center"  data-sortable="true">Unidad</th>
            <th class="text-center"  data-sortable="true">Linea</th>
            <th class="text-center"  data-sortable="true">Lote</th>
            <th class="text-center"  data-sortable="true">Fecha Vencimiento</th>
            <th class="text-end" data-footer-formatter="formatTotal" data-sortable="true">Stock</th>
            <!-- <th class="text-center" data-sortable="true">Ver</th> -->
        </tr>
    </thead>
    <tbody>
        <?php
        // $fecha = date('Y-m-d');
        // $nalma=$_SESSION['idalmacen'];
        ?>
        <?php foreach ($listado as $item) : ?>
            <tr>
                <td><?php echo $item['descri'] ?></td>
                <td><?php echo $item['unid'] ?></td>
                <td><?php echo $item['linea'] ?></td>
                <td><?php echo $item['kar_lote'] ?></td>
                <td><?php echo $item['kar_fvto'] ?></td>
                <td><?php echo $item['cant'] ?></td>
                <!-- <td><a target="_blank" rel="noopener noreferrer" href="<?php echo "/inventarios/kardex?coda=" . $item['idart'] . "&producto=" . $item['descri'] . "&alma=" . $nalma . "&fecha=" . $fecha ?>"><?php echo 'Kardex' ?></a></td> -->
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<script>
    reportetablebt("#table");
</script>