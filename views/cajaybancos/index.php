<?php

use App\View\Components\ModalProveedorComponent;
use App\View\Components\PlanesComponent;

$this->setLayout('layouts/admin');
?>
<?php
$this->startSection('contenido');
?>
<?php
$prov = new ModalProveedorComponent();
echo $prov->render();
?>
<div class="content-wrapper">
    <div class="content">
        <div class="container-fluid">
            <div class="col-lg-12">
                <div class="card card-primary card-outline">
                    <div class="card-body">
                        <div class="row g-2 align-items-center">
                            <div class="col-auto">
                                <label for="" class="col-form-label col-form-label-sm">Código Cta.:</label>
                            </div>
                            <div class="col-md-10">
                                <select class="form-control form-control-sm" id="cmbctas">
                                    <?php foreach ($listacuentasbanco as $lcb) : ?>
                                        <option value="<?php echo $lcb['ctas_idct'] ?>"><?php echo $lcb['ctas_ctas'] . ' ------------------------ BANCO: ' . $lcb['banc_nomb'] . ' ------------------------   MONEDA: ' . $lcb['ctas_mone'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="row g-3 align-items-center">
                            <div class="col-auto">
                                <label for="" class="col-form-label col-form-label-sm ">Fecha Emi.:</label>
                            </div>
                            <div class="col-md-2 ">
                                <input type="date" style="" class="form-control form-control-sm" onblur="consultarvalordolar();" id="txtfechai" placeholder="" value="<?php echo (empty($datos) ? date('Y-m-d') : $datos[0]['fech']) ?>">
                            </div>
                        </div>
                        <div class="row g-3 align-items-center">
                            <div class="col-auto">
                                <label for="" class="col-form-label col-form-label-sm">N°. Cuenta:</label>
                            </div>
                            <div class="col-md-10">
                                <select class="form-control form-control-sm" id="cmbnrocuentas">
                                    <?php foreach ($listarplanescontables as $pc) : ?>
                                        <option value="<?php echo $pc['idcta'] ?>"><?php echo $pc['ncta'] . ' ------------------------ NOMBRE: ' . $pc['nomb'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <hr>
                        <div class="row g-3 align-items-center">
                            <div class="col-auto">
                                <label for="" class="col-form-label col-form-label-sm">Tipo Mov. :</label>
                            </div>
                            <div class="col-md-2">
                                <select name="cmbtipomov" class="form-control form-control-sm text-center" onchange="listardocumentoxcancelar();" id="cmbtipomov">
                                    <option value="I">INGRESOS</option>
                                    <option value="E">EGRESOS</option>
                                </select>
                            </div>
                            <div class="col-auto">
                                <label for="" class="col-form-label col-form-label-sm">
                                    <button class="btn btn-success btn-sm" onclick="abrirmodallistamovimientos()">LISTA DE MOVIMIENTOS</button>
                                </label>
                            </div>
                        </div>
                        <div class="row g-3 align-items-center">
                            <div class="col-auto ">
                                <label for="" class="col-form-label col-form-label-sm">N°. Opera :</label>
                            </div>
                            <div class="col-md-2 ">
                                <div class="input-group">
                                    <input type="text" class="form-control form-control-sm" id="txtnrooperacion" placeholder="Número de Operación" value="">
                                </div>
                            </div>
                        </div>
                        <div class="row g-3 align-items-center">
                            <div class="col-auto ">
                                <label for="" class="col-form-label col-form-label-sm">T. Cambio :</label>
                            </div>
                            <div class="col-md-2 ">
                                <div class="input-group">
                                    <input type="text" onkeypress="return isNumber(event);" class="form-control form-control-sm text-end" id="txttipocambio" placeholder="0.00" value="" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row g-3 align-items-center">
                            <div class="col-auto ">
                                <label for="" class="col-form-label col-form-label-sm">Interés .:</label>&ensp;&ensp;
                            </div>
                            <div class="col-md-2 ">
                                <div class="input-group">
                                    <input type="text" onkeyup="sumaraltotal();" onkeypress="return isNumber(event);" class="form-control form-control-sm text-end" onclick="this.select();" id="txtinteres" placeholder="0.00" value="0">
                                </div>
                            </div>
                            <?php
                            $cmbinteres = new PlanesComponent('67', 'cmbintereses', 'txtintereses', '', '');
                            echo $cmbinteres->render();
                            ?>
                        </div>
                        <div class="row g-3 align-items-center">
                            <div class="col-auto ">
                                <label for="" class="col-form-label col-form-label-sm">Comisión .:</label>
                            </div>
                            <div class="col-md-2 ">
                                <div class="input-group">
                                    <input type="text" onkeyup="sumaraltotal();" onkeypress="return isNumber(event);" class="form-control form-control-sm text-end" onclick="this.select();" id="txtcomision" placeholder="0.00" value="0">
                                </div>
                            </div>
                            <?php
                            $cmbcomisiones = new PlanesComponent('63', 'cmbcomisiones', 'txtcomisiones', '', '');
                            echo $cmbcomisiones->render();
                            ?>
                        </div>
                        <div class="row g-3 align-items-center">
                            <div class="col-auto ">
                                <label for="" class="col-form-label col-form-label-sm">Total :</label>&ensp;&ensp;&ensp;&ensp;
                            </div>
                            <div class="col-md-2 ">
                                <div class="input-group">
                                    <input type="text" onkeypress="return isNumber(event);" class="form-control form-control-sm text-end" onfocus="this.select();" id="txttotal" placeholder="0.00" value="">
                                </div>
                            </div>
                        </div>
                        <div class="row g-3 align-items-center">
                            <div class="col-auto ">
                                <label for="" class="col-form-label col-form-label-sm">Med. Pago:</label>
                            </div>
                            <div class="col-md-5">
                                <div class="input-group">
                                    <select name="cmbmediopago" class="form-control form-control-sm" id="cmbmediopago">
                                        <?php foreach ($listampagos as $lmp) : ?>
                                            <option value="<?php echo $lmp['pago_idpa'] ?>"><?php echo $lmp['pago_deta'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row g-3 align-items-center">
                            <div class="col-auto ">
                                <label for="" class="col-form-label col-form-label-sm">Detalle:</label>&ensp;&ensp;&ensp;
                            </div>
                            <div class="col-md-5">
                                <div class="input-group">
                                    <input type="text" class="form-control form-control-sm" name="" id="txtreferencia" placeholder="Referencia">
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row text-end">
                            <div class="col-8">
                            </div>
                            <div class="col-4 text-end">
                                <button class="btn btn-success" id="btngrabar" onclick="grabar();">Grabar</button>
                                <button class="btn btn-danger" onclick="limpiarcampos();">Limpiar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="mddocumentosxcancelar" tabindex="-1" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="">Lista de Documentos Pendientes a Cancelar</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="divdocumentosxcancelar">
            </div>
            <div class="modal-footer">
                <!-- <button type="button" class="btn btn-primary">Agregar</button> -->
                <button type="button" class="btn btn-warning" onclick="limpiardocumentosagregados()">Limpiar</button>
            </div>
        </div>
    </div>
</div>
<?php
$this->endSection("contenido");
?>
<?php
$this->startsection("javascript");
?>
<style>
    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    input[type=number] {
        text-align: right;
    }
</style>
<script>
    window.onload = function() {
        titulo("<?php echo $titulo ?>");
        consultarvalordolar();
        listardocumentoxcancelar();
    }

    $('#mddocumentosxcancelar').on('hidden.bs.modal', function() {
        calculartotalpordetalle();
    });

    function calculartotalpordetalle() {
        total = 0;
        cmbtipomov = $("#cmbtipomov").val();
        if (cmbtipomov == 'I') {
            $("#tblingresos tbody tr").each(function() {
                $(this).find("td").each(function() {
                    $this = $(this);
                    if ($this.attr("class") == "inputcancelacion") {
                        total += Number($this.find("input").val());
                    }
                });
            });
        } else {
            $("#tblegresos tbody tr").each(function() {
                $(this).find("td").each(function() {
                    $this = $(this);
                    if ($this.attr("class") == "inputcancelacion") {
                        total += Number($this.find("input").val());
                    }
                });
            });
        }
        $("#txttotal").val(total);
    }

    function sumaraltotal() {
        calculartotalpordetalle();
        txttotal = Number($("#txttotal").val());
        txtcomision = Number($("#txtcomision").val());
        txtinteres = Number($("#txtinteres").val());
        $("#txttotal").val(txttotal + txtcomision + txtinteres)
    }

    function listardocumentoxcancelar() {
        cmbtipomov = $("#cmbtipomov").val();
        if (cmbtipomov == 'I') {
            const data = new FormData();
            axios.get('/cajaybancos/listaringresos').then(function(respuesta) {
                const contenido_tabla = respuesta.data;
                $('#divdocumentosxcancelar').html(contenido_tabla);
            }).catch(function(error) {
                toastr.error(error, "Mensaje del sistema");
            });
        } else {
            const data = new FormData();
            axios.get('/cajaybancos/listaregresos').then(function(respuesta) {
                const contenido_tabla = respuesta.data;
                $('#divdocumentosxcancelar').html(contenido_tabla);
            }).catch(function(error) {
                toastr.error(error, "Mensaje del sistema");
            });
        }
        calculartotalpordetalle();
    }

    function abrirmodallistamovimientos() {
        $("#mddocumentosxcancelar").modal('show');
    }

    function consultarvalordolar() {
        const data = new FormData();
        axios.get('/ocompra/getvaluedolar', {
            "params": {
                "fech": $("#txtfechai").val()
            }
        }).then(function(respuesta) {
            $("#txttipocambio").val(respuesta.data.valordolar);
        }).catch(function(error) {
            toastr.error(error, "Mensaje del sistema");
        });
    }

    function limpiardocumentosagregados() {
        cmbtipomov = $("#cmbtipomov").val();
        if (cmbtipomov == 'I') {
            $("#tblingresos tbody tr").each(function() {
                $(this).find("td input").each(function() {
                    $(this).val("0");
                });
            })
        } else {
            $("#tblegresos tbody tr").each(function() {
                $(this).find("td input").each(function() {
                    $(this).val("0");
                });
            })
        }
    }

    function limpiarcampos() {
        $("#txtnrooperacion").val("");
        $("#txtinteres").val("");
        $("#txtcomision").val("");
        $("#txttotal").val("");
        $("#txtreferencia").val("");
        $("#txttotal").css("border", "0.1px solid #9b9b9b")
        $("#txtnrooperacion").css("border", "0.1px solid #9b9b9b")
        listardocumentoxcancelar();
    }

    function grabar() {
        if (!validar()) {
            return;
        }
        cmbtipomov = $("#cmbtipomov").val();
        if (cmbtipomov == 'I') {
            grabaringreso();
        } else {
            grabaregreso();
        }
    }

    function grabaringreso() {
        const detalle = [];
        total = 0;
        saldo = 0;
        $("#tblingresos tbody tr").each(function() {
            json = "";
            $(this).find("td").each(function() {
                $this = $(this);
                if ($this.attr("class") == 'saldo') {
                    saldo = Number($this.text());
                }
                if ($this.attr("class") != "inputcancelacion") {
                    clase = $this.attr("class");
                    clase = clase.replace("d-lg-none ", "");
                    json += ',"' + clase + '":"' + $this.text() + '"'
                } else {
                    json += ',"' + $this.attr("class") + '":"' + $this.find("input").val() + '"';
                    total = Number($this.find("input").val());
                }
                if (total > saldo) {
                    toastr.error("Hay un monto de cancelación mayor al saldo", "Mensaje del sistema")
                    return;
                }
            });
            if (total != 0) {
                obj = JSON.parse('{' + json.substr(1) + '}');
                detalle.push(obj)
            }
        });
        Swal.fire({
            title: "¿Registrar Ingreso?",
            text: "Se insertará en la base de datos.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, registrar'
        }).then(function(respuesta) {
            if (respuesta.isConfirmed) {
                data = new FormData();
                data.append("cmbmediopago", $("#cmbmediopago").val());
                data.append("txtfechai", $("#txtfechai").val());
                data.append("cmbnrocuentas", $("#cmbnrocuentas").val());
                data.append("cmbctas", $("#cmbctas").val());
                data.append("cmbintereses", $("#cmbintereses").val());
                data.append("txtintereses", $("#txtintereses").val());
                data.append("cmbcomisiones", $("#cmbcomisiones").val());
                data.append("txtcomisiones", $("#txtcomisiones").val());
                data.append("txtnrooperacion", $("#txtnrooperacion").val());
                data.append("txttipocambio", $("#txttipocambio").val());
                data.append("txtinteres", $("#txtinteres").val());
                data.append("txtreferencia", $("#txtreferencia").val());
                data.append("txtcomision", $("#txtcomision").val());
                data.append("txttotal", $("#txttotal").val());
                data.append("cuentasxpagar", JSON.stringify(detalle));
                axios.post("/cajaybancos/registraroingresolibro", data)
                    .then(function(respuesta) {
                        Swal.fire({
                            title: "Ingreso registrado satisfactoriamente ",
                            text: respuesta.data.message,
                            icon: "success"
                        });
                        listardocumentoxcancelar();
                        limpiarcampos();
                    }).catch(function(error) {
                        toastr.error(error.response.data, "Mensaje del Sistema");
                        console.log(error);
                    });
            }
        });
    }

    function grabaregreso() {
        const detalle = [];
        total = 0;
        saldo = 0;
        $("#tblegresos tbody tr").each(function() {
            json = "";
            $(this).find("td").each(function() {
                $this = $(this);
                if ($this.attr("class") == 'saldo') {
                    saldo = Number($this.text());
                }
                if ($this.attr("class") != "inputcancelacion") {
                    clase = $this.attr("class");
                    clase = clase.replace("d-lg-none ", "");
                    json += ',"' + clase + '":"' + $this.text() + '"'
                } else {
                    json += ',"' + $this.attr("class") + '":"' + $this.find("input").val() + '"';
                    total = Number($this.find("input").val());
                }
            });
            if (total != 0) {
                obj = JSON.parse('{' + json.substr(1) + '}');
                detalle.push(obj)
            }
        });
        if (total > saldo) {
            toastr.error("Hay un monto de cancelación mayor al saldo", "Mensaje del sistema")
            return;
        }
        Swal.fire({
            title: "¿Registrar Egreso?",
            text: "Se insertará en la base de datos.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, registrar'
        }).then(function(respuesta) {
            if (respuesta.isConfirmed) {
                data = new FormData();
                data.append("cmbmediopago", $("#cmbmediopago").val());
                data.append("txtfechai", $("#txtfechai").val());
                data.append("cmbnrocuentas", $("#cmbnrocuentas").val());
                data.append("cmbctas", $("#cmbctas").val());
                data.append("cmbintereses", $("#cmbintereses").val());
                data.append("txtintereses", $("#txtintereses").val());
                data.append("cmbcomisiones", $("#cmbcomisiones").val());
                data.append("txtcomisiones", $("#txtcomisiones").val());
                data.append("txtnrooperacion", $("#txtnrooperacion").val());
                data.append("txttipocambio", $("#txttipocambio").val());
                data.append("txtinteres", $("#txtinteres").val());
                data.append("txtcomision", $("#txtcomision").val());
                data.append("txtreferencia", $("#txtreferencia").val());
                data.append("txttotal", $("#txttotal").val());
                data.append("cuentasxpagar", JSON.stringify(detalle));
                axios.post("/cajaybancos/registraregresolibro", data)
                    .then(function(respuesta) {
                        Swal.fire({
                            title: "Egreso registrado satisfactoriamente ",
                            text: respuesta.data.message,
                            icon: "success"
                        });
                        listardocumentoxcancelar();
                        limpiarcampos();
                    }).catch(function(error) {
                        toastr.error(error.response.data, "Mensaje del Sistema");
                        console.log(error);
                    });
            }
        });
    }

    function validar() {
        $("#txttotal").css("border", "0.1px solid #9b9b9b")
        $("#txtnrooperacion").css("border", "0.1px solid #9b9b9b")
        txttotal = $("#txttotal").val();
        txtnrooperacion = $("#txtnrooperacion").val();
        if (Number(txttotal) == 0 || txttotal.length < 0) {
            toastr.info("Digite el total", 'Mensaje del sistema');
            $("#txttotal").css("border", "1px solid red")
            return false;
        }
        if (Number(txtnrooperacion) == 0 || txtnrooperacion.length < 0) {
            toastr.info("Digite el número de operación", 'Mensaje del sistema');
            $("#txtnrooperacion").css("border", "1px solid red")
            return false;
        }
        return true;
    }
</script>
<?php
$this->endSection("javascript");
?>