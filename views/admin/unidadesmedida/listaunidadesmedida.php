<div class="card card-primary table-responsive">
    <table id="tabla_unidades" class="table table-bordered table-hover table-sm small">
        <thead>
            <tr>
                <th class="text-center" data-sortable="true">Nombre</th>
                <th class="text-center" data-sortable="true">Cantidad Equivalente</th>
                <th class="text-center">Eliminar</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($lista['lista']['items'] as $item) : ?>
                <tr>
                    <td><?php echo $item['pres_desc'] ?></td>
                    <td><?php echo $item['pres_cant'] ?></td>
                    <td>
                        <?php $parametro1 = $item['pres_idpr']; ?>
                        <button onclick='darbaja(<?php echo $parametro1 ?>)' class="btn btn-danger">Eliminar</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<script>
    $(document).ready(function() {
        reportetablebt('#tabla_unidades');
    });
</script>