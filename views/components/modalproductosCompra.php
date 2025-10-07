<div class="modal fade" id="modal_productos" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="background-color:white;">
            <div class="modal-header" id="header_modal" style="background-color:#28a745;">
                <h4 class="modal-title" id="">Buscar Producto</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="producto">
                <label class="radio-inline">
                    <input type="radio" name="optradiosP" value="nombre" onchange="obtenertipobusquedaProducto()" checked>&nbsp;Nombre&nbsp;
                </label>
                <label class="radio-inline">
                    <input type="radio" name="optradiosP" value="codigo" onchange="obtenertipobusquedaProducto()">&nbsp;Código&nbsp;
                </label>
                <label class="radio-inline">
                    <input type="radio" name="optradiosP" value="codigo1" onchange="obtenertipobusquedaProducto()">&nbsp;Código Fab.&nbsp;
                </label>
                <div class="input-group mb-3">
                    <input type="text" class="form-control" id="txtbuscarProducto" name="buscar"  onkeyup="mayusculas(this)" placeholder="Ingrese Parametro de Producto a Buscar" value="<?php echo session()->get('busquedaPV') ?>">
                    <div class="input-group-append">
                        <button class="btn btn-outline-success" id="cmdbuscarP" onclick="buscarProducto()" type="button">Buscar</button>
                    </div>
                </div>
                <div id="searchP">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<script>
    function obtenertipobusquedaProducto() {
        let vdvto = 0;
        if (document.getElementsByName('optradiosP')[0].checked) {
            vdvto = 'nombre';
            moverCursorFinalTexto("txtbuscarProducto");
            // document.getElementById("txtbuscarProducto").focus();
        }
        if (document.getElementsByName('optradiosP')[1].checked) {
            vdvto = 'codigo';
            moverCursorFinalTexto("txtbuscarProducto");
        }
        if (document.getElementsByName('optradiosP')[2].checked) {
            vdvto = 'codigofab';
            moverCursorFinalTexto("txtbuscarProducto");
        }
        return vdvto;
    }

    function buscarProducto() {
        var abuscar = document.getElementById("txtbuscarProducto").value;
        //console.log(abuscar.length)
        if (abuscar.length == 0) {
            toastr.info("Ingrese Nombre de Producto a Buscar")
            return;
        }
        var noption = obtenertipobusquedaProducto();
        // $('#loading').modal('show');
        axios.get('/productos/listaModalCompra', {
            "params": {
                "cbuscar": abuscar,
                "option": noption
            }
        }).then(function(respuesta) {
            // $('#loading').modal('hide');
            const contenido_tabla = respuesta.data;
            $('#searchP').html(contenido_tabla);
            console.log('hola')
        }).catch(function(error) {
            // $('#loading').modal('hide');
            toastr.error('Error al cargar el listado')
        });
    }    

    function cargarprecios(domElement, array) {
        var select = document.getElementsByName(domElement)[0];
        const $select = document.querySelector("#cmbprecios");
        for (let i = $select.options.length; i >= 0; i--) {
            $select.remove(i);
        }
        for (value in array) {
            var option = document.createElement("option");
            option.text = array[value];
            select.add(option);
        }
    }
   
</script>