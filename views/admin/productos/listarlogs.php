
<br><div class="table-responsive">
    <table id="tabla" class="table table-bordered border-dark table-sm small">
        <thead>
            <tr>
                <th>Código</th>
                <th>Descripción Antigua</th>
                <th>Descripción Nueva</th>
                <th>Código de Barras Antiguo</th>
                <th>Código de Barras Nuevo</th>
                <th>Código de Proveedor Antiguo</th>
                <th>Código de Proveedor Nuevo</th>
                <th>Usuario</th>
                <th>Fecha</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($listado as $item) : ?>
                <tr>
                    <td><?php echo $item['prod_idar'] ?></td>
                    <td><?php echo $item['prod_descriold'] ?></td>
                    <td><?php echo $item['prod_descrinew'] ?></td>
                    <td><?php echo $item['prod_barrasold'] ?></td>
                    <td><?php echo $item['prod_barrasnew'] ?></td>
                    <td><?php echo $item['prod_provold'] ?></td>
                    <td><?php echo $item['prod_provnew'] ?></td>
                    <td><b><?php echo $item['nomb'] ?></b></td>
                    <td><b><?php echo $item['prod_fope'] ?></b></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>