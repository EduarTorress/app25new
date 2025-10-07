<table id="tblVentasxProducto" class="table table-bordered table-hover table table-sm small">
    <thead>
        <tr class="text-center">
            <th>CÓDIGO</th>
            <th>PRODUCTO</th>
            <th>MARCA</th>
            <th>UNID</th>
            <!-- <th>MULT.</th>
            <th>SUCR.</th>
            <th>MACHIN.</th>
            <th>DESME.</th>
            <th>SUCURS.</th> -->
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
                <!-- <td class="text-center"><?php echo $item['MULTITOOLS'] ?></td>
                <td class="text-center"><?php echo $item['SUCRE'] ?></td>
                <td class="text-center"><?php echo $item['MACHINERY'] ?></td>
                <td class="text-center"><?php echo $item['DESMEDRO'] ?></td>
                <td class="text-center"><?php echo $item['SUCURSAL'] ?></td> -->
                <td><?php echo $item['GRUPO'] ?></td>
                <td><?php echo $item['LINEA'] ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<script>
    reportetablebt("#tblVentasxProducto");
</script>