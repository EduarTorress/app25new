<table id="tabla_productos" class="table table-bordered table-hover table-sm small">
    <thead>
        <tr>
            <th id="headersysven">Producto</th>
            <!-- <th>Und.</th> -->
            <!-- <th>Sucu 01</th>
            <th>Sucu 02</th>
            <th>Sucu 03</th> -->
            <?php $sucursales = cargarsucursales(); ?>
            <?php foreach ($sucursales as $s) : ?>
                <th id="headersysven"><?php echo $s['nomb']; ?></th>
            <?php endforeach; ?>
            <!-- <th class="">Costo sin IGV</th> -->
            <!-- <th>P. Menor</th>
            <th>P. Mayor</th> -->
            <th id="headersysven" class="text-center">Precios</th>
            <th id="headersysven" class="text-center">Agregar </th>
        </tr>
    </thead>

    <tbody>
        <?php $tds = cargarsucursalestbody(); ?>
        <?php
        $proyecto = (empty($_SESSION['config']['proyecto']) ? '' : $_SESSION['config']['proyecto']);
        $lista1 = array();
        foreach ($lista['lista']['items'] as $k => $prod) {
            $idart = $prod["idart"];
            $lista1[$idart][] = $prod;
        }
        $i = -1;
        foreach ($lista1 as $k => $items) : ?>
            <tr>
                <?php
                $parametro1 = str_replace("'", '"', $items[0]['descri']);
                $parametro2 = $items[0]['idart'];
                $parametro3 = $items[0]['unid'];
                $parametro4 = $items[0]['uno'] + $items[0]['dos'] + $items[0]['tre'];
                $parametro5 = $items[0]['pre1'];
                $parametro6 = $items[0]['pre2'];
                $parametro7 = $items[0]['pre3'];
                $parametro9 = $items[0]['uno'];
                $parametro10 = $items[0]['dos'];
                $parametro11 = $items[0]['tre'];
                $tipro = $items[0]['tipro'];
                $idmarca = $items[0]['idmarca'];
                $idgrupo = $items[0]['idgrupo'];
                $idcat = $items[0]['idcat'];
                $prod_cod1 = str_replace("'", '"', $items[0]['prod_cod1']);
                $peso = $items[0]['peso'];
                $idflete = $items[0]['idflete'];
                $prod_smin = $items[0]['prod_smin'];
                $prod_smax = $items[0]['prod_smax'];
                $costocigv = $items[0]['costocigv'];
                $costosigv = $items[0]['prec'];
                $costo = $items[0]['costo'];
                $flete = $items[0]['flete'];
                $tmon = $items[0]['tmon'];
                $prod_come = $items[0]['prod_come'];
                $prod_comc = $items[0]['prod_comc'];
                $prod_uti1 = $items[0]['prod_uti1'];
                $prod_uti2 = $items[0]['prod_uti2'];
                $prod_uti3 = $items[0]['prod_uti3'];
                $prod_tigv = $items[0]['prod_tigv'];
                $txtcoda1 = str_replace("'", '"', $items[0]['txtcoda1']);
                $j = 0;
                $presentaciones = [];
                foreach ($items as $item) {
                    $presentaciones[$j] = array(
                        'epta_idep' => $item['epta_idep'],
                        'pres_desc' => $item['pres_desc'],
                        'epta_cant' => $item['epta_cant'],
                        'epta_prec' => $item['epta_prec'],
                        'epta_pcor' => $item['epta_pcor']
                    );
                    $j += 1;
                }
                $parametro12 = json_encode($presentaciones);
                $parametros = compact(
                    'parametro1',
                    'parametro2',
                    'parametro3',
                    'parametro4',
                    'parametro5',
                    'parametro6',
                    'parametro7',
                    'idmarca',
                    'idgrupo',
                    'tipro',
                    'idcat',
                    'prod_cod1',
                    'peso',
                    'idflete',
                    'prod_smin',
                    'prod_smax',
                    'costosigv',
                    'costocigv',
                    'flete',
                    'tmon',
                    'prod_come',
                    'prod_comc',
                    'prod_uti1',
                    'prod_uti2',
                    'prod_uti3',
                    'parametro9',
                    'parametro10',
                    'parametro11',
                    'parametro12',
                    'costo',
                    'txtcoda1',
                    'prod_tigv'
                );
                $cadena_json = json_encode($parametros);
                $tipousuario = $_SESSION['usua_apro'];
                $evento = "";
                if ($tipousuario == '1') {
                    $evento = "modaldatosproductoxid(" . $cadena_json . ")";
                }
                ?>
                <td style=" font-size: 10px;" ondblclick='<?php echo $evento; ?>'><?php echo substr($items[0]['descri'], 0, 120) . ' ' . $items[0]['marca']; ?></td>
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
                        <?php echo $item['pres_desc'] . ' - S/ ' . Round($item['epta_prec'], 2) . ($proyecto != 'xsys5' ? ' ' : ' - S/ ' . $item['epta_pcor']) . '<br>' ?>
                    <?php } ?>
                </td>
                <td class="text-center" id="iniciarp">
                    <?php
                    $descri = str_replace("'", ' ', $items[0]['descri']);
                    $descri = str_replace('"', ' ', $descri);
                    $cmarca = str_replace("'", ' ', $items[0]['marca']);
                    $cmarca = str_replace('"', ' ', $cmarca);
                    $parametro1 = $descri . ' - ' . $cmarca;
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
                            'epta_pcor' => ($proyecto != 'xsys5' ? ' ' :  $item['epta_pcor']),
                            'epta_cost' => (empty($item['epta_cost']) ? 0 : $item['epta_cost'])
                        );
                        $i += 1;
                    }
                    $parametro11 = json_encode($presentaciones);
                    $parametro8 = empty($presentaciones[0]['epta_cost']) ? $items[0]['costo'] : $presentaciones[0]['epta_cost'];
                    $parametro12 = $presentaciones[0]['epta_prec'];
                    $stockuno = $items[0]['uno'];
                    $stockdos = $items[0]['dos'];
                    $stocktre = $items[0]['tre'];
                    $tigv = $items[0]['prod_tigv'];
                    $parametros = compact('parametro1', 'parametro2', 'parametro3', 'parametro4', 'parametro5', 'parametro6', 'parametro7', 'parametro8', 'parametro9', 'parametro10', 'parametro11', 'stockuno', 'stockdos', 'stocktre', 'tigv', 'parametro12');
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