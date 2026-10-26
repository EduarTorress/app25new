<table id="" class="table table-bordered table-hover table-sm small">
    <thead>
        <tr>
            <th>Producto</th>
            <th>Und.</th>
            <th>Stock</th>
            <th>Precio</th>
            <th class="text-center">Agregar</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($lista['lista']['items'] as $item) : ?>
            <tr>
                <td><?php echo $item['descri'] ?></td>
                <td><?php echo $item['unid'] ?></td>
                <?php
                $parametro4 = $item['uno'] + $item['dos'] + $item['tre'] + $item['cua'] + $item['cin'];
                $validarstock = (empty($_SESSION['config']['validarstock']) ? 'N' : $_SESSION['config']['validarstock']);
                if ($validarstock == 'S') {
                    $parametro4 = floatval($item[verificarenquesucursalestoy()]);
                }
                ?>
                <td><?php echo $parametro4 ?></td>
                <td><?php echo $item['costo'] ?></td>
                <td class="text-center" id="iniciar">
                    <?php
                    $parametro1 = $item['descri'];
                    $parametro2 = $item['idart'];
                    $parametro3 = $item['unid'];
                    $parametro5 = $item['pre1'];
                    $parametro6 = $item['pre2'];
                    $parametro7 = $item['prec'];
                    $parametro8 = $item['costo'];
                    $parametro9 = $item['peso'];
                    $parametro10 = $item['tipro'];
                    $parametros = compact('parametro1', 'parametro2', 'parametro3', 'parametro4', 'parametro5', 'parametro6', 'parametro7', 'parametro8', 'parametro9');
                    $cadena_json = json_encode($parametros);
                    if ($parametro10 != 'C'):
                    ?>
                        <button class="btn btn-success" data-target="#agregar_cantidad" id="<?php echo 'agregar' . $parametro2 ?>" onclick='agregarunitemVenta(<?php echo $cadena_json ?>)'><i href="" style="color:white;" class="fas fa-plus-circle"></i></button>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>