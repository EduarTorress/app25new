<br>
<div class="table-responsive">
    <table id="tablaeliminados" class="table table-bordered border-dark table-sm small">
        <thead>
            <tr>
                <th>Código</th>
                <th>Producto</th>
                <th>Movimiento</th>
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
                    <td><b><?php echo $item['nomb'] ?></b></td>
                    <td><b><?php echo $item['prod_fope'] ?></b></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
    reportetablebt("#tablaeliminados");
</script>