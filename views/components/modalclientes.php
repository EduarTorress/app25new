<div class="modal fade" id="modal_clientes" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="background-color:white;">
            <div class="modal-header" id="header_modal" style="background-color:#0c1c3f;">
                <h4 class="modal-title" id="">Clientes</h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="cliente">
                <label class="radio-inline">
                    <input type="radio" name="optradios" value="nombre" onchange="obtenertipobusquedacliente()" checked>&nbsp;Nombre&nbsp;
                </label>
                <label class="radio-inline">
                    <input type="radio" name="optradios" value="ruc" onchange="obtenertipobusquedacliente()">&nbsp;RUC&nbsp;
                </label>
                <label class="radio-inline">
                    <input type="radio" name="optradios" value="dni" onchange="obtenertipobusquedacliente()">&nbsp;DNI&nbsp;
                </label>
                <label class="radio-inline">
                    <input type="radio" name="optradios" value="codigo" onchange="obtenertipobusquedacliente()">&nbsp;Código&nbsp;
                </label>
                <button style="float: right; position: relative; top: -5px;" class="btn btn-primary"><a role="button" href="/cliente/index" style="color:white;">Nuevo</a></button>
                <div class="input-group mb-3">
                    <input type="text" class="form-control" id="txtbuscar" onkeypress="pulsarenterbuscarclientes(event)" name="buscar" onkeyup="mayusculas(this)" placeholder="Cliente a buscar">
                    <br>
                    <div class="input-group-append">
                        <button class="btn btn-outline-primary" id="cmdbuscar" onclick="consultarclientes()" type="button">Buscar</button>
                    </div>
                    <div class="col-12" id="search">
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
    var txtbuscar = document.getElementById("txtbuscar");
    txtbuscar.addEventListener("focus", function(event) {
        $("#cmdbuscar").attr('disabled', false);
    }, true);

    function seleccionarcliente(datos) {
        razon = datos.parametro2
        razon = razon.replace('"', '')
        document.getElementById('txtcliente').value = razon;
        document.getElementById("txtidcliente").value = datos.parametro1;
        document.getElementById("txtruccliente").value = datos.parametro3;
        document.getElementById("txtdireccion").value = datos.parametro5;
        document.getElementById("txtdnicliente").value = datos.parametro4;
        axios.get('/cliente/seleccionar', {
            "params": {
                'idclie': datos.parametro1,
                'nombre': razon,
                'ruc': datos.parametro3,
                'txtdireccion': datos.parametro5,
                'dni': datos.parametro4
            }
        }).then(function(respuesta) {
            $('#modal_clientes').modal('toggle');
        }).catch(function(error) {
            $('#modal_clientes').modal('toggle');
            toastr.error(error, 'Mensaje del sistema');
        });
    }
</script>