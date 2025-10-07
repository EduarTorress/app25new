    <table id="tabla_productos" class="table table-bordered table-hover table-sm small">
        <thead>
            <tr>
                <th>Producto</th>
                <!-- <th>Und.</th> -->
                <!-- <th>Sucu 01</th>
            <th>Sucu 02</th>
            <th>Sucu 03</th> -->
                <?php $sucursales = cargarsucursales(); ?>
                <?php foreach ($sucursales as $s) : ?>
                    <th id="<?php echo $s['idalma']; ?>"><?php echo $s['nomb']; ?></th>
                <?php endforeach; ?>
                <!-- <th class="">Costo sin IGV</th> -->
                <!-- <th>P. Menor</th>
            <th>P. Mayor</th> -->
                <th>Precios</th>
                <th class="text-center">Agregar</th>
            </tr>
        </thead>
        <tbody>
            <?php $tds = cargarsucursalestbody(); ?>
            <?php
            $lista1 = array();
            foreach ($lista['lista']['items'] as $k => $prod) {
                $idart = $prod["idart"];
                $lista1[$idart][] = $prod;
            }
            $i = -1;
            foreach ($lista1 as $k => $items) : ?>
                <tr>
                    <td style=" font-size: 10px;"><?php echo substr($items[0]['descri'], 0, 60); ?></td>
                    <!-- <td><?php echo $items[0]['unid'] ?></td> -->
                    <?php foreach ($tds as $t) : ?>
                        <th class="text-end" id="<?php echo $t; ?>"><?php echo $items[0]["$t"]; ?></th>
                    <?php endforeach; ?>
                    <!-- <td><?php echo $items[0]['uno'] ?></td>
                <td><?php echo $items[0]['dos'] ?></td>
                <td><?php echo $items[0]['tre'] ?></td> -->
                    <!-- <td class="costosinigv"><?php echo $items[0]['prec'] ?></td> -->
                    <!-- <td class="text-end"><?php echo $items[0]['pre1'] ?></td>
                <td class="text-end"><?php echo $items[0]['pre3'] ?></td> -->
                    <td><?php foreach ($items as $item) { ?>
                            <?php echo $item['pres_desc'] . ' - S/ ' . Round($item['epta_prec'], 2) . '<br>' ?>
                        <?php } ?>
                    </td>
                    <td class="text-center" id="iniciarp">
                        <?php
                        $parametro1 = $items[0]['descri'];
                        $parametro2 = $items[0]['idart'];
                        $parametro3 = $items[0]['unid'];
                        $parametro4 = $items[0]['uno'] + $items[0]['dos'] + $items[0]['tre'] + $items[0]['cua'];
                        if ($items[0]['tipro'] == 'K') {
                            $parametro5 = $items[0]['pre3'];
                        } else {
                            $parametro5 = $items[0]['costo'];
                        }
                        $parametro6 = $items[0]['pre2'];
                        $parametro7 = $items[0]['prec'];
                        $parametro8 = $items[0]['costo'];
                        $parametro9 = $items[0]['peso'];
                        $parametro10 = $items[0]['tipro'];
                        $presentaciones = [];
                        $i = 0;
                        foreach ($items as $item) {
                            $presentaciones[$i] = array(
                                'epta_idep' => $item['epta_idep'],
                                'pres_desc' => $item['pres_desc'],
                                'epta_cant' => $item['epta_cant'],
                                'epta_prec' => $item['epta_prec'],
                            );
                            $i += 1;
                        }
                        $parametro11 = json_encode($presentaciones);
                        $stockuno = $items[0]['uno'];
                        $stockdos = $items[0]['dos'];
                        $stocktre = $items[0]['tre'];
                        $parametros = compact('parametro1', 'parametro2', 'parametro3', 'parametro4', 'parametro5', 'parametro6', 'parametro7', 'parametro8', 'parametro9', 'parametro10', 'parametro11', 'stockuno', 'stockdos', 'stocktre');
                        $cadena_json = json_encode($parametros);
                        ?>
                        <button class="btn <?php echo ((intval($parametro4) < 0) ?  'btn-danger' : 'btn-success') ?>" data-target="#agregar_cantidad" id="<?php echo 'agregar' . $parametro2 ?>" onclick='agregarunitemVenta(<?php echo $cadena_json ?>)'><i href="" style="color:white;" class="fas fa-plus-circle"></i></button>
                    </td>
                </tr>
            <?php $i = $i + 1;
            endforeach; ?>
        </tbody>
    </table>
<script>
    $(document).ready(function() {
        focustablaproducto('#tabla_productos');
    });
</script>