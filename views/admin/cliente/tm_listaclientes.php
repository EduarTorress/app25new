<table id="tabla_clientes" class="table table-bordered table-hover table-sm small">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>RUC</th>
            <th>DNI</th>
            <th class="text-center">Seleccionar</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($lista['lista']['items'] as $item) : ?>
            <tr>
                <td><?php echo $item['razo'] ?></td>
                <td><?php echo $item['nruc'] ?></td>
                <td><?php echo $item['ndni'] ?></td>
                <td class="text-center" id="iniciar">
                    <?php
                    $parametro1 = $item['idclie'];
                    $parametro2 = str_replace("'", '"', $item['razo']);
                    $parametro3 = $item['nruc'];
                    $parametro4 = $item['ndni'];
                    $parametro5 = trim($item['dire']);
                    $parametros = compact('parametro1', 'parametro2', 'parametro3', 'parametro4', 'parametro5');
                    $cadena_json = json_encode($parametros);
                    ?>
                    <button id="<?php echo "agregar" . $parametro1 ?>" class="btn btn-success" onclick='seleccionarcliente(<?php echo $cadena_json ?>);'><i href="" style="color:white;" class="fas fa-plus-circle"></i></button>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<script>
    $(document).ready(function() {
        focustablacliente('#tabla_clientes')
    });
</script>