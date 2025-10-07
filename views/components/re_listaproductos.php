<div class="card-body">
    <div class="table-responsive">
        <table id="tabla_productos" class="table table-bordered table-hover table-sm small">
            <thead>
                <tr>
                    <th style="width:2%;" class="text-center">ID</th>
                    <th style="width:2%" class="text-center">Cod Prov</th>
                    <th style="width:60%;">Producto</th>
                    <th style="width:5%;">Barras</th>
                    <th style="width:5%;">U.M.</th>
                    <th style="width:8%;">Stock Total</th>
                    <?php $sucursales = cargarsucursales(); ?>
                    <?php foreach ($sucursales as $s) : ?>
                        <th style="width:8%;"><?php echo ucfirst(strtolower(substr($s['nomb'], 0, 8))) . "."; ?></th>
                    <?php endforeach; ?>
                    <th style="width:5%;" class="text-center">Opciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $lista1 = array();
                foreach ($lista['lista']['items'] as $k => $prod) {
                    $idart = $prod["idart"];
                    //unset($prod['idart']);
                    $lista1[$idart][] = $prod;
                }
                $i = -1;
                foreach ($lista1 as $k => $items) : ?>
                    <tr>
                        <?php
                        $parametro2 = $items[0]['idart'];
                        ?>
                        <td id="<?php echo 'hola' . $parametro2 ?>"><?php echo $items[0]['idart'] ?></td>
                        <td><?php echo $items[0]['txtcoda1']; ?></td>
                        <td><?php echo $items[0]['descri'] . ' - ' . $items[0]['marca'] ?></td>
                        <td><?php echo $items[0]['prod_cod1'] ?></td>
                        <td><?php echo $items[0]['unid'] ?></td>
                        <td class="text-right"><?php echo ($items[0]['uno'] + $items[0]['dos'] + $items[0]['tre']) ?></td>
                        <?php
                        $sucu = array($items[0]['uno'], $items[0]['dos'], $items[0]['tre']);
                        $i = 0;
                        ?>
                        <?php foreach ($sucursales as $s) : ?>
                            <th class="text-right"><?php echo $sucu[$i]; ?></th>
                        <?php
                            $i++;
                        endforeach;
                        ?>
                        <td class="text-center" id="iniciar">
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
                            $opt = session()->get('tiposel', '0');
                            switch ($opt) {
                                case 0: ?>
                                    <button class="btn btn-success" id="<?php echo "agregar" . $parametro2 ?>" onclick='agregarunitemcarrito(<?php echo $cadena_json ?>)'><a style="color:white;" class="a fas fa-plus-circle"></a></button>
                                <?php break;
                                case 1: ?>
                                    <button class="btn btn-warning" id="<?php echo "agregar" . $parametro2 ?>" data-target="#agregar_cantidad" onclick='agregar_producto(<?php echo $cadena_json ?>)'><i href="" style="color:white;" class="fas fa-plus-circle"></i></button>
                                <?php break;
                                case 3: ?>
                                    <?php if ($tipro == 'C') : ?>
                                        <button class="btn btn-warning" id="<?php echo "agregar" . $parametro2 ?>" onclick='armarcombo(<?php echo $cadena_json ?>)'><i href="" style="color:white;" class="fas fa-plus-circle"></i></button>
                                    <?php endif; ?>
                                <?php break;
                                case 4: ?>
                                    <a class="btn btn-info" role="button" onclick='buscarProductoxId(<?php echo $cadena_json ?>)'>
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    <?php $tipousuario = $_SESSION['usua_apro'];
                                    if ($tipousuario == '1') { ?>
                                        <a class="btn btn-danger" role="button" onclick="anularproducto(<?php echo $item['idart'] ?>)">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                        <a class="btn btn-success" role="button" onclick="obteneridart('<?php echo $items[0]['idart']; ?>');">
                                            <i class="fas fa-plus"></i>
                                        </a>
                                    <?php  } ?>
                                <?php break;
                                case 5: ?>
                                    <button class="btn btn-info" id="<?php echo "agregar" . $parametro2 ?>" onclick='getDataArtStock(<?php echo $cadena_json ?>)'><a class="a fas fa-plus-circle" style="color:white;"></a></button>
                            <?php break;
                            }
                            ?>
                        </td>
                    </tr>
                <?php $i = $i + 1;
                endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<script>
    reportetablebt("#tabla_productos");
</script>