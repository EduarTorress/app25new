<?php

use App\View\Components\DocumentoComponent;
use App\View\Components\FormadepagoComponent;
use App\View\Components\IGVComponent;
use App\View\Components\TipoMonedaComponent;
use App\View\Components\ValorDolarComponent;
use App\View\Components\ModalProveedorComponent;
use App\View\Components\ModalProductoComponent;
use App\View\Components\ModalRegistroCuentasxPagarComponent;
?>
<?php
$this->setLayout('layouts/admin');
?>
<?php
$this->startSection('contenido');
?>
<?php
$prov = new ModalProveedorComponent();
echo $prov->render();
?>
<?php
$prod = new ModalProductoComponent();
echo $prod->render();
?>
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-4">
                    <div class="input-group ">
                        <input type="text" class="form-control form-control-sm" id="txtproveedor" aria-label="" aria-describedby="basic-addon2" placeholder="Proveedor" disabled value="<?php echo isset($datosproveedor['razo']) ?  trim($datosproveedor['razo']) : '' ?>">
                        <input type="hidden" id="txtidproveedor" value="<?php echo isset($datosproveedor['idprov']) ?  $datosproveedor['idprov'] : '' ?> ">
                        <input type="hidden" id="txtrucproveedor" value=""><input type="hidden" id="txtptopartida" value=""><input type="hidden" id="txtUbigeoproveedor" value="">
                        <input type="hidden" id="txtidauto" value="<?php echo isset($idcompra) ? $idcompra : 0 ?>">
                        <button class="btn btn-outline-light" role="button" data-bs-toggle="modal" data-bs-target="#modal_proveedor"><i style="color:black" class="fas fa-user-alt"></i></button>
                    </div>
                </div>
                <div class="col-sm-2">
                    <?php
                    $ctdoc = isset($datosproveedor['tdoc']) ? $datosproveedor['tdoc'] : '';
                    $dctos = new DocumentoComponent($ctdoc);
                    echo $dctos->rendercompras();
                    ?>
                </div>
                <div class="col-sm-3">
                    <div class="input-group">&nbsp;&nbsp;&nbsp;
                        <label class="col-sm-0 col-form-label col-form-label-sm">Número: </label>
                        <input type="text" onkeyup="mayusculas(this); isFormatSerie()" class="form-control form-control-sm col-3" maxlength="4" id="cndoc1" value="<?php echo isset($serie) ?  trim($serie) : '' ?>" placeholder="F001">
                        <input type="text" onkeypress="return isNumberNdoc(event);" onblur="rellenaNumero()" class="form-control form-control-sm" maxlength="8" id="cndoc2" value="<?php echo isset($num) ?  $num : '' ?>" placeholder="00000001" pattern="^[0-9]" />
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="input-group">&nbsp;&nbsp;&nbsp;
                        <label class="col-sm-0 col-form-label col-form-label-sm">Guía:</label>
                        <input type="text" class="form-control form-control-sm" id="ndo2" style="width: 100px;" value="<?php echo isset($datosproveedor['ndo2']) ?  $datosproveedor['ndo2'] : '' ?>" placeholder="T00100000001">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-2">
                    <?php
                    $cempresa = isset($datosproveedor['alm']) ? $datosproveedor['alm'] : $_SESSION['idalmacen'];
                    $empresa = new \App\View\Components\EmpresaComponent($cempresa);
                    echo $empresa->render();
                    ?>
                </div>
                <div class="col-sm-2">
                    <?php
                    $cforma = isset($datosproveedor['form']) ? $datosproveedor['form'] : '';
                    $formapago = new FormadepagoComponent($cforma);
                    echo $formapago->render();
                    ?>
                </div>
                <div class="col-sm-2">
                    <?php
                    $cmon = isset($datosproveedor['mone']) ? $datosproveedor['mone'] : '';
                    $tpmoneda = new TipoMonedaComponent($cmon);
                    echo $tpmoneda->render();
                    ?>
                </div>
                <div class="col-sm-3">
                    <div class="input-group">&nbsp;&nbsp;&nbsp;
                        <label class="col-sm-0 col-form-label col-form-label-sm">Fecha Doc. :</label>
                        <input type="date" class="form-control form-control-sm" value="<?php echo empty($datosproveedor['fech']) ?  date("Y-m-d") :  $datosproveedor['fech'] ?>" style="width:140px;" id="txtfechai" name="txtfechai">
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="input-group">&nbsp;&nbsp;&nbsp;
                        <label class="col-sm-0 col-form-label col-form-label-sm">Fecha Reg. :</label>
                        <input type="date" class="form-control form-control-sm" value="<?php echo empty($datosproveedor['fecr']) ?  date("Y-m-d") :  $datosproveedor['fecr']; ?>" style="width:140px;" id="txtfechaf" name="txtfechaf">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-4">
                    <?php
                    $optigv = isset($datosproveedor['optigv']) ? $datosproveedor['optigv'] : 'I';
                    $igv = new IGVComponent($optigv);
                    echo $igv->render();
                    ?>
                </div>
                <div class="col-sm-2" id="divdolar">
                    <?php
                    $dolar = new ValorDolarComponent();
                    echo $dolar->render();
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-success card-outline" style="width:max-content; width:auto;">
                        <div class="col-12" id="detalle">
                            <?php if ($v == 'M') : ?>
                                <div class="table-responsive">
                                    <table class="table table-sm small table table-hover" id="griddetalle">
                                        <thead>
                                            <tr>
                                                <th scope="col" style="width:2%">Opciones</th>
                                                <th scope="col" style="width:3%" class="codigo">Código</th>
                                                <th scope="col" style="width:28%">Producto</th>
                                                <th scope="col" style="width:5%">U.M.</th>
                                                <th scope="col" style="width:5%">Cantidad</th>
                                                <th scope="col" style="width:5%">Precio</th>
                                                <?php if (!empty($_SESSION['config']['tipobotica'])) : ?>
                                                    <th scope="col" style="width:5%">Lote</th>
                                                    <th scope="col" style="width:5%">Fecha Vto.</th>
                                                <?php endif; ?>
                                                <th scope="col" style="width:5%">Importe</th>
                                                <th scope="col" style="width:5%" class="text-center">No Afecto</th>
                                            </tr>
                                        </thead>
                                        <tbody id="bodycompras">
                                            <?php $i = 0; ?>
                                            <?php foreach ($carritoc as $indice => $item) : ?>
                                                <?php if ($item['activo'] == 'A') { ?>
                                                    <tr onkeyup="calcularsubtotal(this); actualizarProducto(this,<?php echo $indice ?>); " onchange="obtenerPrecio(this,<?php echo $indice ?>);">
                                                        <?php
                                                        $parametro1 = $item['descri'];
                                                        $parametro2 = $item['coda'];
                                                        $parametro3 = $item['unidad'];
                                                        $parametro4 = $item['cantidad'];
                                                        $parametro5 = $item['precio'];
                                                        $parametro6 = $indice;
                                                        $parametros = compact('parametro1', 'parametro2', 'parametro3', 'parametro4', 'parametro5', 'parametro6');
                                                        $cadena_json = json_encode($parametros);
                                                        ?>
                                                        <td>
                                                            <button class="btn btn-warning" onclick="quitaritem(<?php echo $indice ?>)"><a style="color:white" class="fas fa-trash-alt"></a></button>
                                                        </td>
                                                        <td class="codigo"><?php echo $item['coda'] ?></td>
                                                        <td><?php echo $item['descri'] ?></td>
                                                        <td><?php
                                                            $presentaciones = json_decode($item['presentaciones'], true); ?>
                                                            <select onchange="cambiarpresentacion(this,<?php echo $indice ?>)" class="form-control form-control-sm" name="cmbpresentaciones" id="cmbpresentaciones">
                                                                <?php foreach ($presentaciones as $p) : ?>
                                                                    <option value="<?php echo $p['epta_idep'] . '-' . $p['epta_prec'] ?>" <?php echo (($p['epta_idep'] == $item['presseleccionada']) ? 'selected' : '') ?>>
                                                                        <?php echo trim($p['pres_desc']) . '-' . $p['epta_cant']; ?>
                                                                    </option>
                                                                <?php endforeach;
                                                                ?>
                                                            </select>
                                                        </td>
                                                        <td class="text-center" onclick="funcionEnterCant(this,<?php echo $indice ?>)" onkeypress="return isNumber(event);" contenteditable="false" name="cantidad"><input onclick="this.select(); clicksubtotal=0;" type="text" class="inputright" onkeypress="return isNumber(event);" value="<?php echo number_format($item['cantidad'], 2, '.', '') ?>"></td>
                                                        <td class="precio text-center" id="precio" onkeypress="return isNumber(event);" contenteditable="false" name="precio"><input onclick="this.select(); clicksubtotal=0;" onkeypress="return isNumber(event);" type="text" class="inputright" value="<?php echo number_format($item['precio'], 2, '.', '') ?>"></td>
                                                        <?php if (!empty($_SESSION['config']['tipobotica'])) : ?>
                                                            <td class="text-center" class="lote"><input onclick="this.select(); clicksubtotal=0;" type="text" class="" value="<?php echo (empty($item['lote']) ? ' ' : $item['lote']); ?>"></td>
                                                            <td class="text-center" class="fechavto"><input class="fechavtoproducto" min="<?php echo date('Y-m-d'); ?>" onclick="this.select(); clicksubtotal=0;" type="date" value="<?php echo (empty($item['fechavto']) ? ' ' : $item['fechavto']); ?>"></td>
                                                            <style>
                                                                .fechavtoproducto::-webkit-inner-spin-button,
                                                                .fechavtoproducto::-webkit-calendar-picker-indicator {
                                                                    display: none;
                                                                    -webkit-appearance: none;
                                                                }
                                                            </style>
                                                        <?php endif; ?>
                                                        <td class="text-center" class="total"><input onclick="this.select(); clicksubtotal=1;" onkeypress="return isNumber(event);" type="text" class="inputright" value="<?php echo number_format(round($item['cantidad'] * $item['precio'], 2), 2, '.', '') ?>"></td>
                                                        <td class="text-center" class="afecto">
                                                            <?php
                                                            $checkafecto = "";
                                                            if (trim($item['checkafecto']) == "true") {
                                                                $checkafecto = "checked";
                                                            }
                                                            ?>
                                                            <input type="checkbox" <?php echo $checkafecto; ?> class="" id="checkmarcado<?php echo $indice; ?>" name="checkafecto" onclick="cambiarcheckafecto(this,<?php echo $indice ?>)">
                                                        </td>
                                                        <?php $i++; ?>
                                                    </tr>
                                                <?php } ?>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div><br>
                                <div class="col-lg-12">
                                    <div class="card card-success card-outline" style="width:auto;">
                                        <div class="row">
                                            <div class="col-7 align-items-start">
                                                <div class="input-group">
                                                    <label class="col-form-label form-control-sm">Observaciones:</label>
                                                    <div>
                                                        <textarea class="form-control form-control-sm" id="txtdetalle" name="txtdetalle" style="width:150%; height:65%;"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-2 align-items-start">
                                                <div class="input-group mb-3" style="width: 85%;">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text text-sm" id=""><strong>Items:</strong></span>
                                                    </div>
                                                    <input type="text" class="form-control text-right text-sm" id="totalitems" aria-label="Small" value="<?php echo $items ?>" aria-describedby="inputGroup-sizing-sm" disabled>
                                                </div>
                                            </div>
                                            <div class="col-3 align-items-start">
                                                <div class="input-group" style="width: 90%;">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text text-sm"><strong>Sub Total</strong></span>
                                                    </div>
                                                    <input type="text" class="form-control text-right text-sm" id="subtotal" aria-label="Small" value="<?php ?>" aria-describedby="inputGroup-sizing-sm" disabled>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-7">
                                                <div class="form-check" id="inputpercepcion">
                                                    <input class="form-check-input" type="checkbox" class="" value="S" id="cbpercepcion">
                                                    <label class="form-check-label" for="">
                                                        Aplica Percepción
                                                    </label>
                                                </div>
                                                <?php
                                                $check = "";
                                                if (trim($checknodescontarstock) == "true") {
                                                    $check = "checked";
                                                }
                                                ?>
                                                <div class="form-check" style="display:none">
                                                    <input class="form-check-input" <?php echo $check; ?> type="checkbox" class="" value="0" id="cbdescontarstock" onclick="cambiarchecknodescontarstock(this)">
                                                    <label class="form-check-label" for="">
                                                        No incrementar Stock
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-2">
                                                <div class="input-group mb-3" style="width: 85%;">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text text-sm" id=""><strong>Perce.</strong></span>
                                                    </div>
                                                    <input type="text" class="form-control text-right text-sm" id="txtpercepcion" aria-label="Small" aria-describedby="inputGroup-sizing-sm" disabled>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <div class="input-group " style="width: 90%;">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text text-sm" id=""><strong>EXON. &emsp;</strong></span>
                                                    </div>
                                                    <input type="text" class="form-control text-right text-sm" id="exonerado" aria-label="Small" value="" aria-describedby="inputGroup-sizing-sm" disabled>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-7">
                                                <button class="btn btn-primary btn-sm" role="button" data-bs-toggle="modal" data-bs-target="#modal_productos"><a style="color:white;">Agregar</a></button>
                                                <button class="btn btn-danger btn-sm" id="cancelar" role="button" onclick="cancelarCompra()">Limpiar</button>
                                                <button class="btn btn-success btn-sm" id="grabar" role="button" onclick="vermodalactualizarprecios();"><?php echo (isset($btn) ? $btn : 'Grabar') ?></button>
                                            </div>
                                            <div class="col-2">
                                                <div class="input-group mb-3" style="width: 85%;">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text text-sm" id=""><strong>Pagar</strong></span>
                                                    </div>
                                                    <input type="text" class="form-control text-right text-sm" id="txttotalpercepcion" aria-label="Small" aria-describedby="inputGroup-sizing-sm" disabled>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <div class="input-group " style="width: 90%;">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text text-sm" id=""><strong>IGV &emsp;&emsp;&ensp;</strong></span>
                                                    </div>
                                                    <input type="text" class="form-control text-right text-sm" id="igv" aria-label="Small" value="" aria-describedby="inputGroup-sizing-sm" disabled>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-7"></div>
                                            <div class="col-2">
                                                <div class="input-group mb-3" style="width: 85%;">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text text-sm" id=""><strong>Desc.</strong></span>
                                                    </div>
                                                    <input type="text" onkeypress="return isNumber();" class="form-control text-right text-sm" onblur="grabardescuento();" id="txtdescuento" value="<?php echo (empty($_SESSION['txtdescuento']) ? '0.00' : $_SESSION['txtdescuento']) ?>" onclick="this.select();" aria-label="Small">
                                                </div>
                                            </div>
                                            <div class="col-3 align-items-start">
                                                <div class="input-group mb-3" style="width: 90%;">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text text-sm" id=""><strong>TOTAL &emsp;</strong></span>
                                                    </div>
                                                    <input type="text" class="form-control text-right text-sm" id="total" aria-label="Small" value="<?php echo  $total ?>" aria-describedby="inputGroup-sizing-sm" disabled>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="mdactualizarprecios" tabindex="-1" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="">¿Actualizar Costos?</h5>
            </div>
            <div class="modal-body">
                <select onchange="" class="form-control form-control-sm" id="actualizarprecios" name="actualizarprecios">
                    <option value="N">NO</option>
                    <option value="S">SI</option>
                </select>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="grabaropcion();">Grabar</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<div id="divpresentaciones"></div>
<?php
$mdrcxp = new ModalRegistroCuentasxPagarComponent();
echo $mdrcxp->render();
?>
<?php
$this->endSection('contenido');
?>
<?php
$this->startSection('javascript');
?>
<script>
    window.onload = function() {
        clicksubtotal = 0;
        titulo("<?php echo $titulo ?>");
        valor = "<?php echo $v ?>";
        if (valor == 'R') {
            axios.get('/compras/listardetalle').then(function(respuesta) {
                const contenido_tabla = respuesta.data;
                $('#detalle').html(contenido_tabla);
            }).catch(function(error) {
                toastr.error('Error al cargar el listado' + error, 'Mensaje del sistema')
            });
        }
        $(".tipodocumentos option[value='07']").remove();
        $(".tipodocumentos option[value='08']").remove();
        $(".tipodocumentos option[value='22']").remove();
        $(".tipodocumentos option[value='20']").remove();
        $(".codigo").css("display", "none");
        $("#cmbAlmacen").removeAttr("disabled");
        fechai = document.getElementById('txtfechai').value;
        obtenerDolar(fechai);
        calcularIGV();
        $("#cndoc1").val("<?php echo isset($serie) ?  $serie : '' ?>");
        $("#txtidproveedor").val("<?php echo isset($datosproveedor['idprov']) ?  $datosproveedor['idprov'] : '' ?>");
        $("#txtproveedor").val("<?php echo isset($datosproveedor['razo']) ?  $datosproveedor['razo'] : '' ?>");
        $("#cmbAlmacen option[value='0']").remove();
    }

    $(".tipodocumentos").on("change", function() {
        isFormatSerie();
    });

    $("#modal_productos").on("shown.bs.modal", function() {
        filastbl = document.getElementById("griddetalle").rows.length;
        if (filastbl <= 1) {
            moverCursorFinalTexto("txtbuscarProducto");
        }
    });

    $("#modal_proveedor").on("shown.bs.modal", function() {
        $("#txtbuscarprov").focus();
    });

    function calcularpercepcion() {
        subtotal = $("#total").val();
        nper = <?php echo round($_SESSION['gene_nper']) / 100; ?>;
        percepcion = Number(subtotal) * Number(nper);
        total = Number(subtotal) + Number(percepcion);
        if ($("#cbpercepcion").is(':checked')) {
            $("#txtpercepcion").val(percepcion.toFixed(2));
            $("#txttotalpercepcion").val(total.toFixed(2));
        };
        if ($("#cbpercepcion").is(':checked') == false) {
            $("#txtpercepcion").val("0.00");
            subtotal = $("#total").val();
            $("#txttotalpercepcion").val(subtotal);
            // igv = obtenerTipoIGV();
            // var total_col = 0;
            // $('#griddetalle tbody').find('tr').each(function(i, el) {
            //     total_col += parseFloat($(this).find('td').eq(6).text());
            // });
            // if (igv == 'I') {
            //     //Si el IGV está incluido
            //     let impo = (Number(total_col)).toFixed(2);
            //     let valor = (impo / 1.18).toFixed(2);
            //     let nigv = (impo - valor).toFixed(2);
            //     $("#igv").val(nigv);
            //     $("#subtotal").val(valor);
            //     $("#total").val(impo);
            // } else {
            //     impo = Number(total_col);
            //     $("#subtotal").val(impo.toFixed(2));
            //     $("#igv").val("18");
            //     imponoigv = ((impo * 0.18) + impo);
            //     $("#total").val(imponoigv.toFixed(2));
            // }
            // let impor = $("#total").val();
            // if (isNaN(impor)) {
            //     $("#subtotal").val("0.00");
            //     $("#igv").val("0.00");
            //     $("#total").val("0.00");
            // }
        };
    }

    function grabardescuento() {
        txtdescuento = $("#txtdescuento").val();
        const data = new FormData();
        data.append("txtdescuento", txtdescuento);
        axios.post('/compras/generardescuento', data)
            .then(function(respuesta) {
                const contenido_tabla = respuesta.data;
                $('#detalle').html(contenido_tabla);
            }).catch(function(error) {
                console.log(error);
            });
    }

    function cambiarchecknodescontarstock(element) {
        // tr = $(element).parent().parent().parent();
        marcado = false;
        if (element.checked == true) {
            marcado = true;
        }
        const data = new FormData();
        data.append("checknodescontarstock", marcado);
        axios.post('/compras/checknodescontarstock', data)
            .then(function(respuesta) {
                calcularIGV()
            }).catch(function(error) {
                console.log(error);
            });
    }

    function cambiarcheckafecto(element, i) {
        // tr = $(element).parent().parent().parent();
        marcado = false;
        if (element.checked == true) {
            marcado = true;
        }
        const data = new FormData();
        data.append("indice", i);
        data.append("marcado", marcado);
        axios.post('/compras/checkafecto', data)
            .then(function(respuesta) {
                // calcularafecto();
                calcularIGV()
            }).catch(function(error) {
                console.log(error);
            });
    }

    // function calcularafecto() {
    //     totalexon = 0;
    //     $('#griddetalle tbody tr').each(function() {
    //         _tr = $(this);
    //         td = _tr.find("td").eq(7).find("input");
    //         var subtotal = _tr.find("td").eq(6).text();
    //         var isChecked = $(td).is(":checked");
    //         if (isChecked) {
    //             totalexon = totalexon + Number(subtotal);
    //         }
    //     });
    //     if (totalexon > 0) {
    //         subtotal = $("#subtotal").val();
    //         igv = $("#igv").val();
    //         subtotalafecto = Number(subtotal) / <?php echo $_SESSION['gene_igv'] ?>;
    //         igvafecto = (Number(subtotalafecto) * 0.18);
    //         $("#exonerado").val(Number(totalexon).toFixed(2))
    //         // $("#subtotal").val(subtotalafecto.toFixed(2));
    //         // $("#igv").val(igvafecto.toFixed(2));
    //         $("#subtotal").val("0.00");
    //         $("#igv").val("0.00");
    //     } else {
    //         $("#exonerado").val("0.00")
    //         igv = obtenerTipoIGV();
    //         var total_col = 0;
    //         // var total_noexon = 0;
    //         $('#griddetalle tbody').find('tr').each(function(i, el) {
    //             total_col += parseFloat($(this).find('td').eq(6).text());
    //             // total_noexon += parseFloat($(this).find('td').eq(6).find("input").val());
    //         });
    //         if (igv == 'I') {
    //             //Si el IGV está incluido
    //             let impo = (Number(total_col)).toFixed(2);
    //             let valor = (impo / 1.18).toFixed(2);
    //             let nigv = (impo - valor).toFixed(2);
    //             $("#igv").val(nigv);
    //             $("#subtotal").val(valor);
    //             $("#total").val(impo);
    //         } else {
    //             impo = Number(total_col);
    //             $("#subtotal").val(impo.toFixed(2));
    //             $("#igv").val("18");
    //             imponoigv = ((impo * 0.18) + impo);
    //             $("#total").val(imponoigv.toFixed(2));
    //         }
    //         calcularpercepcion();
    //         let impor = $("#total").val();
    //         if (isNaN(impor)) {
    //             $("#subtotal").val("0.00");
    //             $("#igv").val("0.00");
    //             $("#total").val("0.00");
    //         }
    //     }
    // }

    function agregarunitemVenta(datos) {
        presentaciones = JSON.parse(datos.parametro11);
        precio = presentaciones[0]['epta_prec'];
        unidad = presentaciones[0]['pres_desc']
        cantequi = presentaciones[0]['epta_cant'];
        eptaidep = presentaciones[0]['epta_idep'];
        const data = new FormData();
        data.append('txtcodigo', datos.parametro2);
        data.append("txtdescripcion", datos.parametro1);
        // data.append("txtunidad", datos.parametro3);
        // data.append("txtprecio", datos.parametro5);
        data.append("txtunidad", unidad);
        data.append("txtprecio", Number(precio).toFixed(2));
        data.append("txtcantidad", 1);
        data.append("precio1", datos.parametro5);
        data.append("precio2", datos.parametro6);
        data.append("precio3", datos.parametro7);
        data.append("costo", datos.parametro8);
        data.append("presentaciones", datos.parametro11);
        data.append("presseleccionada", eptaidep);
        data.append("cantequi", cantequi);
        data.append("stock", parseFloat(datos.parametro4.toFixed(2)));
        data.append("opt", 0)
        axios.post('/compras/agregaritem', data)
            .then(function(respuesta) {
                //window.location.href = '/vtas/index';
                $('#modal_productos').modal('hide')
                const contenido_tabla = respuesta.data;
                $('#detalle').html(contenido_tabla);
                calcularIGV();
                //$("#griddetalle tr:last").focus()
                var a = $("#griddetalle tr:last td:eq(4)").each(function() {
                    $(this).focus();
                    $(this).click();
                });
                idart = "#agregar" + datos.parametro2;
                // console.log(idart);
                $(idart).attr('disabled', 'disabled');
            }).catch(function(error) {
                if (error.hasOwnProperty("response")) {
                    if (error.response.status === 422) {
                        toastr.error(error.response.data.errors, "Mensaje del Sistema");
                    }
                }
            });
    }

    $("#griddetalle tr:last td:eq(5) .inputright").on("keypress", function(evt) {
        if (evt.key === "Enter") {
            $("#modal_productos").modal('show');
        }
    });

    columantotal = "<?php echo empty($_SESSION['config']['tipobotica']) ? 6  : 8; ?>";
    $("#griddetalle tr:last td:eq(" + columantotal + ") .inputright").on("keypress", function(evt) {
        if (evt.key === "Enter") {
            $("#modal_productos").modal('show');
            clicksubtotal = 0;
        }
    });

    function quitaritem(pos) {
        const data = new FormData();
        data.append("indice", pos)
        axios.post('/compras/quitaritem', data)
            .then(function(respuesta) {
                const contenido_tabla = respuesta.data;
                $('#detalle').html(contenido_tabla);
                calcularIGV();
                // $('#totalpedido').html(document.querySelector("#total").value);
            }).catch(function(error) {
                toastr.error('Ocurrió un error' + error, 'Mensaje del sistema');
            });
    }

    function cancelarCompra() {
        axios.post('/compras/limpiar').then(function(respuesta) {
            const tabla = respuesta.data;
            $('#detalle').html(tabla);
            limpiardatos();
        }).catch(function(error) {
            console.log(error);
            // toastr.error(error, 'Mensaje del sistema');
        });
    }

    function limpiardatos() {
        document.querySelector('#txtproveedor').value = "";
        document.getElementById("titulo").innerHTML = "Regs. Compra";
        document.getElementById("grabar").innerHTML = "Grabar";
        document.querySelector("#txtidproveedor").value = "0";
        document.querySelector("#cndoc1").value = "";
        document.querySelector("#cndoc2").value = "";
        document.querySelector("#ndo2").value = "";
        document.querySelector("#cmbforma").value = "E";
        // document.querySelector("#cmbAlmacen").value = "1";
        document.querySelector("#cmbmoneda").value = "S";
        document.querySelector('#txtdolar').value = "";
        window.location.href = '/compras/index';
    }

    function validarCompra() {
        idProv = document.querySelector('#txtidproveedor').value;
        total = document.querySelector('#total').value;
        cndoc1 = $("#cndoc1").val();
        cndoc2 = $("#cndoc2").val();
        if (cndoc1 == '') {
            toastr.info("Dígite la serie", 'Mensaje del Sistema');
            return false;
        }
        if (cndoc2 == '') {
            toastr.info("Dígite el número", 'Mensaje del Sistema');
            return false;
        }
        if (idProv == 0) {
            toastr.info("Seleccione un proveedor", 'Mensaje del Sistema');
            return false;
        }
        if (total == 0) {
            toastr.info("Ingrese importes válidos", 'Mensaje del Sistema');
            return false;
        }
        return true;
    }

    function vermodalactualizarprecios() {
        if (!validarCompra()) {
            return;
        }
        $("#mdactualizarprecios").modal('show');
    }

    function grabaropcion() {
        $("#mdactualizarprecios").modal('hide');
        grabarCompra();
    }

    // $('#mdactualizarprecios').on('hidden.bs.modal', function() {
    // });

    // Modal Registro Cuentas x Pagar inicio

    $('#modalregistrocuentasxpagar').on('shown.bs.modal', function() {
        $("#txtnumeroletras").select();
    });

    function crearfilas() {
        let num = document.querySelector("#cndoc2").value
        let cndoc = (document.querySelector("#cndoc1").value + num).toUpperCase();
        cantidadletras = $("#txtnumeroletras").val();
        $("#tblletras tbody").empty();
        for (var i = 0; i < Number(cantidadletras); i++) {
            var fila = '<tr>' +
                '<td><input type="text" class="ndoc" style="font-size:10px;" value="' + cndoc + '" readonly></td>' +
                '<td><input type="number" oninput="this.value = Math.round(this.value);" class="txtdiasvto" style="font-size:10px;" onfocus="this.select();" onkeyup="calcularfechaxdias(this)" ></td>' +
                '<td><input type="date" class="txtfechavto" style="font-size:10px;" value="<?php echo date('Y-m-d'); ?>"></td>' +
                '<td><input type="text" class="txtreferenciacxpagar" style="font-size:10px;"></td>' +
                '<td><input type="text" class="txtimporte" style="font-size:10px;" onkeypress="isNumber(event)" onfocus="this.select();"></td>' +
                '</tr>';
            $('#tblletras tbody').append(fila);
        }
    }

    function calcularfechaxdias(t) {
        txtdias = $(t).val();
        txtfecha = $("#txtfechai").val();
        txtfechavto = $(t).parent().next().find("input");
        calcularfechavto(txtfecha, txtdias, txtfechavto);
    }

    function calcularfechavto(txtfecha, txtdias, txtfechavto) {
        axios.get('/calcularfechavto', {
            "params": {
                "txtfecha": txtfecha,
                'txtdias': txtdias
            }
        }).then(function(respuesta) {
            $(txtfechavto).val(respuesta.data);
        }).catch(function(error) {
            toastr.error('Error al cargar el listado' + error, 'Mensaje del Sistema')
        });
    }

    // Modal Registro Cuentas x Pagar  END

    function grabarCompra() {
        if (!validarCompra()) {
            return;
        }
        var cmensaje = "";
        if (document.querySelector('#txtidauto').value == '0') {
            cmbformapago = $("#cmbforma").val();
            if (cmbformapago == 'C') {
                total = $("#total").val();
                total = Number(total).toFixed(2);
                $("#txtimportefinal").val(total);
                $("#modalregistrocuentasxpagar").modal('show');
            } else {
                cmensaje = '¿Registrar Compra?';
                grabar(cmensaje);
            }
        } else {
            cmensaje = '¿Actualizar Compra?';
            actualizar(cmensaje);
        }
    }

    function grabar(cmensaje) {
        const detalle = []
        e = 0;
        totalsuma = 0;
        cmbtipodocumentocuentasxpagar = '';
        let form = document.getElementById("cmbforma").value;
        if (form == 'C') {
            $("#tblletras tbody tr").each(function() {
                json = "";
                $(this).find("td input").each(function() {
                    $this = $(this);
                    json += ',"' + $this.attr("class") + '":"' + $this.val() + '"'
                    valor = $this.val();
                    if ($this.attr("class") == 'txtimporte') {
                        if (Number(valor) == 0 || valor == "0" || valor == " ") {
                            e = 1;
                        }
                    }
                    if ($this.attr("class") == 'txtdiasvto') {
                        if (Number(valor) == 0 || valor == "0" || valor == " ") {
                            e = 1;
                        }
                    }
                    if ($this.attr("class") == 'txtimporte') {
                        totalsuma += Number(valor);
                    }
                });
                obj = JSON.parse('{' + json.substr(1) + '}');
                detalle.push(obj)
            });
            if (e == 1) {
                toastr.error("Complete los datos correctamente", 'Mensaje del sistema');
                return;
            }
            importetotal = $("#total").val();
            if (Number(totalsuma) > Number(importetotal)) {
                toastr.error("El monto sumado no debe ser mayor al total", 'Mensaje del sistema');
                return;
            }
            txtnumeroletras = $("#txtnumeroletras").val();
            if (txtnumeroletras.length == 0 || txtnumeroletras == '' || Number(txtnumeroletras) == 0) {
                toastr.error("Ingrese el número de letras", 'Mensaje del sistema');
                return;
            }
        }
        Swal.fire({
            title: cmensaje,
            text: "Se registrará en el sistema ",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si'
        }).then(function(respuesta) {
            if (respuesta.isConfirmed) {
                let tdoc = document.getElementById("cmbdcto").value;
                let num = document.querySelector("#cndoc2").value
                if (num.length < 8) {
                    while (num.length < 8)
                        num = '0' + num;
                }
                let cndoc = (document.querySelector("#cndoc1").value + num).toUpperCase();
                let form = document.getElementById("cmbforma").value;
                let deta = document.querySelector("#txtdetalle").value;
                let impo = document.querySelector("#total").value;
                let ndo2 = document.querySelector("#ndo2").value;
                let mon = document.getElementById("cmbmoneda").value;
                let fechi = document.getElementById("txtfechai").value;
                let fechf = document.getElementById("txtfechaf").value;
                let dolar = document.getElementById("txtdolar").value;
                let idprov = document.getElementById("txtidproveedor").value;
                let alm = document.getElementById("cmbAlmacen").value;
                let valor = document.querySelector("#subtotal").value;
                let nigv = document.querySelector("#igv").value;
                let igv = obtenerTipoIGV();

                // "valor" => $request->get("valor"),
                // "nigv" => $request->get("nigv"),
                // "impo" => $request->get("impo"),

                data = new FormData();
                data.append("tdoc", tdoc);
                data.append("cndoc", cndoc);
                data.append("form", form);
                data.append("fechi", fechi);
                data.append("fechf", fechf);
                data.append("deta", deta);
                data.append("valor", valor);
                data.append("nigv", nigv);
                data.append("impo", impo);
                data.append("ndo2", ndo2);
                data.append("mon", mon);
                data.append("dolar", dolar);
                data.append("idprov", idprov);
                data.append("txtproveedor", $("#txtproveedor").val());
                data.append("pimpo", $("#txtpercepcion").val());
                data.append("cmbtipodocumentocuentasxpagar", $("#cmbtipodocumentocuentasxpagar").val());
                data.append("alm", alm);
                data.append("igv", igv);
                data.append("cuentasxpagar", JSON.stringify(detalle));
                data.append("actualizarprecios", $("#actualizarprecios").val());
                data.append("exonerado", $("#exonerado").val())
                axios.post("/compras/registrar", data)
                    .then(function(respuesta) {
                        const tabla = respuesta.data;
                        $('#detalle').html(tabla);
                        // $('#totalpedido').html(document.querySelector("#total").value);
                        // nropedido = document.querySelector("#nropedido").value;
                        cancelarCompra();
                        limpiardatos();
                        Swal.fire({
                            title: "Compra registrada",
                            text: "Se generó la compra correctamente",
                            icon: "success"
                        });
                    }).catch(function(error) {
                        if (error.hasOwnProperty("response")) {
                            if (error.response.status === 422) {
                                //mostrarErrores("formulario-agregar-presentacion", error.response.data.errors);
                                toastr.error(error.response.data.errors, 'Mensaje del sistema');
                            }
                        } else {
                            toastr.error("Error al registrar compra", "Mensaje del Sistema");
                        }
                    });
            }
        });
    }

    function obtenerDolar(fech) {
        const data = new FormData();
        axios.get('/dolar/obtenerdolar', {
            "params": {
                "fech": fech
            }
        }).then(function(respuesta) {
            const contenido_tabla = respuesta.data;
            $('#divdolar').html(contenido_tabla);
        }).catch(function(error) {
            toastr.error('Ocurrió un error' + error, 'Mensaje del sistema');
        });
    }

    function grabarCabecera() {
        let idprov = document.getElementById("txtidproveedor").value;
        let razo = document.getElementById("txtproveedor").value;
        let tdoc = document.getElementById("cmbdcto").value;
        let cndoc = document.querySelector("#cndoc1").value;
        let num = document.querySelector("#cndoc2").value;
        let ndo2 = document.querySelector("#ndo2").value;
        let form = document.getElementById("cmbforma").value;
        let deta = document.querySelector("#txtdetalle").value;
        let mone = document.getElementById("cmbmoneda").value;
        let fechi = document.getElementById("txtfechai").value;
        let fechf = document.getElementById("txtfechaf").value;
        let dolar = document.getElementById("txtdolar").value;
        let alm = document.getElementById("cmbAlmacen").value;
        var optigv = obtenerTipoIGV();
        data = new FormData();
        data.append("idprov", idprov);
        data.append("razo", razo);
        data.append("tdoc", tdoc);
        data.append("cndoc", cndoc);
        data.append("num", num);
        data.append("ndo2", ndo2);
        data.append("alm", alm);
        data.append("form", form);
        data.append("mone", mone);
        data.append("fechi", fechi);
        data.append("fechf", fechf);
        data.append("dolar", dolar);
        data.append("deta", deta);
        data.append("optigv", optigv);
        axios.post("/compras/sesion", data)
            .then(function(respuesta) {
                // console.log("Se registro la cabecera en la sesión")
            }).catch(function(error) {
                toastr.error("Error al guardar sesión", "Mensaje del Sistema");
            });
    }

    function calcularIGV() {
        igv = obtenerTipoIGV();
        var total_col = 0;
        $('#griddetalle tbody').find('tr').each(function(i, el) {
            columantotal = "<?php echo empty($_SESSION['config']['tipobotica']) ? 6 : 8; ?>";
            total_col += parseFloat($(this).find('td').eq(columantotal).find("input").val());
        });
        totalexon = 0;
        $('#griddetalle tbody tr').each(function() {
            _tr = $(this);
            columnaafecto = "<?php echo empty($_SESSION['config']['tipobotica']) ? 7 : 9; ?>";
            td = _tr.find("td").eq(columnaafecto).find("input");
            columantotal = "<?php echo empty($_SESSION['config']['tipobotica']) ? 6 : 8; ?>";
            var subtotal = _tr.find("td").eq(columantotal).find("input").val();
            var isChecked = $(td).is(":checked");
            if (isChecked) {
                totalexon = totalexon + Number(subtotal);
            }
        });
        $("#exonerado").val(Number(totalexon).toFixed(2))
        if (totalexon > 0) {
            total_col = total_col - totalexon;
        }
        if (igv == 'I') {
            //Si el IGV está incluido
            let impo = (Number(total_col)).toFixed(2);
            let valor = (impo / 1.18).toFixed(2);
            let nigv = (impo - valor).toFixed(2);
            $("#igv").val(nigv);
            $("#subtotal").val(valor);
            $("#total").val(impo);
        } else {
            //Si el IGV no está incluido
            impo = Number(total_col);
            $("#subtotal").val(impo.toFixed(2));
            valorigv = (impo * 0.18).toFixed(2);
            $("#igv").val(valorigv);
            // $("#igv").val("18");
            imponoigv = ((impo * 0.18) + impo);
            $("#total").val(imponoigv.toFixed(2));
        }
        if (totalexon > 0) {
            impo = total_col + totalexon;
            $("#total").val(impo.toFixed(2));
        }
        calcularpercepcion();
        let impor = $("#total").val();
        if (isNaN(impor)) {
            $("#subtotal").val("0.00");
            $("#igv").val("0.00");
            $("#total").val("0.00");
        }
        // calcularafecto();
    }

    function actualizar(cmensaje, actualizarprecios) {
        Swal.fire({
            title: cmensaje,
            text: "Se actualizará en el sistema ",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si'
        }).then(function(respuesta) {
            if (respuesta.isConfirmed) {
                let tdoc = document.getElementById("cmbdcto").value;
                let num = document.querySelector("#cndoc2").value
                if (num.length < 8) {
                    while (num.length < 8)
                        num = '0' + num;
                }
                let cndoc = (document.querySelector("#cndoc1").value + num).toUpperCase();
                let form = document.getElementById("cmbforma").value;
                let deta = document.querySelector("#txtdetalle").value;
                let impo = document.querySelector("#total").value;
                let ndo2 = document.querySelector("#ndo2").value;
                let mon = document.getElementById("cmbmoneda").value;
                let fechi = document.getElementById("txtfechai").value;
                let fechf = document.getElementById("txtfechaf").value;
                let dolar = document.getElementById("txtdolar").value;
                let idprov = document.getElementById("txtidproveedor").value;
                let alm = document.getElementById("cmbAlmacen").value;
                let valor = document.querySelector("#subtotal").value;
                let nigv = document.querySelector("#igv").value;
                let igv = obtenerTipoIGV();
                data = new FormData();
                data.append("tdoc", tdoc);
                data.append("cndoc", cndoc);
                data.append("form", form);
                data.append("fechi", fechi);
                data.append("fechf", fechf);
                data.append("deta", deta);
                data.append("valor", valor);
                data.append("nigv", nigv);
                data.append("impo", impo);
                data.append("ndo2", ndo2);
                data.append("mon", mon);
                data.append("dolar", dolar);
                data.append("idprov", idprov);
                data.append("txtproveedor", $("#txtproveedor").val());
                data.append("alm", alm);
                data.append("pimpo", $("#txtpercepcion").val());
                data.append("igv", igv);
                data.append("actualizarprecios", $("#actualizarprecios").val());
                data.append("exonerado", $("#exonerado").val())
                axios.post("/compras/actualizar", data)
                    .then(function(respuesta) {
                        const tabla = respuesta.data;
                        // console.log(data);
                        $('#detalle').html(tabla);
                        // $('#totalpedido').html(document.querySelector("#total").value);
                        // nropedido = document.querySelector("#nropedido").value;
                        cancelarCompra();
                        limpiardatos();
                        Swal.fire(' Se Actualizó la Compra satisfactoriamente ');
                    }).catch(function(error) {
                        if (error.hasOwnProperty("response")) {
                            if (error.response.status === 422) {
                                //mostrarErrores("formulario-agregar-presentacion", error.response.data.errors);
                                toastr.error(error.response.data.errors, 'Mensaje del sistema');
                            }
                        } else {
                            toastr.error("Error al registrar compra" + error, "Mensaje del sistema");
                        }
                    });
            }
        });
    }

    //Eventos
    var input = document.getElementById('cndoc2');
    input.addEventListener('input', function() {
        if (this.value.length > 8)
            this.value = this.value.slice(0, 8);
    })

    const hiddenInput = document.querySelector('#txtfechaf');
    document.querySelector('#txtfechai').addEventListener('change', (event) => {
        hiddenInput.value = event.target.value;
        $("#txtfechaf").val(hiddenInput.value);
    });

    var txtfecha = document.getElementById("txtfechai");
    txtfecha.addEventListener("blur", function(event) {
        fech = $("#txtfechai").val();
        grabarCabecera();
        obtenerDolar(fech)
    }, true);

    var txtfechaf = document.getElementById("txtfechaf");
    txtfechaf.addEventListener("blur", function(event) {
        grabarCabecera();
    }, true);

    var cndoc1 = document.getElementById("cndoc1");
    cndoc1.addEventListener("blur", function(event) {
        grabarCabecera();
    }, true);

    var cndoc2 = document.getElementById("cndoc2");
    cndoc2.addEventListener("blur", function(event) {
        grabarCabecera();
    }, true);

    var ndo2 = document.getElementById("ndo2");
    ndo2.addEventListener("blur", function(event) {
        grabarCabecera();
    }, true);

    // var deta = document.getElementById("detalle");
    // deta.addEventListener("blur", function(event) {
    //     grabarCabecera();
    // }, true);

    function actualizarProducto(o, i) {
        $(o).each(function() {
            var _tr = $(o);
            cmbpresentacion = _tr.find("td").eq(3).find("select").val();
            cmbpresentacion = cmbpresentacion.split("-");
            textpresentacion = _tr.find("td").eq(3).find("select option:selected").text();
            textpresentacion = textpresentacion.split("-");
            const data = new FormData();
            var id = _tr.find("td").eq(1).html();
            data.append("txtprecio", _tr.find("td").eq(5).find("input").val());
            data.append("txtcantidad", _tr.find("td").eq(4).find("input").val());
            data.append("unidad", (textpresentacion[0]).trim());
            data.append("presseleccionada", cmbpresentacion[0])
            data.append("cantequi", textpresentacion[1]);
            tipobotica = "<?php echo empty($_SESSION['config']['tipobotica']) ? 'N'  : 'S'; ?>";
            if (tipobotica == 'S') {
                data.append("lote", _tr.find("td").eq(6).find("input").val());
                data.append("fechavto", _tr.find("td").eq(7).find("input").val());
            }
            data.append("indice", i);
            axios.post('/compras/EditarUno', data)
                .then(function(respuesta) {}).catch(function(error) {
                    if (error.hasOwnProperty("response")) {
                        if (error.response.status === 422) {
                            console.log(error);
                        }
                    }
                });
        });
    }

    function cambiarpresentacion(o, i) {
        row = $(o).parent().parent();
        $(row).each(function() {
            var _tr = $(row);
            cmbpresentacion = _tr.find("td").eq(3).find("select").val();
            cmbpresentacion = cmbpresentacion.split("-");
            textpresentacion = _tr.find("td").eq(3).find("select option:selected").text();
            textpresentacion = textpresentacion.split("-");
            _tr.find("td").eq(5).find("input").val(Number(cmbpresentacion[1]).toFixed(2));
            const data = new FormData();
            var id = _tr.find("td").eq(1).html();
            data.append("txtcantidad", _tr.find("td").eq(4).find("input").val());
            data.append("txtprecio", _tr.find("td").eq(5).find("input").val());
            data.append("unidad", (textpresentacion[0]).trim());
            data.append("cantequi", textpresentacion[1]);
            data.append("presseleccionada", cmbpresentacion[0])
            tipobotica = "<?php echo empty($_SESSION['config']['tipobotica']) ? 'N'  : 'S'; ?>";
            if (tipobotica == 'S') {
                data.append("lote", _tr.find("td").eq(6).find("input").val());
                data.append("fechavto", _tr.find("td").eq(7).find("input").val());
            }
            data.append("indice", i);
            axios.post('/compras/EditarUno', data)
                .then(function(respuesta) {
                    calcularIGV();
                    calcularsubtotal(row);
                }).catch(function(error) {
                    if (error.hasOwnProperty("response")) {
                        if (error.response.status == 422) {
                            console.log(error);
                        }
                    }
                });
        });
    }

    function funcionEnterCant(o, i) {
        //Eliminamos los id anteriores
        var id1 = document.getElementById("1");
        $(id1).removeAttr('id', '1');
        // var id2 = document.getElementById("2");
        // $(id2).removeAttr('id', '2');
        var id3 = document.getElementById("3");
        $(id3).removeAttr('id', '3');

        //Obtenemos la celda cant y le asignamos un id
        cant = $(o).find("input");
        $(cant).attr('id', '1');
        $("#1").select();

        //Obtenemos la celda precios y precio, a ambos le asignamos un id
        var tr = $(o).parent();
        // tr.find("td").eq(5).attr('id', '2');
        tr.find("td").eq(5).find("input").attr('id', '3');
        //Buscamos lo que hay dentro de la celda precios
        // var p = document.getElementById('precios_' + i);

        //Obtenemos la celda cantidad con función enter
        var cant = document.getElementById("1");
        cant.addEventListener("keypress", function(event) {
            if (event.key === "Enter") {
                event.preventDefault();
                // $('#1').removeClass('focus');
                // $('#1').removeAttr('contenteditable');
                // $('#3').focus();
                tr.find("td").eq(4).removeClass('focus');
                $("#3").select();
            }
        });
        // var preci = document.getElementById("precios_" + i);
        // $('body').on('keydown', preci, function(e) {
        //     if (e.which == 9) {
        //         e.preventDefault();
        //         $('#precios_' + i).blur();
        //         $('#3').addClass('focus');
        //         $('#3').focus();
        //     }
        // });
        var prec = document.getElementById("3");
        prec.addEventListener("keypress", function(event) {
            if (event.key === "Enter") {
                event.preventDefault();
                // $('#3').removeClass('focus');
                // $('#3').removeAttr('contenteditable');
                // $('#body').trigger('click');
                tr.find("td").eq(5).removeClass('focus');
                $("#3").blur();
                // $('#body').trigger('click');
                tr.next('tr').find("td:nth-child(5) input").click();
            }
        });
    }

    // Evento enter con precio
    $("table tbody tr td:nth-child(6) input").click(function() {
        var id = document.getElementById("3");
        $(id).removeAttr('id', '3')
        tr = $(this).closest('tr');
        $(this).attr('id', '3')
        $(this).select()
        var prec = document.getElementById("3");
        prec.addEventListener("keypress", function(event) {
            if (event.key === "Enter") {
                event.preventDefault();
                // $('#3').removeClass('focus');
                // $('#3').removeAttr('contenteditable');
                // $('#body').trigger('click');
                tr.find("td").eq(5).removeClass('focus');
                $("#3").blur();
                // $('#body').trigger('click');
                tr.next('tr').find("td:nth-child(5) input").click();
            }
        });
    });

    //Calculamos en el subtotal y total
    // function calcularsubtotal(o) {
    //     var _tr = $(o);
    //     var cant = _tr.find("td").eq(4).find("input").val();
    //     var prec = _tr.find("td").eq(5).find("input").val();
    //     var subt = parseFloat(cant) * parseFloat(prec);
    //     var campo = _tr.find("td").eq(6);
    //     if (isNaN(subt)) {
    //         toastr.info("Dígite un número correcto")
    //     } else {
    //         campo.html(subt.toFixed(2));
    //         var total_col1 = 0;
    //         $('#griddetalle tbody').find('tr').each(function(i, el) {
    //             //Voy incrementando las variables segun la fila ( .eq(0) representa la fila 1 )     
    //             total_col1 += parseFloat($(this).find('td').eq(6).text());
    //             calcularIGV();
    //             // $('#totalpedido').text(total_col1.toFixed(2));
    //         });
    //     }
    // }
    //Calculamos en el subtotal y total
    function calcularsubtotal(o) {
        var _tr = $(o);
        var cant = _tr.find("td").eq(4).find("input").val();
        var prec = _tr.find("td").eq(5).find("input").val();
        columantotal = "<?php echo empty($_SESSION['config']['tipobotica']) ? 6 : 8; ?>";
        var campo = _tr.find("td").eq(columantotal).find("input");

        if (clicksubtotal == 0) {
            var subt = parseFloat(cant) * parseFloat(prec);
            if (isNaN(subt)) {
                toastr.info("Dígite un número correcto")
            } else {
                $(campo).val(subt.toFixed(2));
                $('#griddetalle tbody').find('tr').each(function(i, el) {
                    calcularIGV();
                });
            }
        } else {
            campo = $(campo).val();
            prec = campo / cant;
            _tr.find("td").eq(5).find("input").val(Number(prec).toFixed(5));
            $('#griddetalle tbody').find('tr').each(function(i, el) {
                calcularIGV();
            });
        }
    }

    //Funcionamiento del combobox
    function obtenerPrecio(o, i) {
        $(o).each(function() {
            var precios = $(this).find("#precios_" + i).val();
            $(this).find(".precio").text(precios);
        });
        calcularsubtotal(o);
        actualizarProducto(o, i);
    }

    $('#cbpercepcion').change(function() {
        calcularpercepcion()
    });
</script>
<?php
$this->endSection("javascript");
?>