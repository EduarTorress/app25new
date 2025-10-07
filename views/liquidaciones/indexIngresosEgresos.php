<?php
$this->setLayout('layouts/admin');
?>
<?php
$this->startSection('contenido');
?>
<div class="content-wrapper">
    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <form class="form-inline">
                        <?php
                        $lu = new \App\View\Components\ListasusuarioscomboComponent(session()->get("usuario_id"));
                        echo $lu->render();
                        ?>
                    </form>
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#divoi" role="tab" aria-selected="true">Otros Ingresos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#dive" role="tab" aria-selected="false">Egresos</a>
                        </li>
                        <!-- <li class="nav-item">
                            <a class="nav-link" id="contact-tab" data-toggle="tab" href="#divt" role="tab" aria-selected="false">Transferencia</a>
                        </li> -->
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="divoi" role="tabpanel"><br>
                            <?php
                            $tipo = 'I';
                            $ie = new \App\View\Components\IngresosEgresosComponent($tipo);
                            echo $ie->render();
                            ?>
                        </div>
                        <div class="tab-pane fade" id="dive" role="tabpanel"><br>
                            <?php
                            $tipo = 'E';
                            $ie = new \App\View\Components\IngresosEgresosComponent($tipo);
                            echo $ie->render();
                            ?>
                        </div>
                        <!-- <div class="tab-pane fade" id="divt" role="tabpanel"><br>
                            <div class="mb-3 row">
                                <label for="" class="col-sm-2 col-form-label">Fecha</label>
                                <div class="col-sm-3">
                                    <input type="date" class="form-control" id="txtfechaf" value="<?php echo date('Y-m-d') ?>" readonly>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="" class="col-sm-2 col-form-label">Saldo</label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" id="txtsaldo" readonly placeholder="0.00">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="" class="col-sm-2 col-form-label">Monto a Transferir</label>
                                <div class="col-sm-3">
                                    <input type="number" class="form-control" id="txtmontoatransferir">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="" class="col-sm-2 col-form-label">Detalle</label>
                                <div class="col">
                                    <input type="text" class="form-control" id="txtdetallef">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <div class="col-sm">
                                    <button type="button" onclick="limpiar();" class="btn btn-danger float-right"><i class="fas fa-refresh"></i> Limpiar</button>
                                    <button type="button" onclick="registrarTransferencia();" class="btn btn-success float-right"><i class="fas fa-plus-circle"></i> Registrar</button>
                                </div>
                            </div>
                        </div> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$cl = new \App\View\Components\ModalConfirmarLoginComponent();
echo $cl->render();
?>
<?php
$this->endSection('contenido');
?>
<?php
$this->startSection('javascript');
?>
<script>
    window.onload = function() {
        titulo("<?php echo $titulo ?>");
        $("#cmbusuarios").attr('disabled', true);
    }

    function registrar(tipo) {
        if (tipo == 'I') {
            dtipo = 'Ingreso';
            registrarIngreso();
        } else {
            dtipo = 'Egreso'
            registrarEgreso();
        }
    }

    function registrarIngreso() {
        validacion = validarIngreso();
        if (validacion == true) {
            Swal.fire({
                title: "¿Grabar Ingreso?",
                text: "Se registrará en la base de datos.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, proceder',
                cancelButtonText: 'No, cancelar'
            }).then(function(respuesta) {
                if (respuesta.isConfirmed) {
                    data = new FormData();
                    data.append("dfecha", $("#txtfechai").val());
                    data.append("cndoc", $("#txtnumerodocumentoi").val());
                    data.append("cdeta", $("#txtdetallei").val());
                    data.append("sdeudor", $("#txtimportei").val());
                    data.append("sacreedor", 0);
                    data.append("tipo", 'I');
                    axios.post("/cajas/registrarIngresoEgreso", data)
                        .then(function(respuesta) {
                            console.log(respuesta);
                            Swal.fire({
                                icon: "success",
                                title: respuesta.data.message,
                                text: "El " + dtipo + " con el número " + $("#txtnumerodocumentoi").val() + ".",
                                showConfirmButton: false,
                                timer: 4750
                            });
                            limpiar();
                        }).catch(function(error) {
                            mostrarerroresvalidacion(error);
                        });
                }
            });
        } else {
            toastr.error("Complete los datos correctamente", "Error del sistema");
        }
    }

    function registrarEgreso() {
        validacion = validarEgreso();
        if (validacion == true) {
            Swal.fire({
                title: "¿Grabar Egreso?",
                text: "Se registrará en la base de datos.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, proceder',
                cancelButtonText: 'No, cancelar'
            }).then(function(respuesta) {
                if (respuesta.isConfirmed) {
                    data = new FormData();
                    data.append("dfecha", $("#txtfechae").val());
                    data.append("cndoc", $("#txtnumerodocumentoe").val());
                    data.append("cdeta", $("#txtdetallee").val());
                    data.append("sdeudor", 0);
                    data.append("sacreedor", $("#txtimportee").val());
                    data.append("tipo", 'E');
                    axios.post("/cajas/registrarIngresoEgreso", data)
                        .then(function(respuesta) {
                            Swal.fire({
                                icon: "success",
                                title: respuesta.data.message,
                                text: "El " + dtipo + " con el número " + $("#txtnumerodocumentoe").val() + ".",
                                showConfirmButton: false,
                                timer: 4750
                            });
                            limpiar();
                        }).catch(function(error) {
                            mostrarerroresvalidacion(error);
                        });
                }
            });
        } else {
            toastr.error("Complete los datos correctamente", "Mensaje del Sistema");
        }
    }

    function validarIngreso() {
        cndoc = $("#txtnumerodocumentoi").val();
        if (cndoc == '') {
            return false;
        }
        cdeta = $("#txtdetallei").val();
        if (cdeta == '') {
            return false;
        }
        importe = $("#txtimportei").val();
        if (importe == '' || importe == 0 || importe == '0') {
            return false;
        }
        return true;
    }

    function validarEgreso() {
        cndoc = $("#txtnumerodocumentoe").val();
        if (cndoc == '') {
            return false;
        }
        cdeta = $("#txtdetallee").val();
        if (cdeta == '') {
            return false;
        }
        importe = $("#txtimportee").val();
        if (importe == '' || importe == 0 || importe == '0') {
            return false;
        }
        return true;
    }

    function validarTransferencia() {
        cndoc = $("#txtmontoatransferir").val();
        if (cndoc == '') {
            return false;
        }
        cdeta = $("#txtdetallef").val();
        if (cdeta == '') {
            return false;
        }
        return true;
    }

    function registrarTransferencia() {
        validacion = validarTransferencia();
        if (validacion == true) {
            Swal.fire({
                title: "¿Grabar Transferencia?",
                text: "Se registrará en la base de datos.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, proceder',
                cancelButtonText: 'No, cancelar'
            }).then(function(respuesta) {
                if (respuesta.isConfirmed) {
                    data = new FormData();
                    data.append("dfecha", $("#txtfechaf").val());
                    data.append("fsaldo", $("#txtsaldo").val());
                    data.append("fmontoatransferir", $("#txtmontoatransferir").val());
                    data.append("cdeta", $("#txtdetallef").val());
                    axios.post("/cajas/registrarTransferencia", data)
                        .then(function(respuesta) {
                            Swal.fire({
                                icon: "success",
                                title: respuesta.data.message,
                                text: "La transferencia se realizó correctamente",
                                showConfirmButton: false,
                                timer: 4750
                            });
                            limpiar();
                        }).catch(function(error) {
                            mostrarerroresvalidacion(error);
                        });
                }
            });
        } else {
            toastr.error("Complete los datos correctamente", "Mensaje del sistema");
        }
    }

    function limpiar() {
        var elements = document.getElementsByTagName("input");
        for (var ii = 0; ii < elements.length; ii++) {
            if (elements[ii].type == "text") {
                elements[ii].value = "";
            }
        }
        $("#txtimportei").val('');
        $("#txtimportee").val('');
        $("#txtmontoatransferir").val('');
    }

    function abrirmodallogin() {
        $("#modalConfirmarLogin").modal("show");
    }

    function cerrarModal() {
        $("#modalConfirmarLogin").modal("hide");
    }

    function consultarlogin() {
        data = new FormData();
        data.append("txtUsuario", document.getElementById("txtUsuario").value);
        data.append("txtPassword", document.getElementById("txtPassword").value);
        axios.post("/cajas/cambiarfecha", data)
            .then(function(respuesta) {
                //toastr.success("Eliminado correctamente");
                rpta = respuesta.data.estado;
                if (rpta == '1') {
                    $(".fecha").attr("readonly", false);
                }
                $("#modalConfirmarLogin").modal("hide");
            }).catch(function(error) {
                if (error.hasOwnProperty("response")) {
                    if (error.response.status == 422) {
                        toastr.error(error.response.data.message, 'Mensaje del sistema');
                    }
                }
            });
    }


    $(".fecha").on("dblclick", function() {
        abrirmodallogin();
    });
</script>
<?php
$this->endSection('javascript');
?>