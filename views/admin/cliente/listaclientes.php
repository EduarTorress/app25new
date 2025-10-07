<div class="card-body">
    <table id="tabla_remitentes" class="table table-bordered table-hover table-sm small">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>RUC</th>
                <th class="text-center">Opciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($lista['lista']['items'] as $item) : ?>
                <tr>
                    <td><?php echo $item['razo'] ?></td>
                    <td><?php echo $item['nruc'] ?></td>
                    <td class="text-center" id="iniciar">
                        <?php
                        $parametro1 = $item['razo'];
                        $parametro2 = $item['nruc'];
                        $parametro3 = $item['ndni'];
                        $parametro4 = $item['dire'];
                        $parametro5 = $item['ciud'];
                        $parametro6 = $item['ubig'];
                        $parametro7 = $item['idclie'];
                        $parametros = compact('parametro1', 'parametro2', 'parametro3', 'parametro4', 'parametro5', 'parametro6');
                        $cadena_json = json_encode($parametros);
                        ?>
                        <?php
                        $razo = $item['razo'];
                        $editar = "S";
                        if (trim($razo) == 'VENTAS DEL DIA' || trim($razo) == 'ANULADO' || trim($razo) == 'ANULADA') {
                            $editar = "N";
                        }
                        ?>
                        <button onclick='modalEdit("<?php echo $parametro7 ?>")' class="btn btn-warning" style="<?php echo ($editar == 'N' ? 'display:none' : '') ?>">Editar</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<!-- <button onclick='darBaja("<?php echo $parametro7 ?>")' class="btn btn-danger">Eliminar</button> -->
<script>
    $(document).ready(function() {
        focustabla('#tabla_remitentes')
    });
</script>