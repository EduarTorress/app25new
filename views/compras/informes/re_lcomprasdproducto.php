<table id="tblVentasxProducto" class="table table-bordered table-hover table table-sm small">
    <thead>
        <tr class="text-center">
            <th>CÓDIGO</th>
            <th>PRODUCTO</th>
            <th>MARCA</th>
            <th>UNID</th>
            <th>GRUP.</th>
            <th>LINE.</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($listado as $item) : ?>
            <tr>
                <td><?php echo $item['CODIGO'] ?></td>
                <td><?php echo $item['PRODUCTO'] ?></td>
                <td><?php echo $item['MARCA'] ?></td>
                <td><?php echo $item['UNIDAD'] ?></td>
                <td><?php echo $item['GRUPO'] ?></td>
                <td><?php echo $item['LINEA'] ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<script>
    // reporteTablaLyG('#tblVentasxProducto');
    reportetablebt("#tblVentasxProducto");
</script>