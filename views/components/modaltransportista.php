<!-- Modal Transportista -->
<div class="modal fade" id="modal_transportista" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="background-color:white;">
            <div class="modal-header" id="header_modal" style="background-color:#28a745;">
                <h7 class="modal-title" id="">Transportista</h7>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="transportista">
                <label class="radio-inline">
                    <input type="radio" name="optradiosTr" value="nombre" onchange="obtenertipobusquedatranspor()" checked>&nbsp;Nombre&nbsp;
                </label>
                <label class="radio-inline">
                    <input type="radio" name="optradiosTr" value="ruc" onchange="obtenertipobusquedatranspor()">&nbsp;Placa&nbsp;
                </label>
                <div class="input-group mb-3">
                    <input type="text" class="form-control" id="txtbuscarTr" name="txtbuscarTr" onkeypress="pulsarentertransportista(event)" onkeyup="mayusculas(this)" placeholder="Ingrese Transportista a Buscar" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                        <button class="btn btn-outline-success" id="cmdbuscartra" onclick="buscarTransportista()" type="button">Buscar</button>
                    </div>
                    <div class="col-12" id="searchTr">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<script>
    $('#modal_transportista').on('shown.bs.modal', function() {
        $('#txtbuscarTr').focus();
        $("#cmdbuscartra").attr('disabled', false);
    });

    var txtbuscar = document.getElementById("txtbuscarTr");
    txtbuscar.addEventListener("click", function(event) {
        $("#cmdbuscartra").attr('disabled', false);
    }, true);

    function obtenertipobusquedatranspor() {
        let vdvto = 0;
        if (document.getElementsByName("optradiosTr")[0].checked) {
            vdvto = 1;
            document.getElementById("txtbuscarTr").focus();
        }
        if (document.getElementsByName("optradiosTr")[1].checked) {
            vdvto = 0;
            document.getElementById("txtbuscarTr").focus();
        }
        return vdvto;
    }

    function buscarTransportista() {
        var abuscar = document.querySelector('#txtbuscarTr').value;
        var noption = obtenertipobusquedatranspor();
        axios.get('/transportista/lista', {
            "params": {
                "cbuscar": abuscar,
                "option": noption
            }
        }).then(function(respuesta) {
            const contenido_tabla = respuesta.data;
            $('#searchTr').html(contenido_tabla);
            $("#cmdbuscartra").attr('disabled', true);
        }).catch(function(error) {
            toastr.error('Error al cargar el listado','Mensaje del sistema')
        });
    }

    function seleccionarTransportista(datos) {
        document.getElementById("txtIdTransportista").value = datos.parametro1;
        document.getElementById("txttransportista").value = datos.parametro2;
        document.getElementById("txttruc").value = datos.parametro5;
        document.getElementById('txtplaca').value = datos.parametro4
        document.getElementById('txtmarca').value = datos.parametro9;
        document.getElementById('txtChoferVehiculo').value = datos.parametro3;
        document.getElementById('txtbrevete').value = datos.parametro7;
        document.getElementById('txtregmtc').value = datos.parametro6;
        document.getElementById('txttipot').value = datos.parametro8;
        document.getElementById('txtPlaca1').value = datos.parametro10;
        document.getElementById('txttipot').value = datos.parametro8;
        // idTransportista = id;
        axios.get('/transportista/seleccionar', {
            "params": {
                'datos': datos
            }
        }).then(function(respuesta) {
            $('#modal_transportista').modal('toggle');
        }).catch(function(error) {
            $('#modal_transportista').modal('toggle');
            toastr.error(error);
        });
    }
</script>