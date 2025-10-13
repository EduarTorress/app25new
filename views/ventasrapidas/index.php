<?php

use App\View\Components\DocumentoComponent;
use App\View\Components\FechaComponent;
use App\View\Components\FechavtoComponent;
use App\View\Components\FormadepagoComponent;
use App\View\Components\VendedorComponent;
use App\View\Components\TipoMonedaComponent;
use App\View\Components\ModalClienteComponent;
use App\View\Components\ModalProductoComponent;
use App\View\Components\ModalImprimir;
use App\View\Components\IGVComponent;
use App\View\Components\ModalConfirmarLoginComponent;

$this->setLayout('layouts/admin');
?>
<?php
$this->startSection('contenido');
?>
<?php
$clie = new ModalClienteComponent();
echo $clie->render();
$prod = new ModalProductoComponent();
echo $prod->render();
$login = new ModalConfirmarLoginComponent();
echo $login->render();
?>
<div class="modal fade" id="mdpreregistro" tabindex="-1" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="">Pre Registro</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-8">
                        <div class="input-group">
                            <input type="text" class="form-control form-control-sm" id="txtcliente" placeholder="Cliente" disabled value="VENTAS DEL DIA">
                            <input type="hidden" id="txtidcliente" value="2">
                            <input type="hidden" id="txtruccliente" value="">
                            <input type="hidden" id="txtdireccion" value="">
                            <input type="hidden" id="txtdnicliente" value="">
                            <input type="hidden" id="txtidauto" value="">
                            <button class="btn btn-outline-light" role="button" data-bs-toggle="modal" data-bs-target="#modal_clientes"><i style="color:black" class="fas fa-user-alt"></i></button>
                            <button class="btn btn-outline-primary" role="button" onclick="mostrardatoscliente()"><i style="color:black" class="fa fa-address-card-o"></i></button>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <?php
                        $ctdoc = isset($datosclientev['tdocv']) ? $datosclientev['tdocv'] : '20';
                        $dctos = new DocumentoComponent($ctdoc);
                        echo $dctos->render();
                        ?>
                    </div>
                    <div class="col-sm-2" style="display:none">
                        <div class="input-group">
                            <label class="col-sm-0 col-form-label col-form-label-sm">Guía R. :</label>
                            <input type="text" onkeyup="mayusculas(this);" class="form-control form-control-sm" id="ndo2" style="width: 100px;" value="<?php echo isset($datosclientev['ndo2v']) ?  $datosclientev['ndo2v'] : '' ?>" placeholder="T001-00001">
                        </div>
                    </div>
                    <div class="col-sm-2" style="display:none">
                        <?php
                        $cempresa = isset($datosclientev['almv']) ? $datosclientev['almv'] : $_SESSION['idalmacen'];
                        $empresa = new \App\View\Components\EmpresaComponent($cempresa);
                        echo $empresa->render();
                        ?>
                    </div>
                    <div class="col-sm-6" style="display:none">
                        <?php
                        $fecha = new FechaComponent();
                        echo $fecha->render();
                        ?>
                    </div>
                    <div class="col-sm-6" style="display:none">
                        <?php
                        $cmon = isset($datosclientev['monev']) ? $datosclientev['monev'] : '';
                        $tpmoneda = new TipoMonedaComponent($cmon);
                        echo $tpmoneda->render();
                        ?>
                    </div>
                    <br><br>
                    <div class="col-sm-6">
                        <?php
                        $cforma = isset($datosclientev['formv']) ? $datosclientev['formv'] : '';
                        $formapago = new FormadepagoComponent($cforma);
                        echo $formapago->render();
                        ?>
                    </div>
                    <div class="col-sm-6">
                        <?php
                        $dfechavto = new FechavtoComponent();
                        echo $dfechavto->render();
                        ?>
                    </div>
                    <div class="col-sm-6">
                        <?php
                        $nidv = isset($datosclientev['idvenv']) ? $datosclientev['idvenv'] : 0;
                        $vendedor = new VendedorComponent($nidv);
                        echo $vendedor->render();
                        ?>
                        <div class="form-group row" style="display: none">&nbsp;&nbsp;&nbsp;
                            <label class="col-sm-0 col-form-label col-form-label-sm">Fecha Vcto. :</label>
                            <input type="date" class="form-control form-control-sm" value="<?php echo empty($datosclientev['fechvV']) ?  date("Y-m-d") :  $datosclientev['fechvV'] ?>" style="width:140px;" id="txtfechav" name="txtfechav">
                        </div>
                    </div>
                    <div class="col-sm-6" style="<?php echo ($_SESSION['config']['multiigv'] == 'S' ? '' : 'display:none') ?>">
                        <?php
                        $optigv = isset($datosclientev['optigv']) ? $datosclientev['optigv'] : '';
                        $igv = new IGVComponent($optigv);
                        echo $igv->render();
                        ?>
                    </div>
                    <div class="col-sm-6">
                        <div class="input-group mb-3">
                            <label class="form-control form-control-sm" for="">TOTAL:</label>
                            <input type="text" class="form-control form-control-sm" id="txttotal" readonly>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="input-group mb-3">
                            <label class="form-control form-control-sm" for="">PAGO:</label>
                            <input type="text" onclick="this.select(); disabledbtngrabar();" onkeypress="entergrabar(event); return isNumber(event)" onkeyup="calcularvuelto();" value="0.00" class="form-control form-control-sm" id="txtpago">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="input-group mb-3">
                            <label class="form-control form-control-sm" for="">VUELTO:</label>
                            <input type="text" class="form-control form-control-sm" id="txtvuelto" readonly>
                        </div>
                    </div>
                    <div class="col-sm-6" style="display:none" id="divefectivo">
                        <div class="input-group mb-3">
                            <label class="form-control form-control-sm" for="">EFECTIVO:</label>
                            <input type="text" onclick="this.select();" onkeypress="entergrabar(event); return isNumber(event)" onkeyup="calcularvuelto();" class="form-control form-control-sm" id="txtefectivo">
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <input type="text" class="form-control form-control-sm" name="txtreferencia" placeholder="Referencia" id="txtreferencia" value="<?php echo (isset($datosclientev['txtreferencia']) ? $datosclientev['txtreferencia'] : '') ?>">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary text-white " id="btngrabar" onclick="registrarvta();">Guardar</button>
                <button type="button" class="btn btn-danger text-white" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<div class="content-wrapper">
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
<?php
$oimp = new ModalImprimir();
echo $oimp->render();
?>
<style>
    #cliente {
        color: black
    }

    #txtbuscar {
        background-color: white;
        color: black;
    }

    #tabla_clientes {
        color: black;
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
        idsolicitud = 1;
        idcliente = 0;
        titulo("<?php echo $titulo ?>");
        axios.get('/ventasrapidas/listardetalle').then(function(respuesta) {
            // 100, 200, 300
            const contenido_tabla = respuesta.data;
            $('#detalle').html(contenido_tabla);
            $(".codigo").css("display", "none");
            $(".indice").css("display", "none");
            <?php if ($_SESSION['config']['multiigv'] != 'S') : ?>
                $(".valorunitario").css("display", "none");
            <?php endif; ?>
            // $("#txtfecha").attr("disabled", true);
            $("#cndoc1").val("<?php echo (isset($serie) ?  $serie : '') ?>");
            calcularIGV();
            <?php if ($_SESSION['moneda'] == 'SI') : ?>
                $("#cmbmoneda").attr('disabled', true);
            <?php endif; ?>
            <?php if ($_SESSION['opigv'] == 'N') : ?>
                $(".igv").attr('disabled', true);
            <?php endif; ?>
            <?php if ($_SESSION['config']['facturardolares'] == 'N') : ?>
                $(".cmbmoneda option[value='D']").remove();
            <?php endif; ?>
            $(".tipodocumentos option[value='07']").remove();
            $(".tipodocumentos option[value='08']").remove();
            ventasexon = "<?php echo empty($_SESSION['config']['ventasexon']) ? 'N' : 'S'; ?>";
            if (ventasexon == 'S') {
                $("#lblsubtotal").text("EXONER.")
            }
        }).catch(function(error) {
            // 400, 500
            toastr.error('Error al cargar el listado ' + error, 'Mensaje del sistema');
        });
        // buscarProducto();
        $("#txtfecha").val("<?php echo (empty($datosclientev['fechv']) ? date("Y-m-d") : $datosclientev['fechv']) ?>");
        $("#txtdias").val("<?php echo (empty($datosclientev['dias']) ?  '' :  $datosclientev['dias']) ?>");
        $("#txtfechavto").val("<?php echo (empty($datosclientev['fvto']) ?  date("Y-m-d") :  $datosclientev['fvto']) ?>");
        $("#modal_productos").modal('show');
    }

    // var code = e.keyCode || e.which;
    // if (code == 46) {
    //     $("#searchP").empty();
    //     $("#txtbuscarProducto").select();
    // }

    // $("input[type='search']").val("Ventas x Producto");

    function disabledbtngrabar() {
        $("#btngrabar").removeAttr("disabled");
    }

    $('#modal_clientes').on('hidden.bs.modal', function() {
        $("#mdpreregistro").modal('show');
    });

    $('#divfecha').click(function() {
        $("#txtfecha").prop("readonly", false);
    });

    $("#cmbforma").on("change", function() {
        cmbforma = $(this).val();
        if (cmbforma == 'T' || cmbforma == 'Y' || cmbforma == 'P' || cmbforma == 'D') {
            $("#divefectivo").css("display", "block")
            $("#txtpago").click();
            $("#txtpago").focus();
            $("#txtpago").select();
        } else {
            $("#divefectivo").css("display", "none")
        }
    });

    $('#mdpreregistro').on('shown.bs.modal', function() {
        $("#txtvuelto").val("0.00");
        $("#txtefectivo").val("0.00");
        $("#txtpago").click();
        $("#txtpago").focus();
        $("#txtpago").select();
    });

    function verutilidad() {
        $("#modalConfirmarLogin").modal("show");
    }

    function cerrarModal() {
        $("#modalConfirmarLogin").modal("hide");
    }

    function entergrabar(e) {
        if (e.keyCode === 13 && !e.shiftKey) {
            txttotal = $("#txttotal").val();
            var txtpago = document.getElementById("txtpago").value;
            if (txtpago == "0.00" || txtpago.length == 0) {
                toastr.info("Ingrese el pago", 'Mensaje del Sistema');
                return;
            }
            txtpagoefectivo = $("#txtefectivo").val();
            pago = txtpago + Number(txtpagoefectivo)
            if (Number(pago) < Number(txttotal)) {
                toastr.info("El pago debe ser mayor o igual el total", 'Mensaje del Sistema');
                $("#txtpago").select()
                return;
            }
            if (isNaN($("#txtvuelto").val())) {
                toastr.info("Digite correctamente los datos", 'Mensaje del Sistema');
                $("#txtpago").select()
                return;
            }
            if (idsolicitud == 0) {
                return;
            }
            registrarvta();
        }
    }

    function consultarlogin() {
        data = new FormData();
        data.append("txtUsuario", document.getElementById("txtUsuario").value);
        data.append("txtPassword", document.getElementById("txtPassword").value);
        axios.post("/vtas/verutilidad", data)
            .then(function(respuesta) {
                //toastr.success("Eliminado correctamente",'Mensaje del Sistema');
                Swal.fire({
                    title: "La ganancia calculada correctamente",
                    text: "La ganancia es: " + respuesta.data.message,
                    icon: "success"
                });
                $("#divutilidad").css("display", "");
                $("#txtutilidad").val(respuesta.data.message);
                $("#modalConfirmarLogin").modal("hide");
            }).catch(function(error) {
                if (error.hasOwnProperty("response")) {
                    if (error.response.status == 422) {
                        toastr.error(error.response.data.message, 'Mensaje del sistema');
                    }
                }
            });
    }

    function quitaritem(pos, idart) {
        btnagregar = "#agregar" + idart;
        const data = new FormData();
        data.append("indice", pos)
        axios.post('/ventasrapidas/quitaritem', data)
            .then(function(respuesta) {
                const contenido_tabla = respuesta.data;
                $('#detalle').html(contenido_tabla);
                calcularIGV();
                $(btnagregar).removeAttr('disabled');
                // $(btnagregar).attr('disabled', false);
                // $('#totalpedido').html(document.querySelector("#total").value);
            }).catch(function(error) {
                toastr.error(error, 'Mensaje del sistema');
            });
    }

    function grabarCabecera() {
        // data = new FormData();
        // data.append("idcliev", $("#txtidcliente").val());
        // data.append("razov", $("#txtcliente").val());
        // data.append("ruccliev", $("#txtruccliente").val());
        // data.append("tdocv", $("#cmbdcto").val());
        // data.append("cndocv", "");
        // data.append("numv", "");
        // var optigv = obtenerTipoIGV();
        // data.append("optigv", optigv);
        // data.append("ndo2v", $("#ndo2").val());
        // data.append("almv", $("#cmbAlmacen").val());
        // data.append("fechv", $("#txtfecha").val());
        // data.append("monev", $("#cmbmoneda").val());
        // data.append("formv", $("#cmbforma").val());
        // data.append("fechvv", $("#txtfechav").val());
        // data.append("idvenv", $("#cmbvendedor").val());
        // data.append("txtreferencia", $("#txtreferencia").val());
        // axios.post("/vtas/sesion", data)
        //     .then(function(respuesta) {
        //         // console.log("Se registro en la sesión")
        //     }).catch(function(error) {
        //         toastr.error("Error al guardar sesión", "Mensaje del Sistema");
        //     });
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
        // quitardisabled();
    });

    function quitardisabled() {
        $('#tabla_productos tr').each(function() {
            $(this).find('td').each(function() {
                $(this).find('button').removeAttr("disabled");
            });
        });
    }

    $("#modal_clientes").on("shown.bs.modal", function() {
        moverCursorFinalTexto("txtbuscar");
    });

    document.addEventListener('keyup', function(event) {
        if (event.ctrlKey && event.keyCode === 13) {
            $("#cmdbuscarP").click();
        }
    });

    // document.addEventListener("keydown", (e) => {
    //     switch (e.key) {
    //         case "Delete":
    //             $("#txtbuscarProducto").focus();
    //             break;
    //         default:
    //             break;
    //     }
    // });

    function agregarunitemVenta(datos) {
        presentaciones = JSON.parse(datos.parametro11);
        precio = presentaciones[0]['epta_prec'];
        unidad = presentaciones[0]['pres_desc'];
        cantequi = presentaciones[0]['epta_cant'];
        eptaidep = presentaciones[0]['epta_idep'];
        const data = new FormData();
        data.append('txtcodigo', datos.parametro2);
        data.append("txtdescripcion", datos.parametro1);
        // data.append("txtunidad", datos.parametro3);
        data.append("txtunidad", unidad);
        data.append("txtprecio", Number(precio).toFixed(2));
        // data.append("txtprecio", datos.parametro5);
        data.append("txtcantidad", 1);
        data.append("precio1", datos.parametro6);
        data.append("precio2", datos.parametro7);
        data.append("precio3", datos.parametro5);
        data.append("costo", datos.parametro8);
        data.append("tipoproducto", datos.parametro10);
        data.append("presentaciones", datos.parametro11);
        data.append("presseleccionada", eptaidep);
        data.append("cantequi", cantequi);
        data.append("cmbmoneda", $("#cmbmoneda").val());
        data.append("stock", parseFloat(datos.parametro4.toFixed(2)));
        data.append("opt", 0)
        axios.post('/ventasrapidas/agregaritem', data)
            .then(function(respuesta) {
                // // $('#modal_productos').modal('hide')
                // const contenido_tabla = respuesta.data;
                // $('#detalle').html(contenido_tabla);
                // calcularIGV();
                // idart = "#agregar" + datos.parametro2;
                // // console.log(idart);
                // $(idart).attr('disabled', 'disabled');
                $('#modal_productos').modal('hide')
                const contenido_tabla = respuesta.data;
                $('#detalle').html(contenido_tabla);
                calcularIGV();
                idart = "#agregar" + datos.parametro2;
                $(idart).attr('disabled', 'disabled');
            }).catch(function(error) {
                if (error.hasOwnProperty("response")) {
                    if (error.response.status == 422) {
                        if (error.response.data.errors) {
                            e = error['response']['data']['errors']
                            result = []
                            for (var i in e) {
                                result.push([i, e[i]]);
                            }
                            result.forEach(function(numero) {
                                toastr.error(numero[1], 'Mensaje del sistema')
                            });
                        } else {
                            errors = error.response.data.message;
                            toastr.error(errors, "Mensaje del Sistema")
                        }
                    }
                }
                $("#txtbuscarproducto").select();
            });
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

    function calcularIGV() {
        igv = obtenerTipoIGV();
        var total_col = 0;
        valorigv = Number("<?php echo $_SESSION['gene_igv']; ?>");
        $('#griddetalle tbody').find('tr').each(function(i, el) {
            columantotal = "<?php echo empty($_SESSION['config']['tipobotica']) ? 7 : 9; ?>";
            t = $(this).find('td').eq(columantotal).text();
            // t = t.replace(",", "")
            total_col += parseFloat(t);
        });
        if (igv == 'I') {
            $('#griddetalle tbody tr').each(function() {
                $(this).find(".preciosgv").html("");
            });
        } else {
            $('#griddetalle tbody tr').each(function() {
                precio = $(this).find(".precio input").val();
                preciosgv = precio / Number(valorigv);
                $(this).find(".preciosgv").html(Number(preciosgv).toFixed(2));
            });
        }

        let impo = (Number(total_col)).toFixed(2);
        let valor = (impo / valorigv).toFixed(2);
        let nigv = (impo - valor).toFixed(2);
        $("#igv").val(nigv);
        $("#subtotal").val(valor);
        $("#total").val(impo);

        ventasexon = "<?php echo empty($_SESSION['config']['ventasexon']) ? 'N' : 'S'; ?>";
        if (ventasexon == 'S') {
            $("#igv").val("0");
            $("#subtotal").val(impo);
            $("#total").val(impo);
        }

        let impor = document.querySelector("#total").value;
        if (isNaN(impor)) {
            $("#subtotal").val("0.00");
            $("#igv").val("0.00");
            $("#total").val("0.00");
        }
    }

    function mostrardatoscliente() {
        txtruccliente = $("#txtruccliente").val();
        txtdireccion = $("#txtdireccion").val();
        txtdnicliente = $("#txtdnicliente").val();
        Swal.fire("RUC: " + txtruccliente + ". <br> DNI: " + txtdnicliente + ". <br>DIRECCIÓN: " + txtdireccion + ".");
    }

    function validarVenta() {
        idcliente = document.querySelector('#txtidcliente').value;
        total = document.querySelector('#total').value;
        ctdoc = $('#cmbdcto option:selected').val();
        ruc = document.querySelector('#txtruccliente').value;
        if (idcliente == 0) {
            toastr.info("Seleccione un Cliente", 'Mensaje del Sistema');
            return false;
        }
        if (total == 0) {
            toastr.info("Ingrese Importes Válidos", 'Mensaje del Sistema');
            return false;
        }
        if (ctdoc == '01' && ruc.trim() == '') {
            toastr.info("Se necesita que el Cliente tenga RUC para hacer una Factura", 'Mensaje del Sistema');
            return false;
        }
        if (ctdoc == '01' && ruc == 0) {
            toastr.info("Se necesita que el Cliente tenga RUC para hacer una Factura", 'Mensaje del Sistema');
            return false;
        }
        return true;
    }

    function preregistro() {
        if (validarcampocantidad() == true) {
            toastr.error("La cantidad de los productos deben ser mayores que cero", 'Mensaje del Sistema');
            return;
        }
        calcularIGV();
        importe = $("#total").val();
        if (importe == "0.00") {
            toastr.info("Agregue productos a la venta", 'Mensaje del Sistema');
            return;
        }
        $("#mdpreregistro").modal('show');
        $("#txttotal").val(importe)
    }

    function calcularvuelto() {
        total = $("#total").val();
        pago = $("#txtpago").val();
        pagoefectivo = $("#txtefectivo").val();
        pagototal = Number(pago) + Number(pagoefectivo)
        diferenciavuelto = Number(pagototal) - Number(total);
        if (diferenciavuelto < 0) {
            $("#txtvuelto").val("0.00")
        } else {
            $("#txtvuelto").val(diferenciavuelto.toFixed(2))
        }
    }

    function validarcampocantidad() {
        cantnull = false;
        $('#griddetalle tbody tr').each(function() {
            _tr = $(this);
            var cant = _tr.find("td").eq(4).find("input").val();
            if (cant.length == 0) {
                cantnull = true;
                return cantnull;
            }
            var precio = _tr.find("td").eq(5).find("input").val();
            if (precio.length == 0) {
                cantnull = true;
                return cantnull;
            }
        });
        return cantnull;
    }

    function registrarvta() {
        calcularIGV();
        if (validarcampocantidad() == true) {
            toastr.error("La cantidad de los productos deben ser mayores que cero", 'Mensaje del Sistema');
            return;
        }
        $("#btngrabar").attr("disabled", "disabled");
        if (!validarVenta()) {
            return;
        }
        txttotal = $("#txttotal").val();
        var txtpago = document.getElementById("txtpago").value;
        if (txtpago == "0.00" || txtpago.length == 0) {
            toastr.info("Ingrese el pago", 'Mensaje del Sistema');
            $("#btngrabar").removeAttr("disabled");
            return;
        }
        txtpagoefectivo = $("#txtefectivo").val();
        pago = Number(txtpago) + Number(txtpagoefectivo)
        if (Number(pago) < Number(txttotal)) {
            toastr.info("El pago debe ser mayor o igual al total", 'Mensaje del Sistema');
            $("#txtpago").select()
            $("#btngrabar").removeAttr("disabled");
            return;
        }
        cmbforma = $("#cmbforma").val();
        if ((cmbforma == 'T') || (cmbforma == 'Y') || (cmbforma == 'P') || (cmbforma == 'D')) {
            //console.log(Number(txtpagoefectivo));
            if (Number(txtpagoefectivo) <= 0) {
                if (Number(pago) > Number(txttotal)) {
                    toastr.info("El pago debe ser igual al total", 'Mensaje del Sistema');
                    $("#txtpago").select()
                    $("#btngrabar").removeAttr("disabled");
                    return;
                }
            }
        }

        valorescarrito = verificarvalorescarrito();
        const data = new FormData();
        data.append("detalle", JSON.stringify(valorescarrito));
        axios.post('/vtas/verificarvalorescarrito', data)
            .then(function(respuesta) {
                rpta = respuesta.data.estado;
                if (rpta == '1') {
                    cmensaje = '¿Registrar Venta?';
                    grabar(cmensaje);
                } else {
                    toastr.warning("Ocurrió un error de conexión a internet. Vuelva a intentarlo", 'Mensaje del Sistema')
                }
            });
        // cmensaje = '¿Registrar Venta?';
        // grabar(cmensaje);
    }

    function verificarvalorescarrito() {
        const detalle = []
        json = "";
        columnaindice = "<?php echo (empty($_SESSION['config']['tipobotica']) ? 8 : 10); ?>";
        $('#griddetalle tbody tr').each(function() {
            var _tr = $(this);
            var id = _tr.find("td").eq(columnaindice).html();
            var cant = _tr.find("td").eq(4).find("input").val();
            var precio = _tr.find("td").eq(5).find("input").val();
            json += ',"id":"' + id + '"'
            json += ',"cant":"' + cant + '"'
            json += ',"precio":"' + precio + '"'
            obj = JSON.parse('{' + json.substr(1) + '}');
            calcularsubtotal(_tr)
            detalle.push(obj)
        });
        return detalle;
    }

    function armarcarritorespaldo() {
        const respaldocarrito = []
        $("#griddetalle tbody tr").each(function() {
            json = "";
            $(this).find("td:not(.dtr-control)").each(function() {
                $this = $(this);
                if ($this.attr("name") == 'tdpresc') {
                    textresp = $this.find("#cmbpresentaciones option:selected").text().trim();
                    textresp = textresp.replace("'", " ");
                    textresp = textresp.replace('"', " ");
                    cmbpresentaciones = $this.find("#cmbpresentaciones").val();
                    cmbpresentaciones = cmbpresentaciones.split("-");
                    json += ',"' + $this.attr("name") + '":"' + textresp + "-" + cmbpresentaciones[0] + '"';
                } else {
                    if ($this.attr("name") == 'tdcant' || $this.attr("name") == 'tdprec') {
                        json += ',"' + $this.attr("name") + '":"' + $this.find("input").val().trim() + '"';
                    } else {
                        textresp = $this.text().trim();
                        textresp = textresp.replace("'", " ");
                        textresp = textresp.replace('"', " ");
                        json += ',"' + $this.attr("name") + '":"' + textresp + '"';
                    }
                }
            })
            obj = JSON.parse('{' + json.substr(1) + '}');
            respaldocarrito.push(obj)
        });
        return respaldocarrito;
    }

    function changedetaildolar() {
        moneda = $("#cmbmoneda").val();
        const data = new FormData();
        data.append('moneda', moneda);
        axios.post('/detail/changedolar', data)
            .then(function(respuesta) {
                const contenido_tabla = respuesta.data;
                $('#detalle').html(contenido_tabla);
                calcularIGV();
                $("#cmbmoneda").attr('disabled', true);
            }).catch(function(error) {
                if (error.hasOwnProperty("response")) {
                    if (error.response.status == 422) {
                        toastr.error(error.response.data.errors, "Mensaje del Sistema");
                    }
                }
            });
    }

    function changeoptigv() {
        $(".igv").attr('disabled', true);
    }

    function validarcampocantidad() {
        cantnull = false;
        $('#griddetalle tbody tr').each(function() {
            _tr = $(this);
            var cant = _tr.find("td").eq(4).find("input").val();
            if (cant.length == 0) {
                cantnull = true;
                return cantnull;
            }
        });
        return cantnull;
    }

    function grabar(cmensaje) {
        idsolicitud = 0;
        data = new FormData();
        data.append("idcliev", $("#txtidcliente").val());
        data.append("razov", $("#txtcliente").val());
        data.append("tdocv", $("#cmbdcto").val());
        data.append("txtdireccion", $("#txtdireccion").val());
        data.append("txtruccliente", $("#txtruccliente").val());
        data.append("txtdnicliente", $("#txtdnicliente").val());
        data.append("ndo2v", $("#ndo2").val());
        data.append("almv", $("#cmbAlmacen").val());
        data.append("fechv", $("#txtfecha").val());
        data.append("monev", $("#cmbmoneda").val());
        data.append("formv", $("#cmbforma").val());
        data.append("fechvv", $("#txtfechavto").val());
        let tigv = obtenerTipoIGV();
        data.append("optigv", tigv);
        data.append("idvenv", $("#cmbvendedor").val());
        data.append("subtotal", $("#subtotal").val());
        data.append("igv", $("#igv").val());
        data.append("total", $("#total").val());
        data.append("txtreferencia", $("#txtreferencia").val());
        data.append("txtefectivo", $("#txtefectivo").val());
        data.append("txtpago", $("#txtpago").val());
        data.append("txtvuelto", $("#txtvuelto").val());
        data.append("respaldo", "S");
        axios.post("/vtas/registrar", data)
            .then(function(respuesta) {
                estadorpta = respuesta.data.estado;
                if (estadorpta == '2') {
                    let filas = $("#griddetalle").find('tbody tr').length;
                    if (filas > 0) {
                        // $("#btngrabar").removeAttr("disabled");
                        // toastr.info("Por favor haga clic nuevamente en Grabar", "Mensaje del sistema");
                        const datarespaldo = new FormData();
                        datarespaldo.append('respaldocarrito', JSON.stringify(armarcarritorespaldo()));
                        axios.post('/ventasrapidas/cargarrespaldo', datarespaldo)
                            .then(function(respuestarespaldo) {
                                registrarvta();
                            }).catch(function(errorrespaldo) {
                                if (errorrespaldo.hasOwnProperty("response")) {
                                    toatr.error("Ocurrió un error de conexión", 'Mensaje del sistema')
                                }
                            });
                    }
                } else {
                    $("#mdpreregistro").modal('hide');
                    toastr.success(respuesta.data.mensaje.trimEnd() + ' ' + respuesta.data.ndoc);
                    $('#griddetalle tbody tr').remove();
                    var cruta = '/vtas/imprimirdirecto/';
                    var xhr = new XMLHttpRequest();
                    xhr.open('GET', cruta, true);
                    xhr.responseType = 'blob';
                    xhr.onload = function(e) {
                        if (this.status == 200) {
                            var w = screen.width;
                            url = location.protocol + '//' + document.domain + '/descargas/' + respuesta.data.ndoc + ".pdf"
                            if (w <= 800) {
                                var req = new XMLHttpRequest();
                                req.open("GET", url, true);
                                req.responseType = "blob";
                                req.onload = function(event) {
                                    var blob = req.response;
                                    var link = document.createElement('a');
                                    link.href = window.URL.createObjectURL(blob);
                                    link.download = respuesta.data.ndoc + ".pdf"
                                    link.click();
                                };
                                req.send();
                            } else {
                                $("#pdfguia").attr("src", url)
                                $("#abrirguia").click();
                                $("#exampleModal").modal('hide');
                                // $("#pdfguia").get(0).contentWindow.print();
                            }
                        }
                    };
                    xhr.send();
                    $("#btngrabar").removeAttr("disabled");
                    idsolicitud = 1;
                    limpiardatos();
                }
            }).catch(function(error) {
                $("#btngrabar").removeAttr("disabled");
                idsolicitud = 1;
                console.log(error);
                mostrarerroresvalidacion(error);
                toastr.error(error.response.message, 'Mensaje del sistema');
            });
    }

    function limpiardatos() {
        $("#cmbmoneda").attr('disabled', false);
        <?php $_SESSION['moneda'] = 'NO'; ?>
        <?php $_SESSION['opigv'] = 'I'; ?>
        $("#txtcliente").val("VENTAS DEL DÍA");
        $("#txtdias").val("");
        $("#titulo").val("Venta Rápida");
        $("#txtidcliente").val("2");
        $("#txtruccliente").val("");
        $("#txtdnicliente").val("");
        $("#txtdireccion").val("");
        $("#ndo2").val("");
        $("#cmbforma").val("E");
        // $("#cmbAlmacen").val("1");
        $("#cmbmoneda").val("S");
        $("#optigv").val("I");
        // $("#cmbvendedor").val("1");
        $("#total").val("0.00");
        $("#igv").val("0.00");
        $("#subtotal").val("0.00");
        $("#totalitems").val("0.00");
        $("#txtreferencia").val("");
        $("#cmbdcto").val("20");
        $("#divefectivo").css("display", "none")
        $("#searchP").empty();
        document.getElementById("grabar").innerHTML = "Grabar";
    }

    const onFocus = () => {
        $("#exampleModal").modal('hide');
        $("#txtpago").val("0.00");
        $('#modal_productos').modal('show');
        $("#mdpreregistro").modal('hide');
    }
    window.addEventListener("focus", onFocus)

    // function actualizar(cmensaje) {
    //     Swal.fire({
    //         title: cmensaje,
    //         text: "Se Graba en la Base de datos ",
    //         icon: 'question',
    //         showCancelButton: true,
    //         confirmButtonColor: '#3085d6',
    //         cancelButtonColor: '#d33',
    //         confirmButtonText: 'Si'
    //     }).then(function(respuesta) {
    //         if (respuesta.isConfirmed) {
    //             data = new FormData();
    //             data.append("idautov", $("#txtidauto").val());
    //             data.append("idcliev", $("#txtidcliente").val());
    //             data.append("razov", $("#txtcliente").val());
    //             data.append("tdocv", $("#cmbdcto").val());
    //             data.append("ndo2v", $("#ndo2").val());
    //             data.append("almv", $("#cmbAlmacen").val());
    //             data.append("fechv", $("#txtfecha").val());
    //             data.append("monev", $("#cmbmoneda").val());
    //             data.append("formv", $("#cmbforma").val());
    //             data.append("fechvv", $("#txtfechavto").val());
    //             let tigv = obtenerTipoIGV();
    //             data.append("optigv", tigv);
    //             data.append("idvenv", $("#cmbvendedor").val());
    //             data.append("subtotal", $("#subtotal").val());
    //             data.append("igv", $("#igv").val());
    //             data.append("total", $("#total").val());
    //             data.append("txtreferencia", $("#txtreferencia").val());
    //             axios.post("/vtas/actualizar", data)
    //                 .then(function(respuesta) {
    //                     toastr.success(' Se actualizo correctamente ');
    //                     const tabla = respuesta.data;
    //                     $('#detalle').html(tabla);
    //                     window.location.href = '/vtas/vtasresumidas';
    //                     limpiardatos();
    //                 }).catch(function(error) {
    //                     mostrarerroresvalidacion(error);
    //                 });
    //         }
    //     });
    // }

    function cancelarVenta() {
        axios.post('/ventasrapidas/limpiar').then(function(respuesta) {
            const tabla = respuesta.data;
            $('#detalle').html(tabla);
            window.location.href = '/ventasrapidas/index';
        }).catch(function(error) {
            toastr.error(error, 'Mensaje del sistema');
        });
    }

    //Eventos
    var txtfecha = document.getElementById("txtfecha");
    txtfecha.addEventListener("blur", function(event) {
        fech = $("#txtfecha").val();
        grabarCabecera();
    }, true);

    var txtfechav = document.getElementById("txtfechav");
    txtfechav.addEventListener("blur", function(event) {
        grabarCabecera();
    }, true);

    var ndo2 = document.getElementById("ndo2");
    ndo2.addEventListener("blur", function(event) {
        grabarCabecera();
    }, true);

    var txtreferencia = document.getElementById("txtreferencia");
    txtreferencia.addEventListener("blur", function(event) {
        grabarCabecera();
    }, true);

    $("#cmbvendedor").on("change", function() {
        grabarCabecera();
    });
</script>
<?php
$this->endSection("javascript");
?>