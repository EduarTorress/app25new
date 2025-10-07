<div class="card-body">
    <table id="tabla_unidades" class="table table-bordered table-hover table-sm small">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Cantidad Equivalente</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($lista['lista']['items'] as $item) : ?>
                <tr>
                    <td><?php echo $item['pres_desc'] ?></td>
                    <td><?php echo $item['pres_cant'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<script>
    $(document).ready(function() {
        focustabla('#tabla_unidades')
    });
</script>