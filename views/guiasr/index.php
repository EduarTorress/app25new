<?php
$this->setLayout('layouts/admin');
?>
<?php
$this->startSection('contenido');
?>
<?php

use App\View\Components\ModalDestinatarioComponent;
use App\View\Components\ModalDirecciones;
use App\View\Components\ModalImprimir;
use App\View\Components\ModalProductoComponent;
use App\View\Components\ModalTransportistaComponent;
use App\View\Components\UbigeosComponent;
?>
<?php
$clie = new ModalDestinatarioComponent();
echo $clie->render();
$ve = new ModalTransportistaComponent();
echo $ve->render();
$odirecciones = new ModalDirecciones();
echo $odirecciones->render();
$prod = new ModalProductoComponent();
echo $prod->render();
$oimp = new ModalImprimir();
echo $oimp->render();
?>
<div class="content-wrapper">
    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <div class="row row-cols-1 row-cols-md-3 g-4">
                        <div class="col">
                            <div class="form-group row">
                                <label class="col-sm-0 col-form-label ">Fecha de emisión :</label>
                                <input type="date" id="txtFechaEmision" class="form-control " value="<?php echo isset($_SESSION['guiar']['fechaEmision']) ?  $_SESSION['guiar']['fechaEmision'] : date("Y-m-d") ?>" style="width:40%;">
                                <input type="hidden" id="txtnumerodocumento" value="<?php echo isset($_SESSION['guiar']['txtnumerodocumento']) ?  $_SESSION['guiar']['txtnumerodocumento'] : '' ?>">
                                <input type="hidden" name="" id="txtidautocanje" value="<?php echo (empty($guiaidau) ? '0' : $guiaidau); ?>">
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group row">
                                <label class="col-sm-0 col-form-label">Fecha de traslado:</label>
                                <input type="date" id="txtFechaTraslado" class="form-control " value="<?php echo isset($_SESSION['guiar']['fechaTraslado']) ?  $_SESSION['guiar']['fechaTraslado'] : date("Y-m-d") ?>" style="width:45%;">
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group row">
                                <label class="col-sm-0 col-form-label">Partida:</label>
                                <input type="text" class="form-control " id="txtDireccionRemitente" style="width:80%" value="<?php echo isset($_SESSION['guiar']['direccionRemitente']) ?  $_SESSION['guiar']['direccionRemitente'] : session()->get("gene_ptop") ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group row">
                                <label class="col-sm-0 col-form-label">Referencia:</label>
                                <input type="text" class="form-control" placeholder="Ingrese referencia" maxlength="200" id="txtReferencia" style="width:40%" value="<?php echo isset($_SESSION['guiar']['txtReferencia']) ?  $_SESSION['guiar']['txtReferencia'] : '' ?>">
                                <div class="col">
                                    <?php if (empty($_SESSION['guiar']['txtIdauto'])) : ?>
                                        <button class="btn btn-success" onclick="modalvtas();">Canjear Venta</button>
                                    <?php endif; ?>
                                    <input type="hidden" name="idautov" id="idautov" value="0">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        Destinatario
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <div class="input-group">
                                    <input type="hidden" id="txtIdauto" value="<?php echo isset($_SESSION['guiar']['txtIdauto']) ?  $_SESSION['guiar']['txtIdauto'] : '' ?>">
                                    <input type="hidden" id="txtIdDestinatario" value="<?php echo isset($_SESSION['destinatario']['idDestinatario']) ?  $_SESSION['destinatario']['idDestinatario'] : '' ?>">
                                    <input type="hidden" id="txtUbigeoDestinatario" value="<?php echo isset($_SESSION['destinatario']['ubigDestinatario']) ?  $_SESSION['destinatario']['ubigDestinatario'] : '' ?>">
                                    <input type="hidden" id="txtrucDestinatario" value="<?php echo isset($_SESSION['destinatario']['rucDestinatario']) ?  $_SESSION['destinatario']['rucgDestinatario'] : '' ?>">
                                    <input type="text" class="form-control form-control-sm" id="txtNombreDestinatario" placeholder="Destinatario" disabled value="<?php echo isset($_SESSION['destinatario']['nombre']) ?  $_SESSION['destinatario']['nombre'] : '' ?>">
                                    <button class="btn btn-outline-light" role="button" data-bs-toggle="modal" data-bs-target="#modal_destinatario"><i style="color:black" class="fas fa-user-alt"></i></button>
                                </div>
                                <div class="input-group">
                                    <input type="text" class="form-control form-control-sm" placeholder="Dirección de LLegada" id="txtDireccionDestinatario" disabled value="<?php echo isset($_SESSION['destinatario']['destinatarioDireccion']) ?  $_SESSION['destinatario']['destinatarioDireccion'] : '' ?>">
                                    <button class="btn btn-outline-light" role="button" data-toggle="modal" data-target=""><i style="color:black" class="fa fa-arrow-circle-o-right" aria-hidden="true"></i></button>
                                </div>
                                <div>
                                    <?php
                                    $ubigeo = isset($destinatario['ubigDestinatario']) ? $destinatario['ubigDestinatario'] : '';
                                    $estado = isset($destinatario['ubigDestinatario']) ? 'A' : '';
                                    $oubg = new UbigeosComponent($estado, $ubigeo);
                                    echo $oubg->render();
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            Transportista
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <div class="input-group">
                                        <input type="hidden" id="txtIdTransportista" value="<?php echo isset($_SESSION['transportista']['txtIdTransportista']) ?  $_SESSION['transportista']['txtIdTransportista'] : '' ?>">
                                        <input type="text" class="form-control form-control-sm" id="txttransportista" placeholder="Transportista" disabled value="<?php echo isset($_SESSION['transportista']['txttransportista']) ?  $_SESSION['transportista']['txttransportista'] : '' ?>">
                                        <input type="text" class="form-control form-control-sm" id="txttruc" placeholder="Ruc" disabled value="<?php echo isset($_SESSION['transportista']['txtruc']) ?  $_SESSION['transportista']['txtruc'] : '' ?>">
                                        <button class="btn btn-outline-light" role="button" data-bs-toggle="modal" data-bs-target="#modal_transportista"><i style="color:black" class="fas fa-user-alt"></i></button>
                                    </div>
                                    <div class="input-group">
                                        <input type="text" class="form-control form-control-sm" id="txtplaca" placeholder="Placa" disabled value="<?php echo isset($_SESSION['transportista']['txtPlaca']) ?  $_SESSION['transportista']['txtPlaca'] : '' ?>">
                                        <input type="text" class="form-control form-control-sm" disabled id="txtPlaca1" value="<?php echo isset($_SESSION['transportista']['txtPlaca1']) ?  $_SESSION['transportista']['txtPlaca1'] : '' ?>" placeholder="Placa Carreta">
                                        <input type="text" class="form-control form-control-sm" id="txtmarca" placeholder="Marca" disabled value="<?php echo isset($_SESSION['transportista']['txtmarca']) ?  $_SESSION['transportista']['txtmarca'] : '' ?>">
                                        <button class="btn btn-outline-light" role="button" data-toggle="modal" data-target=""><i style="color:black" class="fa fa-truck"></i></button>
                                    </div>
                                    <div class="input-group">
                                        <input type="text" class="form-control form-control-sm" id="txtChoferVehiculo" placeholder="Chofer" disabled value="<?php echo isset($_SESSION['transportista']['txtChoferVehiculo']) ?  $_SESSION['transportista']['txtChoferVehiculo'] : '' ?>">
                                        <input type="text" class="form-control form-control-sm" id="txtbrevete" placeholder="Brevete" disabled value="<?php echo isset($_SESSION['transportista']['txtbrevete']) ?  $_SESSION['transportista']['txtbrevete'] : '' ?>">
                                        <button class="btn btn-outline-light" role="button" data-toggle="modal" data-target="#modal_choferes"><i style="color:black" class="fa fa-id-card-o" aria-hidden="true"></i></button>
                                    </div>
                                    <div class="input-group">
                                        <input type="text" class="form-control form-control-sm" id="txtregmtc" placeholder="Registro MTC" disabled value="<?php echo isset($_SESSION['transportista']['txtregmtc']) ?  $_SESSION['transportista']['txtregmtc'] : '' ?>">
                                        <input type="text" class="form-control form-control-sm" id="txttipot" placeholder="Tipo de Transporte" disabled value="<?php echo isset($_SESSION['transportista']['txttipot']) ?  $_SESSION['transportista']['txttipot'] : '' ?>">
                                        <button class="btn btn-outline-light" role="button" data-bs-toggle="modal" data-bs-target=""><i style="color:black" class="fa fa-truck"></i></button>
                                    </div>
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
                                            <div class="table-responsive">
                                                <table class="table table-sm small table table-hover" id="griddetalle">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col" style="width:2%">Opciones</th>
                                                            <th scope="col" style="width:3%;" class="codigo">Código</th>
                                                            <th scope="col" style="width:28%">Producto</th>
                                                            <th scope="col" style="width:5%">U.M.</th>
                                                            <th scope="col" class="text-center" style="width:5%">Cantidad</th>
                                                            <th scope="col" class="text-center" style="width:5%">Peso</th>
                                                            <th scope="col" class="text-center" style="width:5%">SCOP</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="carritoventas">
                                                        <?php $i = 0; ?>
                                                        <?php foreach ($carritov as $indice => $item) : ?>
                                                            <?php if ($item['activo'] == 'A') { ?>
                                                                <tr onkeyup="verificarValores(this); actualizarProducto(this,<?php echo $indice ?>);" onblur="actualizarProducto(this,<?php echo $indice ?>);">
                                                                    <?php
                                                                    $parametro1 = $item['descri'];
                                                                    $parametro2 = $item['coda'];
                                                                    $parametro3 = $item['unidad'];
                                                                    $parametro4 = $item['stock'];
                                                                    $parametro5 = $item['precio1'];
                                                                    $parametro6 = $item['precio2'];
                                                                    $parametro7 = $item['precio3'];
                                                                    $parametro8 = $item['costo'];
                                                                    $parametro9 = $item['cantidad'];
                                                                    $parametro10 = 1;
                                                                    $parametro11 = $indice;
                                                                    $parametros = compact('parametro1', 'parametro2', 'parametro3', 'parametro4', 'parametro5', 'parametro6', 'parametro8', 'parametro9', 'parametro10', 'parametro11');
                                                                    $cadena_json = json_encode($parametros);
                                                                    ?>
                                                                    <td class=""><button class="btn btn-warning" onclick="quitaritem(<?php echo $indice ?>)"><a style="color:white" class="fas fa-trash-alt"> </a></button>
                                                                        <!-- <button class="btn btn-success" onclick='editaritem(<?php echo $cadena_json ?>);'><a style="color:white" class="fas fa-edit"></a></button> -->
                                                                    </td>
                                                                    <td class="codigo"><?php echo $item['coda'] ?></td>
                                                                    <td class="descri"><?php echo $item['descri'] ?></td>
                                                                    <td class="unidad">
                                                                        <?php $presentaciones = json_decode($item['presentaciones'], true); ?>
                                                                        <select onchange="cambiarpresentacion(this,<?php echo $indice ?>)" class="form-control form-control-sm" name="cmbpresentaciones" id="cmbpresentaciones">
                                                                            <?php foreach ($presentaciones as $p) : ?>
                                                                                <option value="<?php echo $p['epta_idep'] . '-' . $p['epta_prec'] ?>" <?php echo ((trim($p['epta_idep']) == trim($item['presseleccionada'])) ? 'selected' : '') ?>>
                                                                                    <?php echo trim($p['pres_desc']) . '-' . $p['epta_cant'] . '-' . $p['epta_idep']; ?>
                                                                                </option>
                                                                            <?php endforeach;
                                                                            ?>
                                                                        </select>
                                                                    </td>
                                                                    <td class="cantidad" style="text-align: center;" onclick="funcionEnterCant(this,<?php echo $indice ?>)" onkeypress="return isNumber(event);" contenteditable="true" name="cantidad"><?php echo round($item['cantidad'], 4) ?></td>
                                                                    <td class="precio" id="precio" style="text-align: center;" onkeypress="return isNumber(event);" contenteditable="false" name="precio"><?php echo round($item['peso'], 5) ?></td>
                                                                    <td class="scop" style="text-align: center;" id="scop" contenteditable="true" name="scop"><?php echo $item['scop'] ?></td>
                                                                    <!-- <td class="text-center" class="total"><?php echo round($item['cantidad'] * $item['peso'], 2) ?></td> -->
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
                                                            <br>
                                                            <div class="input-group">
                                                                <!-- "/productos/index/3" para index de productos de ventas  -->
                                                                <button class="btn btn-primary btn-sm" role="button" data-bs-toggle="modal" data-bs-target="#modal_productos">Agregar</button>
                                                                <button class="btn btn-danger btn-sm" id="cancelar" role="button" onclick="limpiarTodo()">Limpiar</button>
                                                                <button class="btn btn-success btn-sm" id="grabar" role="button" onclick="Guia();"><?php echo (isset($btn) ? $btn : 'Grabar') ?></button>
                                                            </div>
                                                        </div>
                                                        <div class="col-2 align-items-start">
                                                            <div class="input-group mb-3" style="width: 85%;">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text text-sm" id=""><strong>Items:</strong></span>
                                                                </div>
                                                                <input type="text" class="form-control text-right text-sm" id="totalitems" aria-label="Small" value="<?php echo $i; ?>" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="col-3 align-items-start">
                                                            <div class="input-group" style="width: 90%;">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text text-sm" id=""><strong>Peso:</strong></span>
                                                                </div>
                                                                <input type="text" class="form-control text-right text-sm" id="total" aria-label="Small" value="<?php echo  $total ?>" readonly>
                                                                <input type="text" style="display:none" class="form-control text-right text-sm" id="numeroDocumento" aria-label="Small" value="<?php echo isset($numeroDocumento) ?  $numeroDocumento : '' ?>" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="cargamodal">
</div>
<div id="cargamodalvtas"></div>
<style>
    td>input {
        height: 20px;
    }

    iframe {
        width: 100%;
    }
</style>
<?php
$this->endSection('contenido');
?>
<?php
$this->startSection('javascript');
?>
<script>
    window.onload = function() {
        titulo("<?php echo $titulo ?>");
        calcularPesoTotal();
        $(".codigo").css("display", "none");
    }

    $("#modal_transportista").on("hidden.bs.modal", function() {
        axios.get('/transportista/listarChoferes', {})
            .then(function(respuesta) {
                const contenido_tabla = respuesta.data;
                $('#cargamodal').html(contenido_tabla);
            }).catch(function(error) {
                toastr.error('Error al cargar el listado')
            });
    });

    function modalvtas() {
        axios.get('/guiasr/listarvtastocanje', {})
            .then(function(respuesta) {
                const contenido_tabla = respuesta.data;
                $('#cargamodalvtas').html(contenido_tabla);
                $("#modal_ventas").modal('show');
            }).catch(function(error) {
                toastr.error('Error al cargar el listado')
            });
    }

    $('#cmbUbigeo').selectpicker();

    function agregarunitemVenta(datos) {
        presentaciones = JSON.parse(datos.parametro11);
        precio = presentaciones[0]['epta_prec'];
        unidad = presentaciones[0]['pres_desc']
        cantequi = presentaciones[0]['epta_cant'];
        eptaidep = presentaciones[0]['epta_idep'];
        stock = parseFloat(datos.parametro4.toFixed(2))
        const data = new FormData();
        data.append('txtcodigo', datos.parametro2);
        data.append("txtdescripcion", datos.parametro1);
        // data.append("txtunidad", datos.parametro3);
        data.append("txtunidad", unidad);
        data.append("txtpeso", datos.parametro9);
        data.append("txtcantidad", 1);
        data.append("precio1", datos.parametro5);
        data.append("precio2", datos.parametro6);
        data.append("precio3", datos.parametro7);
        data.append("presentaciones", datos.parametro11);
        data.append("presseleccionada", eptaidep);
        data.append("cantequi", cantequi);
        data.append("stock", parseFloat(datos.parametro4.toFixed(2)));
        data.append("opt", 0)
        //console.log(parseFloat(datos.parametro4.toFixed(2)))
        axios.post('/guiasr/agregaritem', data)
            .then(function(respuesta) {
                //window.location.href = '/vtas/index';
                // hidemodal("#modal_productos")
                $('#modal_productos').modal('hide')
                const contenido_tabla = respuesta.data;
                $('#detalle').html(contenido_tabla);
                calcularIGV();
                //$("#griddetalle tr:last").focus()
                var a = $("#griddetalle tr:last td:eq(4)").each(function() {
                    console.log($(this).text());
                    $(this).focus();
                    $(this).click();
                });
            }).catch(function(error) {
                if (error.hasOwnProperty("response")) {
                    if (error.response.status === 422) {
                        toastr.error(error.response.data.errors, "Mensaje del Sistema");
                    }
                }
            });
    }

    function quitaritem(pos) {
        const data = new FormData();
        data.append("indice", pos)
        axios.post('/guiasr/quitaritem', data)
            .then(function(respuesta) {
                const contenido_tabla = respuesta.data;
                $('#detalle').html(contenido_tabla);
            }).catch(function(error) {
                toastr.error(error, 'Mensaje del sistema');
            });
    }

    $('#modal_destinatario').on('shown.bs.modal', function() {
        $('#txtbuscar').focus();
    });

    $('#modal_transportista').on('shown.bs.modal', function() {
        $('#txtbuscarTr').focus();
    });

    $('#modal_productos').on('shown.bs.modal', function() {
        $('#txtbuscarProducto').focus();
    });

    function calcularPesoTotal() {
        var cantidades = [];
        var pesos = [];
        var pesoTotal = [];
        var total = 0;
        $("#tabla tbody > tr").each(function(index) {
            var cantidad = Number($(this).find('.cantidad').val());
            cantidades.push(cantidad);
            var peso = Number($(this).find('.peso').val());
            var pesot = cantidad * peso;
            pesoTotal.push(pesot);
            total += pesot;
        });
        if (!isNaN(total)) {
            $("#txttpeso").text(total.toFixed(2));
        } else {
            $("#txttpeso").text("0.00");
        }
    }

    function Guia() {
        txtidauto = $("#txtIdauto").val();
        if (txtidauto == '') {
            grabarGuia();
        } else {
            modificarGuia();
        }
    }

    function grabarGuia() {
        if (validar() == false) {
            return;
        }
        Swal.fire({
            title: '¿Registrar Guia de Remisión?',
            text: "Se guardará en el sistema ",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si'
        }).then(function(respuesta) {
            if (respuesta.isConfirmed) {
                data = new FormData();
                data.append("idDestinatario", $("#txtIdDestinatario").val());
                data.append("idtransportista", $("#txtIdTransportista").val());
                data.append("txtNombreDestinatario", $("#txtNombreDestinatario").val());
                data.append("txtrucDestinatario", $("#txtrucDestinatario").val());
                data.append("txtDireccionRemitente", $("#txtDireccionRemitente").val());
                data.append("txtDireccionDestinatario", $("#txtDireccionDestinatario").val());
                data.append("txtubigeod", $("#cmbUbigeo").val());
                data.append("txtPlaca1", "");
                data.append("txtPlaca", $("#txtplaca").val());
                data.append("txtBrevete", $("#txtbrevete").val());
                data.append("txtReferencia", $("#txtReferencia").val());
                data.append("txtIdTransportista", $("#txtIdTransportista").val());
                data.append("txtChoferVehiculo", $("#txtChoferVehiculo").val());
                data.append("txtFechaEmision", $("#txtFechaEmision").val());
                data.append("txtFechaTraslado", $("#txtFechaTraslado").val());
                data.append("txttruc", $("#txttruc").val());
                data.append("txtmarca", $("#txtmarca").val());
                data.append("txttransportista", $("#txttransportista").val());
                data.append("txtmarca", $("#txtmarca").val());
                data.append("idautov", $("#idautov").val());
                axios.post("/guiasr/registrar", data)
                    .then(function(respuesta) {
                        toastr.success(respuesta.data.mensaje.trimEnd() + ' ' + respuesta.data.ndoc);
                        var cruta = '/guiasr/imprimirdirecto/';
                        var xhr = new XMLHttpRequest();
                        xhr.open('GET', cruta, true);
                        xhr.responseType = 'blob';
                        xhr.onload = function(e) {
                            if (this.status == 200) {
                                var w = screen.width;
                                url = location.protocol + '//' + document.domain + '/descargas/' + respuesta.data.ndoc + ".pdf"
                                // console.log(w)
                                if (w <= 768) {
                                    var req = new XMLHttpRequest();
                                    req.open("GET", url, true);
                                    req.responseType = "blob";
                                    req.onload = function(event) {
                                        var blob = req.response;
                                        // console.log(blob.size);
                                        var link = document.createElement('a');
                                        link.href = window.URL.createObjectURL(blob);
                                        link.download = respuesta.data.ndoc + ".pdf"
                                        link.click();
                                    };
                                    req.send();
                                } else {
                                    $("#pdfguia").attr("src", url)
                                    $("#abrirguia").click();
                                }
                            }
                        };
                        xhr.send();
                        limpiarGuia();
                    }).catch(function(error) {
                        console.log(error)
                        e = error['response']['data']['errors']
                        result = []
                        for (var i in e) {
                            result.push([i, e[i]]);
                        }
                        result.forEach(function(numero) {
                            toastr.error(numero[1], 'Mensaje del sistema')
                        });
                    });
            }
        });
    }

    function modificarGuia() {
        idautocanje = $("#txtidautocanje").val();
        if (Number(idautocanje) > 0) {
            toastr.error("La guía no puede ser modificada, porque ya fue canjeada");
            return;
        }
        calcularPesoTotal();
        const detalle = []
        $("#griddetalle tbody tr").each(function() {
            json = "";
            $(this).find("td:not(.dtr-control)").each(function() {
                $this = $(this);
                if ($this.attr("class") == 'unidad') {
                    json += ',"' + $this.attr("class") + '":"' + $this.find("#cmbpresentaciones option:selected").text().trim() + '"';
                } else {
                    json += ',"' + $this.attr("class") + '":"' + $this.text().trim() + '"';
                }
            })
            obj = JSON.parse('{' + json.substr(1) + '}');
            detalle.push(obj)
        });
        if (validar() === false) {
            toastr.error("Faltan datos para modificar");
            return;
        }
        Swal.fire({
            title: '¿Modificar Guia de Remisión ?',
            text: "Se actualizará en el sistema.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si'
        }).then(function(respuesta) {
            if (respuesta.isConfirmed) {
                data = new FormData();
                data.append("idDestinatario", $("#txtIdDestinatario").val());
                data.append("idtransportista", $("#txtIdTransportista").val());
                data.append("txtNombreDestinatario", $("#txtNombreDestinatario").val());
                data.append("txtDireccionRemitente", $("#txtDireccionRemitente").val());
                data.append("txtDireccionDestinatario", $("#txtDireccionDestinatario").val());
                data.append("txtubigeod", $("#cmbUbigeo").val());
                data.append("txtPlaca1", "");
                data.append("txtPlaca", $("#txtplaca").val());
                data.append("txtBrevete", $("#txtbrevete").val());
                data.append("txtReferencia", $("#txtReferencia").val());
                data.append("txtIdTransportista", $("#txtIdTransportista").val());
                data.append("txtChoferVehiculo", $("#txtChoferVehiculo").val());
                data.append("txtIdauto", txtIdauto);
                data.append("txtFechaEmision", $("#txtFechaEmision").val());
                data.append("txtFechaTraslado", $("#txtFechaTraslado").val());
                data.append("txtnumerodocumento", $("#txtnumerodocumento").val());
                data.append("detalle", JSON.stringify(detalle));
                axios.post("/guiasr/modificar", data)
                    .then(function(respuesta) {
                        toastr.success(respuesta.data.mensaje.trimEnd() + ' ' + respuesta.data.ndoc);
                        window.location.href = '/guiasr/index';
                        limpiarGuia();
                    }).catch(function(error) {
                        console.log(error)
                        num = error['response']['data']
                        if (num.length == 1) {
                            e = error['response']['data']
                        } else {
                            e = error['response']['data']['errors']
                        }
                        result = []
                        for (var i in e) {
                            result.push([i, e[i]]);
                        }
                        result.forEach(function(numero) {
                            toastr.error(numero[1], 'Mensaje del sistema')
                        });
                    });
            }
        });
    }

    function validar() {
        idDestinatario = $("#txtIdDestinatario").val();
        if (idDestinatario == "") {
            toastr.info("Ingrese el Destinatario")
            return false;
        }
        return true
    }

    function limpiarGuia() {
        limpiardetallegr();
        <?php
        session()->remove('destinatario');
        session()->remove('transportista');
        session()->remove('guiar');
        session()->remove('carritogr');
        ?>
        $("#idautov").val("0");
        document.getElementById('txtIdDestinatario').value = "";
        document.getElementById("txtIdTransportista").value = "";
        document.getElementById("txtNombreDestinatario").value = "";
        document.getElementById("txtDireccionDestinatario").value = "";
        document.getElementById("cmbUbigeo").value = "";
        document.getElementById("txtplaca").value = "";
        $("#txtPlaca1").val("");
        document.getElementById("txtbrevete").value = "";
        document.getElementById("txtChoferVehiculo").value = "";
        document.getElementById("txtIdauto").value = "";
        document.getElementById("txttransportista").value = "";
        document.getElementById("txttruc").value = "";
        document.getElementById("txtmarca").value = "";
        document.getElementById("txtregmtc").value = "";
        document.getElementById("txttipot").value = "";
        document.getElementById("totalitems").value = "0";
        document.getElementById("total").value = "0.00";
        $("#griddetalle tbody tr").remove();
        // window.location.href = '/guiasr/index';
    }

    function limpiarTodo() {
        <?php
        $_SESSION['transportista'] = [];
        session()->remove('destinatario');
        session()->remove('transportista');
        session()->remove('guiar');
        ?>
        txtidauto = $("#txtIdauto").val();
        if (txtidauto != '') {
            window.location.href = '/guiasr/index';
        }
        $("#idautov").val("0");
        document.getElementById('txtIdDestinatario').value = "";
        document.getElementById("txtIdTransportista").value = "";
        document.getElementById("txtNombreDestinatario").value = "";
        document.getElementById("txtDireccionDestinatario").value = "";
        document.getElementById("cmbUbigeo").value = "";
        document.getElementById("txtplaca").value = "";
        document.getElementById("txtbrevete").value = "";
        document.getElementById("txtChoferVehiculo").value = "";
        document.getElementById("txtIdauto").value = "";
        document.getElementById("txttransportista").value = "";
        document.getElementById("txttruc").value = "";
        document.getElementById("txtmarca").value = "";
        $("#txtPlaca1").val("");
        document.getElementById("txtregmtc").value = "";
        document.getElementById("txttipot").value = "";
        document.getElementById("totalitems").value = "00";
        document.getElementById("total").value = "0.00";
        $("#griddetalle tbody tr").remove();
        limpiardetallegr();
        // window.location.href = '/guiasr/index';
    }

    function limpiardetallegr() {
        axios.get('/guiasr/limpiar', {})
            .then(function(respuesta) {
                const contenido_tabla = respuesta.data;
                $('#detalle').html(contenido_tabla);
                // window.location.href = '/guiasr/index';
            }).catch(function(error) {
                toastr.error('Error al cargar el listado' + error, 'Mensaje del sistema')
            });
    }

    /////
    $(document).ready(function() {
        $(".codigo").css("display", "none");
    });

    //Poner editable luego de quitar el focus a los campos.
    $("#body").on('click', function() {
        $('#1').attr('contenteditable', 'true');
        // $('#2').attr('contenteditable', 'true');
        $('#3').attr('contenteditable', 'true');
    });

    // function actualizarProducto(o, i) {
    //     $(o).each(function() {
    //         var _tr = $(o);
    //         cmbpresentacion = _tr.find("td").eq(3).find("select").val();
    //         cmbpresentacion = cmbpresentacion.split("-");
    //         textpresentacion = _tr.find("td").eq(3).find("select option:selected").text();
    //         textpresentacion = textpresentacion.split("-");
    //         const data = new FormData();
    //         var id = _tr.find("td").eq(1).html();
    //         data.append("txtpeso", _tr.find("td").eq(5).html());
    //         data.append("txtcantidad", _tr.find("td").eq(4).html());
    //         data.append("txtscop", _tr.find("td").eq(6).html());
    //         data.append("presseleccionada", cmbpresentacion[0]);
    //         data.append("unidad", textpresentacion[0].trim());
    //         data.append("cantequi", textpresentacion[1]);
    //         data.append("indice", i);
    //         axios.post('/guiasr/EditarUno', data)
    //             .then(function(respuesta) {}).catch(function(error) {
    //                 if (error.hasOwnProperty("response")) {
    //                     if (error.response.status == 422) {
    //                         console.log(error);
    //                     }
    //                 }
    //             });
    //     });
    // }

    // function cambiarpresentacion(o, i) {
    //     row = $(o).parent().parent().parent();
    //     $(row).each(function() {
    //         var _tr = $(row);
    //         cmbpresentacion = _tr.find("td").eq(3).find("select").val();
    //         cmbpresentacion = cmbpresentacion.split("-");
    //         textpresentacion = _tr.find("td").eq(3).find("select option:selected").text();
    //         textpresentacion = textpresentacion.split("-");
    //         // _tr.find("td").eq(5).find("input").val(Number(cmbpresentacion[1]).toFixed(2));
    //         const data = new FormData();
    //         var id = _tr.find("td").eq(1).html();
    //         data.append("txtcantidad", _tr.find("td").eq(4).html());
    //         data.append("txtpeso", _tr.find("td").eq(5).html());
    //         data.append("txtscop", _tr.find("td").eq(6).html());
    //         data.append("presseleccionada", cmbpresentacion[0]);
    //         data.append("unidad", textpresentacion[0].trim());
    //         data.append("cantequi", textpresentacion[1]);
    //         data.append("indice", i);
    //         axios.post('/guiasr/EditarUno', data)
    //             .then(function(respuesta) {
    //                 calcularIGV();
    //                 calcularsubtotal(row);
    //             }).catch(function(error) {
    //                 if (error.hasOwnProperty("response")) {
    //                     if (error.response.status == 422) {
    //                         console.log(error);
    //                     }
    //                 }
    //             });
    //     });
    // }

    function funcionEnterCant(o, i) {
        //Eliminamos los id anteriores
        var id1 = document.getElementById("1");
        $(id1).removeAttr('id', '1');
        var id2 = document.getElementById("2");
        $(id2).removeAttr('id', '2');

        $(o).attr('id', '1');

        var tr = $(o).parent();
        tr.find("td").eq(5).attr('id', '2');

        var cant = document.getElementById("1");
        cant.addEventListener("keypress", function(event) {
            if (event.key === "Enter") {
                event.preventDefault();
                $('#1').removeClass('focus');
                $('#1').removeAttr('contenteditable');
                // $('#2').focus().select();
            }
        });

        // var prec = document.getElementById("2");
        // prec.addEventListener("keypress", function(event) {
        //     if (event.key === "Enter") {
        //         event.preventDefault();
        //         $('#2').removeClass('focus');
        //         $('#2').removeAttr('contenteditable');
        //         $('#body').trigger('click');
        //     }
        // });

        var scop = document.getElementById("3");
        scop.addEventListener("keypress", function(event) {
            if (event.key === "Enter") {
                event.preventDefault();
                $('#3').removeClass('focus');
                $('#3').removeAttr('contenteditable');
                $('#body').trigger('click');
            }
        });
    }

    // Evento enter con el cantidad
    $("table tbody tr td:nth-child(6)").click(function() {
        var id = document.getElementById("2");
        $(id).removeAttr('id', '2')
        $(this).attr('id', '2')
        var cant = document.getElementById("2");
        cant.addEventListener("keypress", function(event) {
            if (event.key === "Enter") {
                event.preventDefault();
                $('#2').removeClass('focus');
                $('#2').removeAttr('contenteditable');
                $('#body').trigger('click');
            }
        });
    });

    // Evento enter con peso
    $("table tbody tr td:nth-child(7)").click(function() {
        var id = document.getElementById("3");
        $(id).removeAttr('id', '3')
        $(this).attr('id', '3')
        var prec = document.getElementById("3");
        prec.addEventListener("keypress", function(event) {
            if (event.key === "Enter") {
                event.preventDefault();
                $('#3').removeClass('focus');
                $('#3').removeAttr('contenteditable');
                $('#body').trigger('click');
            }
        });
    });

    //Calculamos en el subtotal y total
    function calcularsubtotal(o) {
        var _tr = $(o);
        var cant = _tr.find("td").eq(4).html();
        var prec = _tr.find("td").eq(5).html();
        var subt = parseFloat(cant) * parseFloat(prec);
        var campo = _tr.find("td").eq(6);
        if (isNaN(subt)) {
            toastr.info("Dígite un número correcto")
        } else {
            campo.html(subt.toFixed(2));
            var total_col1 = 0;
            $('table tbody').find('tr').each(function(i, el) {
                //Voy incrementando las variables segun la fila ( .eq(0) representa la fila 1 )
                total_col1 += parseFloat($(this).find('td').eq(6).text());
            });
        }
    }

    function verificarValores(o) {
        calcularPesoTotal();
    }

    function calcularPesoTotal() {
        var cantidades = [];
        var pesos = [];
        var pesoTotal = [];

        var total = 0;
        i = 0;
        $("#griddetalle tbody > tr").each(function(index) {
            var cantidad = Number($(this).find('.cantidad').text());
            cantidades.push(cantidad);
            var peso = Number($(this).find('.precio').text());
            pesoTotal.push(peso);
            var pesot = cantidad * peso;
            // console.log(pesot)
            total += pesot;
        });
        if (!isNaN(total)) {
            $("#total").val(total.toFixed(2));
        } else {
            $("#total").val("0.00");
        }
    }
</script>
<?php
$this->endSection('javascript');
?>