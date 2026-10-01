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
                    <div class="input-group">
                        <input type="text" class="form-control form-control-sm" id="txtproveedor" aria-label="" aria-describedby="basic-addon2" placeholder="Proveedor" disabled value="<?php echo isset($datosproveedor['razo']) ?  trim($datosproveedor['razo']) : '' ?>">
                        <input type="hidden" id="txtidproveedor" value="<?php echo isset($datosproveedor['idprov']) ?  $datosproveedor['idprov'] : '' ?> ">
                        <input type="hidden" id="txtrucproveedor" value=""><input type="hidden" id="txtptopartida" value="">
                        <input type="hidden" id="txtUbigeoproveedor" value="">
                        <input type="hidden" id="txtidauto" value="<?php echo isset($idcompra) ? $idcompra : 0 ?>">
                        <button class="btn btn-outline-light" disabled role="button" data-bs-toggle="modal" data-bs-target="#modal_proveedor"><i style="color:black" class="fas fa-user-alt"></i></button>
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
                        <input type="text" onkeyup="mayusculas(this); isFormatSerie()" class="form-control form-control-sm col-3" maxlength="4" id="cndoc1" value="" placeholder="F001">
                        <input type="text" onkeypress="return isNumberNdoc(event);" onblur="rellenaNumero()" class="form-control form-control-sm" maxlength="8" id="cndoc2" value="" placeholder="00000001" pattern="^[0-9]" />
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="input-group">&nbsp;&nbsp;&nbsp;
                        <label class="col-sm-0 col-form-label col-form-label-sm">Guía:</label>
                        <input type="text" class="form-control form-control-sm" maxlength="13" id="ndo2" style="width: 100px;" value="<?php echo trim($serie) . trim($num) ?> " placeholder="T00100000001">
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
        ie = -1;
        titulo("<?php echo $titulo ?>");
        // if (valor == 'R') {
        axios.get('/compras/detalleguiaparacanje').then(function(respuesta) {
            const contenido_tabla = respuesta.data;
            $('#detalle').html(contenido_tabla);
            calcularIGV();
        }).catch(function(error) {
            toastr.error('Error al cargar el listado' + error, 'Mensaje del sistema')
        });
        // }
        $(".codigo").css("display", "none");
        $("#cmbAlmacen").removeAttr("disabled");
        fechai = document.getElementById('txtfechai').value;
        obtenerDolar(fechai);
        $("#txtidproveedor").val("<?php echo isset($datosproveedor['idprov']) ?  $datosproveedor['idprov'] : '' ?>");
        $("#txtproveedor").val("<?php echo isset($datosproveedor['razo']) ?  $datosproveedor['razo'] : '' ?>");
        $("#cmbAlmacen option[value='0']").remove();
        $("#cmbdcto").val("01");
        $("#cmbdcto option[value='09']").remove();
        $("#cmbdcto option[value='GI']").remove();
    }

    $(".tipodocumentos").on("change", function() {
        isFormatSerie();
    });

    function entertipodocumento(u) {
        enterPressed = 1;
        u.onkeypress = function(e) {
            var keyCode = (e.keyCode || e.which);
            if (keyCode === 13) {
                if (enterPressed == 0) {} else if (enterPressed >= 1) {
                    e.preventDefault();
                    $("#cndoc1").click();
                    $("#cndoc1").select();
                }
                enterPressed++;
                return;
            }
        };
    }

    function enterformapago(u) {
        enterPressed = 1;
        u.onkeypress = function(e) {
            var keyCode = (e.keyCode || e.which);
            if (keyCode === 13) {
                if (enterPressed == 0) {} else if (enterPressed >= 1) {
                    e.preventDefault();
                    $("#modal_productos").modal('show');
                }
                enterPressed++;
                return;
            }
        };
    }

    $("#modal_productos").on("shown.bs.modal", function() {
        filastbl = document.getElementById("griddetalle").rows.length;
        if (filastbl <= 1) {
            moverCursorFinalTexto("txtbuscarProducto");
        }
        if (document.getElementById('codigo').checked) {
            moverCursorFinalTexto("txtbuscarProducto");
            $("#txtbuscarProducto").select();
        }
    });

    $("#cndoc1").on("keypress", function(evt) {
        if (evt.key === "Enter") {
            $("#cndoc2").click();
            $("#cndoc2").select();
        }
    });

    $("#cndoc1").on("keypress", function(evt) {
        if (evt.key === "Enter") {
            $("#cndoc2").click();
            $("#cndoc2").select();
        }
    });

    $("#cndoc2").on("keypress", function(evt) {
        if (evt.key === "Enter") {
            $("#ndo2").click();
            $("#ndo2").select();
        }
    });

    $("#ndo2").on("keypress", function(evt) {
        if (evt.key === "Enter") {
            $("#cmbforma").focus();
            $("#cmbforma").click();
        }
    });

    function entertest(u) {
        var enterPressed = 1;
        u.onkeypress = function(e) {
            var keyCode = (e.keyCode || e.which);
            if (keyCode === 13) {
                if (enterPressed == 0) {} else if (enterPressed >= 1) {
                    e.preventDefault();
                    tr = $(u).parent().parent();
                    inputcantidad = $(tr).find(".cantidad input");
                    $(inputcantidad).select();
                    $(inputcantidad).click();
                    $(inputcantidad).attr("id", "1")
                }
                enterPressed++;
                return;
            }
        };
    }

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
        };
    }


    $('#modal_productos').on('hidden.bs.modal', function() {
        var a = $("#griddetalle tr:last td:eq(3)");
        select = $(a).find("select");
        // $(select).select();
        $(select).focus();
        $(select).click();
        // $(a).find("input").click();
        // $(a).find("input").focus();
    });

    function agregarunitemVenta(datos) {
        // console.log(ie);
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
        if (ie < 0) {
            axios.post('/compras/agregaritem', data)
                .then(function(respuesta) {
                    //window.location.href = '/vtas/index';
                    $('#modal_productos').modal('hide')
                    const contenido_tabla = respuesta.data;
                    $('#detalle').html(contenido_tabla);
                    calcularIGV();
                    //$("#griddetalle tr:last").focus()
                    // var a = $("#griddetalle tr:last td:eq(4)").each(function() {
                    //     $(this).focus();
                    //     $(this).click();
                    // });
                    idart = "#agregar" + datos.parametro2;
                    // console.log(idart);
                    $(idart).attr('disabled', 'disabled');
                    ie = -1;
                }).catch(function(error) {
                    if (error.hasOwnProperty("response")) {
                        if (error.response.status === 422) {
                            toastr.error(error.response.data.errors, "Mensaje del Sistema");
                        }
                    }
                });
        } else {
            data.append("indice", ie);
            axios.post('/compras/agregaritemxposicion', data)
                .then(function(respuesta) {
                    //window.location.href = '/vtas/index';
                    $('#modal_productos').modal('hide')
                    const contenido_tabla = respuesta.data;
                    $('#detalle').html(contenido_tabla);
                    calcularIGV();
                    idart = "#agregar" + datos.parametro2;
                    $(idart).attr('disabled', 'disabled');
                    ie = -1;
                }).catch(function(error) {
                    if (error.hasOwnProperty("response")) {
                        if (error.response.status === 422) {
                            toastr.error(error.response.data.errors, "Mensaje del Sistema");
                        }
                    }
                });
        }
    }

    function quitaritem(pos) {
        const data = new FormData();
        data.append("indice", pos)
        axios.post('/compras/quitaritem', data)
            .then(function(respuesta) {
                const contenido_tabla = respuesta.data;
                $('#detalle').html(contenido_tabla);
                calcularIGV();
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
        document.querySelector("#cmbmoneda").value = "S";
        document.querySelector('#txtdolar').value = "";
        window.location.href = '/compras/listar';
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
        // if (total == 0) {
        //     toastr.info("Ingrese importes válidos", 'Mensaje del Sistema');
        //     return false;
        // }
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

    // $('#mdactualizarprecios').on('hidden.bs.modal', function() {});

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

    // Modal Registro Cuentas x Pagar  FINAL
    function grabarCompra() {
        if (!validarCompra()) {
            return;
        }
        var cmensaje = "";
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
            if (Number(importetotal) != 0) {
                if (Number(totalsuma) > Number(importetotal)) {
                    toastr.error("El monto sumado no debe ser mayor al total", 'Mensaje del sistema');
                    return;
                }
            }
            txtnumeroletras = $("#txtnumeroletras").val();
            if (txtnumeroletras.length == 0 || txtnumeroletras == '' || Number(txtnumeroletras) == 0) {
                toastr.error("Ingrese el numero de letras", 'Mensaje del sistema');
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
                data.append("txtrucproveedor", $("#txtrucproveedor").val());
                data.append("pimpo", $("#txtpercepcion").val());
                data.append("cmbtipodocumentocuentasxpagar", $("#cmbtipodocumentocuentasxpagar").val());
                data.append("alm", alm);
                data.append("igv", igv);
                data.append("cuentasxpagar", JSON.stringify(detalle));
                data.append("actualizarprecios", $("#actualizarprecios").val());
                data.append("exonerado", $("#exonerado").val());
                data.append("txtidauto", $("#txtidauto").val());
                data.append("productos", JSON.stringify(obtenerProductos()));
                axios.post("/compras/registrarcanjedeguia", data)
                    .then(function(respuesta) {
                        const tabla = respuesta.data;
                        $('#detalle').html(tabla);
                        cancelarCompra();
                        limpiardatos();
                        Swal.fire({
                            title: "Proceso realizado",
                            text: "Se ejecuto el canje correctamente",
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

    function obtenerProductos() {
        let productos = [];
        $('#griddetalle tbody tr').each(function() {
            let codigo = $(this).find('td.codigo').text().trim();
            let precio = $(this).find('td.precio input').val();
            let selectPresentacion = $(this).find('select[name="cmbpresentaciones"]');
            let optionSeleccionado = selectPresentacion.find('option:selected');
            let valor = optionSeleccionado.val().split('-');
            let epta_idep = valor[0];
            let texto = optionSeleccionado.text().trim();
            let epta_cant = texto.substring(texto.lastIndexOf('-') + 1).trim();
            productos.push({
                codigo: codigo,
                precio: precio,
                epta_idep: epta_idep,
                epta_cant: epta_cant
            });
        });
        return productos;
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

    function obtenerTipoIGV() {
        let vdvto = 'I';
        if (document.getElementsByName("igv")[0].checked) {
            vdvto = 'I';
        }
        if (document.getElementsByName("igv")[1].checked) {
            vdvto = 'N';
        }
        return vdvto;
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
            valorigv = Number("<?php echo $_SESSION['gene_igv']; ?>");
            let valor = (impo / valorigv).toFixed(2);
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

    $('#cbpercepcion').change(function() {
        calcularpercepcion()
    });
</script>
<?php
$this->endSection("javascript");
?>