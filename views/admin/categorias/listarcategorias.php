<table id="tablacategorias" class="table table-bordered table-hover table-sm small">
    <thead >
        <tr >
            <th>Código</th>
            <th class="text-center">Categoria</th>
            <th class="text-end">Total Productos</th>
            <th class="text-center">Opciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($lista['lista']['items'] as $item) : ?>
            <tr>
                <td><?php echo $item['idcat'] ?></td>
                <td class="text-center"><?php echo $item['dcat'] ?></td>
                <td class="text-end"><?php echo $item['totalproductos'] ?></td>
                <td class="text-center" id="iniciar">
                    <?php
                    $parametro1 = $item['idcat'];
                    $parametro2 = $item['dcat'];
                    $parametro3 = $item['totalproductos'];
                    $parametros = compact('parametro1', 'parametro2', 'parametro3');
                    $cadena_json = json_encode($parametros);
                    ?>
                    <button onclick='modalEdit(<?php echo $parametro1 ?>)' class="btn btn-warning">Editar</button>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<script>
    $(document).ready(function() {
        focustabla('#tablacategorias');
    });
</script>