<?php

use App\View\Components\ModalImprimir;
use App\View\Components\ModalProductoComponent;
use App\View\Components\ModalTransportistaComponent;
?>
<?php
$this->setLayout('layouts/admin');
?>
<?php
$this->startSection('contenido');
?>
<?php
$ve = new ModalTransportistaComponent();
echo $ve->render();
?>
<?php
$prod = new ModalProductoComponent();
echo $prod->render();
?>
<?php
$oimp = new ModalImprimir();
echo $oimp->render();
?>
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <div class="row row-cols-1 row-cols-md-3 g-4">
                        <div class="col">
                            <div class="form-group row">
                                <label class="col-sm-0 col-form-label">Fecha de Emisión:</label>
                                <input type="date" id="txtFechaEmision" class="form-control" min="<?php echo date('Y-m-d') ?>" value="<?php echo isset($_SESSION['guiac']['fechaEmision']) ?  $_SESSION['guiac']['fechaEmision'] : date("Y-m-d") ?>" style="width:40%;">
                            </div>
                            <?php if (empty($_SESSION['traspaso']['txtidautot'])) : ?>
                                <button class="btn btn-success" onclick="modalcompras();">Canjear Compra</button>
                            <?php endif; ?>
                            <input type="hidden" name="idautot" id="idautot" value="0">
                        </div>
                        <div class="col">
                            <div class="form-group row">
                                <label class="col-sm-0 col-form-label">Fecha de Traslado:</label>
                                <input type="date" id="txtFechaTraslado" class="form-control " min="<?php echo date('Y-m-d') ?>" value="<?php echo isset($_SESSION['guiac']['fechaTraslado']) ?  $_SESSION['guiac']['fechaTraslado'] : date("Y-m-d") ?>" style="width:45%;">
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group row">
                                <label class="col-sm-0 col-form-label">Referencia:</label>
                                <input type="text" class="form-control" id="txtreferencia" readonly placeholder="Ingrese una referencia" style="width:80%" value="<?php echo isset($_SESSION['proveedor']['referencia']) ?  trim($_SESSION['proveedor']['referencia'])  : '' ?>">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        Traspasos entre Almacenes
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <div class="input-group">
                                    <input type="hidden" id="txtidautot" value="<?php echo isset($_SESSION['traspasos']['txtidautot']) ?  $_SESSION['traspasos']['txtidautot'] : '0' ?>">
                                    <div class="form-group row">
                                        <label class="col-form-label" for="">Sucursal Salida:</label>
                                        <div class="">
                                            <select name="cmbsucursalsalida" id="cmbsucursalsalida" data-width="100%" class="selectpicker" data-live-search="true">
                                                <?php $idalmasalida = 1; ?>
                                                <?php foreach ($sucursales as $row) : ?>
                                                    <?php if ($row['idalma'] == $_SESSION['idalmacen']) : ?>
                                                        <option value=<?php echo $row['idalma'] ?> <?php echo (($row['idalma'] == $idalmasalida ? 'selected' : '')) ?>><?php echo trim($row['nomb']) ?></option>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col"></div>
                            <div class="col">
                                <div class="input-group">
                                    <div class="form-group row">
                                        <label class="col-form-label" for="">Sucursal Ingreso:</label>
                                        <div class="">
                                            <select name="cmbsucursalingreso" id="cmbsucursalingreso" data-width="100%" class="selectpicker" data-live-search="true">
                                                <?php $idalmaingreso = 2; ?>
                                                <?php foreach ($sucursales as $row) : ?>
                                                    <?php if ($row['idalma'] != $_SESSION['idalmacen']) : ?>
                                                        <option value=<?php echo $row['idalma'] ?> <?php echo (($row['idalma'] == $idalmaingreso ? 'selected' : '')) ?>><?php echo trim($row['nomb']) ?></option>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
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
                                        <input type="hidden" id="txtIdTransportista" value="<?php echo isset($_SESSION['transportista']['txtIdTransportista']) ?  $_SESSION['transportista']['txtIdTransportista'] : '0' ?>">
                                        <input type="text" class="form-control form-control-sm" id="txttransportista" placeholder="Transportista" disabled value="<?php echo isset($_SESSION['transportista']['txttransportista']) ?  $_SESSION['transportista']['txttransportista'] : '' ?>">
                                        <input type="text" class="form-control form-control-sm" id="txttruc" placeholder="Ruc" disabled value="<?php echo isset($_SESSION['transportista']['txtruc']) ?  $_SESSION['transportista']['txtruc'] : '' ?>">
                                        <button class="btn btn-outline-light" role="button" data-bs-toggle="modal" data-bs-target="#modal_transportista"><i style="color:black" class="fas fa-user-alt"></i></button>
                                    </div>
                                    <div class="input-group">
                                        <input type="text" class="form-control form-control-sm" id="txtplaca" placeholder="Placa 01" disabled value="<?php echo isset($_SESSION['transportista']['txtPlaca']) ?  $_SESSION['transportista']['txtPlaca'] : '' ?>">
                                        <input type="text" class="form-control form-control-sm" placeholder="Placa 02" disabled id="txtPlaca1" value="<?php echo isset($_SESSION['transportista']['txtPlaca1']) ?  $_SESSION['transportista']['txtPlaca1'] : '' ?>">
                                        <input type="text" class="form-control form-control-sm" id="txtmarca" placeholder="Marca" disabled value="<?php echo isset($_SESSION['transportista']['txtmarca']) ?  $_SESSION['transportista']['txtmarca'] : '' ?>">
                                        <button class="btn btn-outline-light" role="button" data-bs-toggle="modal" data-bs-target=""><i style="color:black" class="fa fa-truck"></i></button>
                                    </div>
                                    <div class="input-group">
                                        <input type="text" class="form-control form-control-sm" id="txtChoferVehiculo" placeholder="Chofer" disabled value="<?php echo isset($_SESSION['transportista']['txtChoferVehiculo']) ?  $_SESSION['transportista']['txtChoferVehiculo'] : '' ?>">
                                        <input type="text" class="form-control form-control-sm" id="txtbrevete" placeholder="Brevete" disabled value="<?php echo isset($_SESSION['transportista']['txtbrevete']) ?  $_SESSION['transportista']['txtbrevete'] : '' ?>">
                                        <button class="btn btn-outline-light" role="button" data-bs-toggle="modal" data-bs-target="#modal_choferes"><i style="color:black" class="fa fa-id-card-o" aria-hidden="true"></i></button>
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
</div>
<input type="hidden" name="" id="idautoc" value="0">
<div id="divpresentaciones"></div>
<div id="cargamodalcompras"></div>
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
            axios.get('/traspasos/listardetalle').then(function(respuesta) {
                const contenido_tabla = respuesta.data;
                $('#detalle').html(contenido_tabla);
            }).catch(function(error) {
                toastr.error('Error al cargar el listado' + error, 'Mensaje del sistema')
            });
        }
        $(".codigo").css("display", "none");
        $("#btncrearproductomodal").css("display", "none");
        // calcularIGV();
    }

    // function activarreferencia() {
        // $("#txtreferencia").removeAttr("readonly");
        // txtreferencia = $("#txtreferencia").val();
        // $("#txtreferencia").val(txtreferencia + " ");
        // moverCursorFinalTexto("#txtreferencia");
    // }

    function listardetalle() {
        axios.get('/traspasos/listardetalle').then(function(respuesta) {
            const contenido_tabla = respuesta.data;
            $('#detalle').html(contenido_tabla);
        }).catch(function(error) {
            toastr.error('Error al cargar el listado' + error, 'Mensaje del sistema')
        });
    }

    function modalcompras() {
        axios.get('/guiasc/listarcomprastocanje', {})
            .then(function(respuesta) {
                const contenido_tabla = respuesta.data;
                $('#cargamodalcompras').html(contenido_tabla);
                $("#modal_compras").modal('show');
            }).catch(function(error) {
                toastr.error('Error al cargar el listado' + error, 'Mensaje del sistema')
            });
    }

    // function seleccionarcompra(datos) {
    // $("#txtidproveedor").val(datos.parametro3);
    // $("#txtUbigeoproveedor").val(datos.parametro6);
    // $("#txtproveedor").val(datos.parametro7)
    // $("#txtptopartida").val(datos.parametro4);
    // $("#txtrucproveedor").val(datos.parametro5)
    // $("#txtreferencia").val(datos.parametro8)
    // $("#idautoc").val(datos.parametro1);
    // axios.get('/traspasos/listardetallecompratocanje', {
    //     "params": {
    //         "idauto": datos.parametro1
    //     }
    // }).then(function(respuesta) {
    //     const contenido_tabla = respuesta.data;
    //     $('#detalle').html(contenido_tabla);
    //     calcularPesoTotal();
    // }).catch(function(error) {
    //     toastr.error('Error al cargar el listado', 'Mensaje del Sistema')
    // });
    // $("#modal_compras").modal('hide');
    // }

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
        data.append("txtprecio", Number(datos.parametro9).toFixed(2));
        data.append("txtcantidad", 1);
        data.append("precio1", datos.parametro5);
        data.append("precio2", datos.parametro6);
        data.append("precio3", datos.parametro7);
        data.append("costo", datos.parametro8);
        data.append("presentaciones", datos.parametro11);
        data.append("presseleccionada", eptaidep);
        data.append("stockuno", datos.stockuno);
        data.append("stockdos", datos.stockdos);
        data.append("stocktre", datos.stocktre);
        data.append("cantequi", cantequi);
        data.append("stock", parseFloat(datos.parametro4.toFixed(2)));
        data.append("opt", 0)
        axios.post('/traspasos/agregaritem', data)
            .then(function(respuesta) {
                //window.location.href = '/vtas/index';
                $('#modal_productos').modal('hide')
                const contenido_tabla = respuesta.data;
                $('#detalle').html(contenido_tabla);
                calcularPesoTotal();
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
        axios.post('/traspasos/quitaritem', data)
            .then(function(respuesta) {
                const contenido_tabla = respuesta.data;
                $('#detalle').html(contenido_tabla);
                calcularPesoTotal();
                // $('#totalpedido').html(document.querySelector("#total").value);
            }).catch(function(error) {
                toastr.error('Ocurrió un error' + error, 'Mensaje del sistema');
            });
    }

    function cancelarTraspaso() {
        axios.post('/traspasos/limpiar').then(function(respuesta) {
            const tabla = respuesta.data;
            $('#detalle').html(tabla);
            limpiardatos();
            window.location.href = '/traspasos/index';
        }).catch(function(error) {
            toastr.error(error, 'Mensaje del sistema');
        });
    }

    function limpiardatos() {
        // document.querySelector('#txtproveedor').value = "";
        // document.getElementById("titulo").innerHTML = "Regs. Compra";
        // document.getElementById("grabar").innerHTML = "Grabar";
        // document.querySelector("#txtidproveedor").value = "0";
        // document.querySelector("#cndoc1").value = "";
        // document.querySelector("#cndoc2").value = "";
        // document.querySelector("#ndo2").value = "";
        // document.querySelector("#cmbforma").value = "E";
        // // document.querySelector("#cmbAlmacen").value = "1";
        // document.querySelector("#cmbmoneda").value = "S";
        // document.querySelector('#txtdolar').value = "";
        document.getElementById("txtplaca").value = "";
        document.getElementById("txtPlaca1").value = "";
        document.getElementById("txtbrevete").value = "";
        document.getElementById("txtChoferVehiculo").value = "";
        document.getElementById("txttransportista").value = "";
        document.getElementById("txtIdTransportista").value = "0";
        document.getElementById("txttruc").value = "";
        document.getElementById("txtmarca").value = "";
        document.getElementById("txtregmtc").value = "";
        document.getElementById("txttipot").value = "";
        $("#idautoc").val("0");
        <?php
        session()->remove('transportista');
        ?>
    }

    function validarTraspaso() {
        // idProv = document.querySelector('#txtidproveedor').value;
        // total = document.querySelector('#total').value;
        // cndoc1 = $("#cndoc1").val();
        // cndoc2 = $("#cndoc2").val();
        // if (cndoc1 == '') {
        //     toastr.info("Dígite la serie","Mensaje del Sistema");
        //     return false;
        // }
        // if (cndoc2 == '') {
        //     toastr.info("Dígite el número","Mensaje del Sistema");
        //     return false;
        // }
        // if (idProv == 0) {
        //     toastr.info("Seleccione un proveedor","Mensaje del Sistema");
        //     return false;
        // }
        // if (total == 0) {
        //     toastr.info("Ingrese importes válidos","Mensaje del Sistema");
        //     return false;
        // }
        return true;
    }

    function grabarTraspaso() {
        if (!validarTraspaso()) {
            return;
        }
        var cmensaje = "";
        if (document.querySelector('#txtidautot').value == '0') {
            cmensaje = '¿Registrar Traspaso?';
            grabar(cmensaje);
        }
        // else {
        // cmensaje = '¿Actualizar Traspaso?';
        // actualizar(cmensaje);
        //}
    }

    function grabar(cmensaje) {
        txtIdTransportista = $("#txtIdTransportista").val();
        if (txtIdTransportista.length == 0) {
            toastr.error("Ingrese un transportista", 'Mensaje del Sistema')
            return;
        }
        if (txtIdTransportista == '0') {
            toastr.error("Ingrese un transportista", 'Mensaje del Sistema')
            return;
        }
        total = $("#total").val();
        if (Number(total) == 0) {
            toastr.error("Agregue productos al traspaso", 'Mensaje del Sistema')
            return;
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
                // let tdoc = document.getElementById("cmbdcto").value;
                // let num = document.querySelector("#cndoc2").value
                // if (num.length < 8) {
                //     while (num.length < 8)
                //         num = '0' + num;
                // }
                // let cndoc = (document.querySelector("#cndoc1").value + num).toUpperCase();
                // let form = document.getElementById("cmbforma").value;
                // let ndo2 = document.querySelector("#ndo2").value;
                // let mon = document.getElementById("cmbmoneda").value;
                // let fechi = document.getElementById("txtfechai").value;
                // let fechf = document.getElementById("txtfechaf").value;
                // let dolar = document.getElementById("txtdolar").value;
                // let idprov = document.getElementById("txtidproveedor").value;
                // let alm = document.getElementById("cmbAlmacen").value;
                // let valor = document.querySelector("#subtotal").value;
                // let nigv = document.querySelector("#igv").value;
                // let igv = obtenerTipoIGV();
                // "valor" => $request->get("valor"),
                // "nigv" => $request->get("nigv"),
                // "impo" => $request->get("impo"),

                data = new FormData();
                // data.append("tdoc", tdoc);
                // data.append("cndoc", cndoc);
                // data.append("form", form);
                // data.append("fechi", fechi);
                // data.append("fechf", fechf);
                data.append("referencia", document.querySelector("#txtreferencia").value);
                // data.append("valor", valor);
                // data.append("nigv", nigv);
                data.append("impo", document.querySelector("#total").value);
                data.append("sucursalsalida", $("#cmbsucursalsalida").val());
                data.append("sucursalingreso", $("#cmbsucursalingreso").val());
                data.append("fechaemision", $("#txtFechaEmision").val());
                data.append("fechatraslado", $("#txtFechaTraslado").val());
                data.append("txtidautot", $("#txtidautot").val());
                data.append("txtPlaca", $("#txtplaca").val());
                data.append("txtmarca", $("#txtmarca").val());
                data.append("txtreferencia", $("#txtreferencia").val());
                data.append("txtBrevete", $("#txtbrevete").val());
                data.append("txtructransportista", $("#txttruc").val());
                data.append("txttransportista", $("#txttransportista").val());
                data.append("txtIdTransportista", $("#txtIdTransportista").val());
                data.append("txtChoferVehiculo", $("#txtChoferVehiculo").val());
                data.append("txtregmtc", $("#txtregmtc").val());
                data.append("idautoc", $("#idautoc").val());
                // data.append("ndo2", ndo2);
                // data.append("mon", mon);
                // data.append("dolar", dolar);
                // data.append("idprov", idprov);
                // data.append("txtproveedor", $("#txtproveedor").val());
                // data.append("pimpo", $("#txtpercepcion").val());
                // data.append("cmbtipodocumentocuentasxpagar", $("#cmbtipodocumentocuentasxpagar").val());
                // data.append("alm", alm);
                // data.append("igv", igv);
                // data.append("cuentasxpagar", JSON.stringify(detalle));
                // data.append("actualizarprecios", $("#actualizarprecios").val());
                // data.append("exonerado", $("#exonerado").val())
                axios.post("/traspasos/registrar", data)
                    .then(function(respuesta) {
                        const tabla = respuesta.data;
                        $('#detalle').html(tabla);
                        // $('#totalpedido').html(document.querySelector("#total").value);
                        // nropedido = document.querySelector("#nropedido").value;
                        Swal.fire({
                            title: "Traspaso registrado",
                            text: "Se generó la compra correctamente",
                            icon: "success"
                        });
                        // cancelarTraspaso();
                        var cruta = '/traspasos/imprimirdirecto/';
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
                        limpiardatos();
                        listardetalle();
                        // toastr.success("Traspaso generado satisfactoriamente", "Mensaje del Sistema")
                    }).catch(function(error) {
                        if (error.hasOwnProperty("response")) {
                            if (error.response.status === 422) {
                                //mostrarErrores("formulario-agregar-presentacion", error.response.data.errors);
                                toastr.error(error.response.data.message, 'Mensaje del sistema');
                            }
                        } else {
                            toastr.error("Error al registrar traspaso", "Mensaje del Sistema");
                        }
                    });
            }
        });
    }

    function grabarCabecera() {
        // let idprov = document.getElementById("txtidproveedor").value;
        // let razo = document.getElementById("txtproveedor").value;
        // let tdoc = document.getElementById("cmbdcto").value;
        // let cndoc = document.querySelector("#cndoc1").value;
        // let num = document.querySelector("#cndoc2").value;
        // let ndo2 = document.querySelector("#ndo2").value;
        // let form = document.getElementById("cmbforma").value;
        // let deta = document.querySelector("#txtdetalle").value;
        // let mone = document.getElementById("cmbmoneda").value;
        // let fechi = document.getElementById("txtfechai").value;
        // let fechf = document.getElementById("txtfechaf").value;
        // let dolar = document.getElementById("txtdolar").value;
        // let alm = document.getElementById("cmbAlmacen").value;
        // var optigv = obtenerTipoIGV();
        // data = new FormData();
        // data.append("idprov", idprov);
        // data.append("razo", razo);
        // data.append("tdoc", tdoc);
        // data.append("cndoc", cndoc);
        // data.append("num", num);
        // data.append("ndo2", ndo2);
        // data.append("alm", alm);
        // data.append("form", form);
        // data.append("mone", mone);
        // data.append("fechi", fechi);
        // data.append("fechf", fechf);
        // data.append("dolar", dolar);
        // data.append("deta", deta);
        // data.append("optigv", optigv);
        // axios.post("/compras/sesion", data)
        //     .then(function(respuesta) {
        //         // console.log("Se registro la cabecera en la sesión")
        //     }).catch(function(error) {
        //         toastr.error("Error al guardar sesión", "Mensaje del Sistema");
        //     });
    }

    $('#modal_transportista').on('shown.bs.modal', function() {
        $('#txtbuscarTr').focus();
    });

    function calcularPesoTotal() {
        var total_col = 0;
        $('#griddetalle tbody').find('tr').each(function(i, el) {
            columantotal = "<?php echo empty($_SESSION['config']['tipobotica']) ? 6 : 8; ?>";
            total_col += parseFloat($(this).find('td').eq(columantotal).find("input").val());
        });

        let impo = (Number(total_col)).toFixed(2);
        $("#total").val(impo);
        let impor = $("#total").val();

        if (isNaN(impor)) {
            $("#total").val("0.00");
        }
    }

    // function actualizar(cmensaje, actualizarprecios) {
    //     Swal.fire({
    //         title: cmensaje,
    //         text: "Se actualizará en el sistema ",
    //         icon: 'question',
    //         showCancelButton: true,
    //         confirmButtonColor: '#3085d6',
    //         cancelButtonColor: '#d33',
    //         confirmButtonText: 'Si'
    //     }).then(function(respuesta) {
    //         if (respuesta.isConfirmed) {
    //             let tdoc = document.getElementById("cmbdcto").value;
    //             let num = document.querySelector("#cndoc2").value
    //             if (num.length < 8) {
    //                 while (num.length < 8)
    //                     num = '0' + num;
    //             }
    //             let cndoc = (document.querySelector("#cndoc1").value + num).toUpperCase();
    //             let form = document.getElementById("cmbforma").value;
    //             let deta = document.querySelector("#txtdetalle").value;
    //             let impo = document.querySelector("#total").value;
    //             let ndo2 = document.querySelector("#ndo2").value;
    //             let mon = document.getElementById("cmbmoneda").value;
    //             let fechi = document.getElementById("txtfechai").value;
    //             let fechf = document.getElementById("txtfechaf").value;
    //             let dolar = document.getElementById("txtdolar").value;
    //             let idprov = document.getElementById("txtidproveedor").value;
    //             let alm = document.getElementById("cmbAlmacen").value;
    //             let valor = document.querySelector("#subtotal").value;
    //             let nigv = document.querySelector("#igv").value;
    //             let igv = obtenerTipoIGV();
    //             data = new FormData();
    //             data.append("tdoc", tdoc);
    //             data.append("cndoc", cndoc);
    //             data.append("form", form);
    //             data.append("fechi", fechi);
    //             data.append("fechf", fechf);
    //             data.append("deta", deta);
    //             data.append("valor", valor);
    //             data.append("nigv", nigv);
    //             data.append("impo", impo);
    //             data.append("ndo2", ndo2);
    //             data.append("mon", mon);
    //             data.append("dolar", dolar);
    //             data.append("idprov", idprov);
    //             data.append("txtproveedor", $("#txtproveedor").val());
    //             data.append("alm", alm);
    //             data.append("pimpo", $("#txtpercepcion").val());
    //             data.append("igv", igv);
    //             data.append("actualizarprecios", $("#actualizarprecios").val());
    //             data.append("exonerado", $("#exonerado").val())
    //             axios.post("/compras/actualizar", data)
    //                 .then(function(respuesta) {
    //                     const tabla = respuesta.data;
    //                     // console.log(data);
    //                     $('#detalle').html(tabla);
    //                     // $('#totalpedido').html(document.querySelector("#total").value);
    //                     // nropedido = document.querySelector("#nropedido").value;
    //                     cancelarTraspaso();
    //                     limpiardatos();
    //                     Swal.fire(' Se actualizó la Compra satisfactoriamente ');
    //                 }).catch(function(error) {
    //                     if (error.hasOwnProperty("response")) {
    //                         if (error.response.status === 422) {
    //                             //mostrarErrores("formulario-agregar-presentacion", error.response.data.errors);
    //                             toastr.error(error.response.data.errors, 'Mensaje del sistema');
    //                         }
    //                     } else {
    //                         toastr.error("Error al registrar compra" + error, "Mensaje del sistema");
    //                     }
    //                 });
    //         }
    //     });
    // }

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
            axios.post('/traspasos/EditarUno', data)
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
            //_tr.find("td").eq(5).find("input").val(Number(cmbpresentacion[1]).toFixed(2));
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
            axios.post('/traspasos/EditarUno', data)
                .then(function(respuesta) {
                    calcularPesoTotal();
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
    function calcularsubtotal(o) {
        var _tr = $(o);
        var cant = _tr.find("td").eq(4).find("input").val();
        var prec = _tr.find("td").eq(5).find("input").val();
        columantotal = "<?php echo empty($_SESSION['config']['tipobotica']) ? 6 : 8; ?>";
        var campo = _tr.find("td").eq(columantotal).find("input");

        if (clicksubtotal == 0) {
            var subt = parseFloat(cant) * parseFloat(prec);
            if (isNaN(subt)) {
                toastr.info("Dígite un número correcto", 'Mensaje del Sistema')
            } else {
                $(campo).val(subt.toFixed(2));
                $('#griddetalle tbody').find('tr').each(function(i, el) {
                    calcularPesoTotal();
                });
            }
        } else {
            campo = $(campo).val();
            prec = campo / cant;
            _tr.find("td").eq(5).find("input").val(Number(prec).toFixed(2));
            $('#griddetalle tbody').find('tr').each(function(i, el) {
                calcularPesoTotal();
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
</script>
<?php
$this->endSection("javascript");
?>