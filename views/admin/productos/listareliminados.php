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
                    <td><?php echo $item['idart'] ?></td>
                    <td><b><?php echo $item['producto'] ?></b></td>
                    <td><?php echo 'Elimino' ?></td>
                    <td><b><?php echo $item['usuario'] ?></b></td>
                    <td><b><?php echo $item['fechaeliminacion'] ?></b></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<script>
    reportetablebt("#tablaeliminados");
</script>