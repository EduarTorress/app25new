<!-- Modal Presentaciones -->
<div class="modal fade" id="modal_presentaciones" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="background-color:white;">
            <div class="modal-header" id="header_modal" style="background-color:#28a745;">
                <h7 class="modal-title" id="lblpresentaciones">Presentaciones</h7>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="">
                <div class="row">
                    <div class="form-group col-md-4">
                        <label for="">U. M. :</label>
                        <select class="selectpicker" data-live-search="true" id="cmbpresentacionesc">
                            <?php foreach ($cmbpresentaciones as $um) : ?>
                                <option value="<?php echo $um['pres_idpr'] . '-' . $um['pres_cant'] ?>" data-tokens="<?php echo $um['pres_desc'] ?>"><?php echo $um['pres_desc'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group col-md-2">
                        <label for="">Costo:</label>
                        <input type="text" onkeypress="return isNumber(event);" value="0" onclick="this.select();" class="form-control form-control-sm" id="txtcostopres" value="">
                    </div>
                    <div class="form-group col-md-2">
                        <label for="">Ganancia:</label>
                        <input type="text" onkeypress="return isNumber(event);" onkeyup="calcularprecioxganancia();" value="0" onclick="this.select();" class="form-control form-control-sm" id="txtgananciapres" value="">
                    </div>
                    <div class="form-group col-md-2">
                        <label for="">Precio:</label>
                        <input type="text" onkeypress="return isNumber(event);" onkeyup="calculargananciaxprecio()" value="0" onclick="this.select();" class="form-control form-control-sm" id="txtpreciopres" value="">
                    </div>
                    <div class="form-group col-md-2 text-center"><br>
                        <button class="btn btn-success btn-sm" onclick="registrardetallepresentacion()">Registrar</button>
                        <button class="btn btn-danger btn-sm" onclick="limpiardetapres()">Limpiar&nbsp;&nbsp; </button>
                    </div>
                </div>
                <?php $proyecto = (empty($_SESSION['config']['proyecto']) ? '' : $_SESSION['config']['proyecto']); ?>
                <div class="row" <?php echo ($proyecto != 'xsys5' ? 'style="display:none;"' : ' ') ?>>
                    <div class="form-group col-md-6">
                    </div>
                    <div class="form-group col-md-2">
                        <label for="">Ganan. Corp:</label>
                        <input type="text" onkeypress="return isNumber(event);" onkeyup="calcularpreciocorpxgananciacorp();" value="0" onclick="this.select();" class="form-control form-control-sm" id="txtgananciaprescorp" value="">
                    </div>
                    <div class="form-group col-md-2">
                        <label for="">Prec. Corp:</label>
                        <input type="text" onkeypress="return isNumber(event);" onkeyup="calculargananciacorpxpreciocorp()" value="0" onclick="this.select();" class="form-control form-control-sm" id="txtprecioprescorp" value="">
                    </div>
                </div>
            </div>
            <!-- <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div> -->
        </div>
    </div>
</div>
<div class="modal fade" id="modal-mantenimiento-presentacion" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" id="modal-mantenimiento-contenido-presentacion">
        </div>
    </div>
</div>
<script>
    $('#cmbpresentacionesc').selectpicker();

    function calcularprecioxganancia() {
        //console.log('calculando precio por ganancia')
        txtgananciapres = $("#txtgananciapres").val();
        txtcostopres = $("#txtcostopres").val();
        nuevoprecio = (txtcostopres * (1 + (txtgananciapres / 100))).toFixed(2);
        // console.log(nuevoprecio);
        $("#txtpreciopres").val(round(nuevoprecio, 0.1));
    }

    function calculargananciaxprecio() {
        //console.log('calculando ganancia por precio')
        nuevoprecio = $("#txtpreciopres").val();
        txtcostopres = $("#txtcostopres").val();
        if (Number(txtcostopres) == 0) {
            $("#txtgananciapres").val("0");
        } else {
            txtgananciapres = (((nuevoprecio - txtcostopres) / txtcostopres) * 100).toFixed(2);
            $("#txtgananciapres").val(round(txtgananciapres, 0.1));
        }
    }

    $('#txtcostopres').keypress(function(e) {
        if (e.keyCode == 13) {
            $("#txtgananciapres").focus();
            $("#txtgananciapres").click();
        }
    });

    $('#txtgananciapres').keypress(function(e) {
        if (e.keyCode == 13) {
            $("#txtpreciopres").focus();
            $("#txtpreciopres").click();
        }
    });

    $('#txtpreciopres').keypress(function(e) {
        if (e.keyCode == 13) {
            $("#txtgananciaprescorp").focus();
            $("#txtgananciaprescorp").click();
        }
    });

    $('#txtgananciaprescorp').keypress(function(e) {
        if (e.keyCode == 13) {
            $("#txtprecioprescorp").focus();
            $("#txtprecioprescorp").click();
        }
    });

    $('#txtprecioprescorp').keypress(function(e) {
        if (e.keyCode == 13) {
            registrardetallepresentacion();
            limpiardetapres();
        }
    });

    function calculargananciacorpxpreciocorp() {
        nuevoprecio = $("#txtprecioprescorp").val();
        txtcostopres = $("#txtcostopres").val();
        if (Number(txtcostopres) == 0) {
            $("#txtgananciaprescorp").val("0");
        } else {
            txtgananciapres = (((nuevoprecio - txtcostopres) / txtcostopres) * 100).toFixed(2);
            $("#txtgananciaprescorp").val(round(txtgananciapres, 0.1));
        }
    }

    function calcularpreciocorpxgananciacorp() {
        txtgananciapres = $("#txtgananciaprescorp").val();
        txtcostopres = $("#txtcostopres").val();
        nuevoprecio = (txtcostopres * (1 + (txtgananciapres / 100))).toFixed(2);
        $("#txtprecioprescorp").val(round(nuevoprecio, 0.1));
    }

    function registrardetallepresentacion() {
        txtcostopres = $("#txtcostopres").val();
        txtpreciopres = $("#txtpreciopres").val();
        if (Number(txtcostopres) > Number(txtpreciopres)) {
            toastr.error("El precio de venta no puede estar debajo del costo neto ", 'Mensaje del sistema');
            return;
        }
        if (txtpreciopres.length == 0) {
            toastr.error("Ingrese precio ", 'Mensaje del sistema');
            return;
        }
        txtidart = $("#txtidart").val();
        if (txtidart.length == 0) {
            toastr.error("Primero registre el producto ", 'Mensaje del sistema');
            return;
        }
        cmbpresentaciones = $("#cmbpresentacionesc").val();
        pres = cmbpresentaciones.split("-");
        data = new FormData();
        data.append("idart", txtidart);
        data.append("txtcostopres", txtcostopres);
        data.append("txtgananciapres", $("#txtgananciapres").val());
        data.append("prec", txtpreciopres);
        data.append("txtprecioprescorp", $("#txtprecioprescorp").val());
        data.append("txtgananciaprescorp", $("#txtgananciaprescorp").val());
        data.append("idpres", pres[0]);
        data.append("cant", pres[1]);
        axios.post("/presentaciondetalle/registrar", data)
            .then(function(respuesta) {
                toastr.success(respuesta.data.message, 'Mensaje del Sistema');
                limpiardetapres();
                listardetapresxproducto();
            }).catch(function(error) {
                toastr.error(error.response.data.message, 'Mensaje del sistema');
            });
    }

    function limpiardetapres() {
        $("#txtpreciopres").val("0");
        $("#txtprecioprescorp").val("0");
        $("#txtcostopres").val("0");
        $("#txtgananciapres").val("0");
        $("#txtgananciaprescorp").val("0");
    }

    //UNIDADES DE MEDIDA (PRESENTACIONES)

    $('.bs-searchbox input[type="search"]').keyup(function(event) {
        if (event.keyCode === 13) {
            axios.get('/admin/unidadesmedida/create')
                .then(function(respuesta) {
                    $('#modal-mantenimiento-contenido-presentacion').html(respuesta.data)
                    $('#modal-mantenimiento-presentacion').modal('show');
                    txtnombre = $('.bs-searchbox input[type="search"]').val();
                    $("#txtnombre").val(txtnombre);
                }).catch(function(error) {
                    toastr.error('Error al cargar el modal de crear ' + error, 'Mensaje del sistema')
                });
        }
    });

    $("#modal-mantenimiento-presentacion").on("shown.bs.modal", function() {
        $("#txtcantidadd").focus();
        $("#txtcantidadd").select();
    });
</script>