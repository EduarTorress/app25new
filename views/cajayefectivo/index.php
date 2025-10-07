<?php

use App\View\Components\ModalProveedorComponent;
use App\View\Components\PlanesComponent;
use App\View\Components\TipoMonedaComponent;

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
                        <div class="row g-3 align-items-center">
                            <div class="col-auto">
                                <label for="" class="col-form-label col-form-label-sm">Tipo Mov. :</label>
                            </div>
                            <div class="col-md-2">
                                <select name="cmbtipomov" class="form-control form-control-sm text-center" onchange=";" id="cmbtipomov">
                                    <option value="I">INGRESOS</option>
                                    <option value="E">EGRESOS</option>
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
                        <hr>
                        <div class="row g-3">
                            <h6><b>Cuentas Contables:</b></h6>
                        </div>
                        <div class="row g-3 align-items-center text-sm cuentascontables">
                            <?php
                            $cmbcuentas = new PlanesComponent('%%', 'cmbcuentas', 'txtcuentas', '', '');
                            echo $cmbcuentas->render();
                            ?>
                        </div>
                        <hr>
                        <div class="row g-3 align-items-center">
                            <div class="col-auto ">
                                <label for="" class="col-form-label col-form-label-sm">Moneda :</label>&ensp;
                            </div>
                            <div class="col-md-2 ">
                                <div class="input-group">
                                    <select name="select" onchange="verificarmoneda();" class="form-control form-control-sm" id="cmbmoneda">
                                        <option value="S" selected>Soles</option>
                                        <option value="D">Dólares</option>
                                    </select>
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
                                <label for="" class="col-form-label col-form-label-sm">Valor... :</label>&ensp;&ensp;&ensp;
                            </div>
                            <div class="col-md-2 ">
                                <div class="input-group">
                                    <input type="text" onkeyup="verificarmoneda();" onkeypress="return isNumber(event);" class="form-control form-control-sm text-end" onfocus="this.select();" id="txtvalor" placeholder="0.00" value="0">
                                </div>
                            </div>
                        </div>
                        <div class="row g-3 align-items-center">
                            <div class="col-auto ">
                                <label for="" class="col-form-label col-form-label-sm">Importe. :</label>&ensp;
                            </div>
                            <div class="col-md-2 ">
                                <div class="input-group">
                                    <input type="text" readonly onkeypress="return isNumber(event);" class="form-control form-control-sm text-end" onclick="this.select();" id="txttotal" placeholder="0.00" value="0">
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
<div class="modal fade" id="mdcuentas" tabindex="-1" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="">Registrar Datos a Libro Diario</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <h6><b>Cuentas Contables:</b></h6>
                </div>
                <div class="row g-3 align-items-center text-sm ocuentascontables">
                    <?php
                    $cmbocuentas = new PlanesComponent('%%', 'cmbocuentas', 'txtocuentas', '', '');
                    echo $cmbocuentas->render();
                    ?>
                    <input type="hidden" id="txtctarelaciondebe" value="">
                    <input type="hidden" id="txtnamectarelaciondebe" value="">
                    <input type="hidden" id="txtctarelacionhaber" value="">
                    <input type="hidden" id="txtnamectarelacionhaber" value="">
                    <div class="col-auto">
                        <button class="btn btn-primary" type="button" onclick="agregarcuentadetalle();">Agregar</button>
                    </div>
                </div>
                <hr>
                <div>
                    <div class="input-group mb-3">
                        <select class="form-control" name="cmbtiporegistrro" id="cmbtiporegistrro">
                            <option value="A">ABONAR</option>
                            <option value="C">CARGAR</option>
                        </select>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-6">
                        <h6><b>Cuentas Destino:</b></h6>
                        <div class="table-responsive">
                            <table id="tblcuentasagregadas" class="table table-striped table-sm text-sm">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Nombre</th>
                                        <th scope="col">Debe</th>
                                        <th scope="col">Haber</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-6">
                        <h6><b>Asientos:</b></h6>
                        <div class="table-responsive">
                            <table id="tblcuentasrelacionadas" class="table table-striped text-sm">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Nombre</th>
                                        <th scope="col">Debe</th>
                                        <th scope="col">Haber</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <br>
                    <div class="col-3">
                        <div class="input-group mb-3">
                            <span class="input-group-text" id=""><b>Debe :</b></span>
                            <input type="text" id="txttotaldebe" class="form-control text-end" readonly value="0">
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="input-group mb-3">
                            <span class="input-group-text text-sm" id=""><b>Haber :</b></span>
                            <input type="text" id="txttotalhaber" class="form-control text-end" readonly value="0">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success">Registrar</button>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar / Limpiar</button>
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
    }

    function entertest() {}

    $("#cmbocuentas").on("change", function() {
        cname = $("#cmbocuentas").val();
        cname = cname.split("&");
        $("#txtocuentas").val(cname[1]);
        $("#txtctarelaciondebe").val(cname[2].trim());
        $("#txtctarelacionhaber").val(cname[3].trim());
        cnoametext = $("#cmbocuentas option:selected").text();
        cmnoamectatext = (cnoametext.substring(0, 2));
        if (cmnoamectatext == '42') {
            $(".ocuentascontables").find(".txtdebeohaber").val("HABER")
        } else {
            $(".ocuentascontables").find(".txtdebeohaber").val("DEBE")
        }
    });

    $("#cmbcuentas").on("change", function() {
        cname = $("#cmbcuentas option:selected").text();
        cmnamecta = (cname.substring(0, 2));
        if (cmnamecta == '42') {
            $(".cuentascontables").find(".txtdebeohaber").val("HABER")
        } else {
            $(".cuentascontables").find(".txtdebeohaber").val("DEBE")
        }
    });

    function verificarmoneda() {
        cmbmoneda = $("#cmbmoneda").val();
        txttipocambio = $("#txttipocambio").val();
        txtvalor = $("#txtvalor").val();
        txttotal = 0;
        if (cmbmoneda == 'D') {
            txttotal = Number(txtvalor) * Number(txttipocambio);
        } else {
            txttotal = Number(txtvalor);
        }
        $("#txttotal").val(txttotal.toFixed(2))
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

    function limpiarcampos() {
        $("#txttotal").val("0");
        $("#txtvalor").val("0");
        $("#txtreferencia").val("");
    }

    function grabar() {
        if (!validar()) {
            return;
        }
        cmbtipomov = $("#cmbtipomov").val();
        if (cmbtipomov == 'I') {
            grabaringreso();
        } else {
            abrirmodalegresocuentas();
        }
    }

    function grabaringreso() {
        Swal.fire({
            title: "¿Registrar Ingreso a Libro Caja Efectivo?",
            text: "Se insertará en la base de datos.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, registrar'
        }).then(function(respuesta) {
            if (respuesta.isConfirmed) {
                data = new FormData();
                data.append("txtfechai", $("#txtfechai").val());
                data.append("cmbcuentas", $("#cmbcuentas").val());
                data.append("txtcuentas", $("#txtcuentas").val());
                data.append("cmbmoneda", $("#cmbmoneda").val());
                data.append("txtvalor", $("#txtvalor").val());
                data.append("txttotal", $("#txttotal").val());
                data.append("txttipocambio", $("#txttipocambio").val());
                data.append("txtreferencia", $("#txtreferencia").val());
                axios.post("/cajayefectivo/registraringresolibro", data)
                    .then(function(respuesta) {
                        Swal.fire({
                            title: "Ingreso registrado satisfactoriamente ",
                            text: respuesta.data.message,
                            icon: "success"
                        });
                        limpiarcampos();
                    }).catch(function(error) {
                        toastr.error(error.response.data, "Mensaje del Sistema");
                        console.log(error);
                    });
            }
        });
    }

    function abrirmodalegresocuentas() {
        $("#mdcuentas").modal('show');
        calculartotaldebeyhaber();
    }

    function calculartotaldebeyhaber() {
        var total_coldebe = 0;
        var total_colhaber = 0;
        $('#tblcuentasagregadas tbody').find('tr').each(function(i, el) {
            td = $(this).find('td').eq(2).text();
            total_coldebe += parseFloat(td);
            th = $(this).find('td').eq(3).text();
            total_colhaber += parseFloat(th);
        });
        $("#txttotalhaber").val(total_colhaber);
        $("#txttotaldebe").val(total_coldebe);
    }

    $('#mdcuentas').on('shown.bs.modal', function() {
        $('#tblcuentasagregadas tbody').empty();
        $('#tblcuentasrelacionadas tbody').empty();
        cmbcuentas = $("#cmbcuentas").val();
        txtcuentas = $("#txtcuentas").val()
        namecmbcuentas = $("#cmbcuentas option:selected").text();
        txtdebeohaber = $(".cuentascontables").find(".txtdebeohaber").val();
        txttotal = $("#txttotal").val();
        tdstring = "<td class='text-end'>0</td><td class='text-end'>" + txttotal + "</td>";
        if (txtdebeohaber.trim() == 'DEBE') {
            tdstring = "<td class='text-end'>" + txttotal + "</td><td class='text-end'>0</td>";
        }
        $("#tblcuentasagregadas").find('tbody').append("<tr><td>" + namecmbcuentas + "</td><td>" + txtcuentas + "</td>" + tdstring + "</tr>");
        calculartotaldebeyhaber();
    });

    function agregarcuentadetalle() {
        cmbocuentas = $("#cmbocuentas").val();
        txtocuentas = $("#txtocuentas").val()
        namecmbocuentas = $("#cmbocuentas option:selected").text();
        y = 0;
        $('#tblcuentasagregadas tbody').find('tr').each(function(i, el) {
            x = $(this).find('td').eq(0).text();
            if (x.trim() == namecmbocuentas) {
                y = 1;
            }
        });
        if (y == 1) {
            toastr.error("Cuenta ya agregada", 'Mensaje del Sistema');
            return;
        }
        txtdebeohaber = $(".ocuentascontables").find(".txtdebeohaber").val();
        tdstring = "<td class='text-end'>0</td><td class='text-end'>" + txttotal + "</td>";
        if (txtdebeohaber.trim() == 'DEBE') {
            tdstring = "<td class='text-end'>" + txttotal + "</td><td class='text-end'>0</td>";
        }
        $("#tblcuentasagregadas").find('tbody').append("<tr><td>" + namecmbocuentas + "</td><td>" + txtocuentas + "</td>" + tdstring + "</tr>");
        calculartotaldebeyhaber();
        txtctarelaciondebe = $("#txtctarelaciondebe").val();
        axios.get('/planescontables/getctabynro', {
            "params": {
                "cbuscar": txtctarelaciondebe
            }
        }).then(function(respuesta) {
            if (respuesta.data.estado == '1') {
                tdstring = "<td class='text-end'>0</td><td class='text-end'>" + txttotal + "</td>";
                cmnamecta = (respuesta.data.data.ncta.substring(0, 2));
                if (cmnamecta == '42') {
                    tdstring = "<td class='text-end'>" + txttotal + "</td><td class='text-end'>0</td>";
                }
                $("#tblcuentasrelacionadas").find('tbody').append("<tr><td>" + respuesta.data.data.ncta + "</td><td>" + respuesta.data.data.nomb + "</td>" + tdstring + "</tr>");
            }
        }).catch(function(error) {
            toastr.error('Error al obtener cuenta' + error, 'Mensaje del sistema')
        });
        // console.log($("#txtctarelacionhaber").val());
        txtctarelacionhaber = $("#txtctarelacionhaber").val();
        axios.get('/planescontables/getctabynro', {
            "params": {
                "cbuscar": txtctarelacionhaber
            }
        }).then(function(respuesta) {
            if (respuesta.data.estado == '1') {
                tdstring = "<td class='text-end'>0</td><td class='text-end'>" + txttotal + "</td>";
                cmnamecta = (respuesta.data.data.ncta.substring(0, 2));
                if (cmnamecta == '42') {
                    tdstring = "<td class='text-end'>" + txttotal + "</td><td class='text-end'>0</td>";
                }
                $("#tblcuentasrelacionadas").find('tbody').append("<tr><td>" + respuesta.data.data.ncta + "</td><td>" + respuesta.data.data.nomb + "</td>" + tdstring + "</tr>");
            }
        }).catch(function(error) {
            toastr.error('Error al obtener cuenta' + error, 'Mensaje del sistema')
        });
    }

    function grabaregreso() {
        Swal.fire({
            title: "¿Registrar Egreso a Libro Caja Efectivo?",
            text: "Se insertará en la base de datos.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, registrar'
        }).then(function(respuesta) {
            if (respuesta.isConfirmed) {
                data = new FormData();
                data.append("txtfechai", $("#txtfechai").val());
                data.append("cmbcuentas", $("#cmbcuentas").val());
                data.append("txtcuentas", $("#txtcuentas").val());
                data.append("cmbmoneda", $("#cmbmoneda").val());
                data.append("txtvalor", $("#txtvalor").val());
                data.append("txttotal", $("#txttotal").val());
                data.append("txttipocambio", $("#txttipocambio").val());
                data.append("txtreferencia", $("#txtreferencia").val());
                axios.post("/cajayefectivo/registraregresolibro", data)
                    .then(function(respuesta) {
                        Swal.fire({
                            title: "Egreso registrado satisfactoriamente ",
                            text: respuesta.data.message,
                            icon: "success"
                        });
                        limpiarcampos();
                    }).catch(function(error) {
                        console.log(error);
                        toastr.error(error.response.data, "Mensaje del Sistema");
                    });
            }
        });
    }

    function validar() {
        txttotal = $("#txttotal").val();
        txtvalor = $("#txtvalor").val();
        if (Number(txttotal) == 0 || txttotal.length < 0) {
            toastr.info("Digite el importe", 'Mensaje del sistema');
            return false;
        }
        if (Number(txtvalor) == 0 || txtvalor.length < 0) {
            toastr.info("Digite el valor", 'Mensaje del sistema');
            return false;
        }
        return true;
    }
</script>
<?php
$this->endSection("javascript");
?>