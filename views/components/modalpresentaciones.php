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
                    <div class="form-group col-md-4">
                        <label for="">Precio:</label>
                        <input type="text" onkeypress="return isNumber(event);" onclick="" class="form-control form-control-sm" id="txtpreciopres" value="">
                    </div>
                    <div class="form-group col-md-4 text-end"><br>
                        <button class="btn btn-success" onclick="registrardetallepresentacion()">Registrar</button>
                        <button class="btn btn-danger" onclick="limpiardetapres()">Limpiar</button>
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

    function registrardetallepresentacion() {
        txtcoston = $("#txtcoston").val();
        txtpreciopres = $("#txtpreciopres").val();
        if (Number(txtcoston) > Number(txtpreciopres)) {
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
        data.append("prec", txtpreciopres);
        data.append("idpres", pres[0]);
        data.append("cant", pres[1]);
        axios.post("/presentaciondetalle/registrar", data)
            .then(function(respuesta) {
                toastr.success(respuesta.data.message);
                limpiardetapres();
                listardetapresxproducto();
            }).catch(function(error) {
                toastr.error(error, 'Mensaje del sistema');
            });
    }

    function limpiardetapres() {
        $("#txtpreciopres").val(" ");
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