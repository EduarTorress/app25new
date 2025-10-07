<div class="modal fade" id="mdStockProducto" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="lblTitle"></h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="tblGestionStock">
                        <thead>
                            <tr>
                                <th scope="col" style="display:none">#</th>
                                <th scope="col">Produc.</th>
                                <th scope="col">Stock</th>
                                <th scope="col">Físico </th>
                                <th scope="col">Eliminar</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-warning" onclick="clearTbGStock()">Limpiar</button>
                <button type="button" onclick="saveDataArtStock()" class="btn btn-primary">Guardar cambios</button>
            </div>
        </div>
    </div>
</div>
<script>
    function getDataArtStock(datos) {
        // console.log(datos);
        almacen = "", column = "";
        stock = 0;
        idalmacen = <?php echo (!empty(trim($_SESSION['idalmacen'])) ? trim($_SESSION['idalmacen']) : 0); ?>;
        if (idalmacen == 0) {
            window.location.href = '/login';
        }
        switch (idalmacen) {
            case 1:
                almacen = 'SUCURSAL 01';
                column = 'uno';
                stock = datos.parametro9
                break;
            case 2:
                almacen = 'SUCURSAL 02';
                column = 'dos';
                stock = datos.parametro10
                break;
            case 3:
                almacen = 'SUCURSAL 03';
                column = 'tre';
                stock = datos.parametro11
                break;
        }

        datos = {
            'idart': datos.parametro2,
            'producto': datos.parametro1,
            'idAlmac': idalmacen,
            'almacen': almacen,
            'stock': stock,
            'column': column
        }

        valor = 0
        $('#tblGestionStock > tbody  > tr > td > input.idart').each(function() {
            id = $(this).val();
            if (id == datos.idart) {
                toastr.error("El producto ya fue añadido", 'Mensaje del sistema')
                valor = 1
            }
        });
        if (valor == 0) {
            showDataArtStock(datos);
        }
    }

    function showDataArtStock(datos) {
        $("#lblTitle").text("Gestionar stock: " + datos.almacen);
        // console.log(datos);
        var tr = `<tr class="fila"> 
                    <td style="display:none"><input type="text" name="idart1" style="width: 100%;" class="idart" id="idart1"  value='` + datos.idart + `' readonly></td>
                    <td><input type="text" name="nombre1" style="width: 100%;" class="nombre" id="nombre1"  value='` + datos.producto.trim() + `' readonly></td>
                    <td style="width:10px;"><input type="text" name="stock1" style="width: 100%;" class="stock" id="stock1"  value='` + datos.stock + `' readonly></td>
                    <td style="width:10px;"><input type="number" name="ingreso1" style="width: 100%;" class="ingreso" id="ingreso1" value="1"> </td>
                    <td> <button class="borrar" style="height:25px; background-color:#FF3838; border-color: #FF3838;">Eliminar</button></td>
                    </tr>`;
        $('#tblGestionStock tbody').append(tr);
        // $("#txtProducto").val(datos.producto);
        // $("#txtIdProducto").val(datos.idart);
        // $("#txtStockActual").val(datos.stock);
        $("#mdStockProducto").modal('show');
    }

    function saveDataArtStock() {
        var table = document.getElementById("tblGestionStock");
        var filas = table.tBodies[0].rows.length;
        if (filas == 0) {
            toastr.error("No hay productos para guardar",'Mensaje del Sistema')
        } else {
            Swal.fire({
                title: '¿Desea grabar el AJUSTE de INVENTARIO?',
                text: "Se registrará el ingreso de los productos seleccionados ",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, registrar'
            }).then(function(respuesta) {
                if (respuesta.isConfirmed) {
                    const detalle = []
                    $("#tblGestionStock tbody tr").each(function() {
                        json = "";
                        $(this).find("td input").each(function() {
                            $this = $(this);
                            p = $this.val();
                            p = p.replace('"', "'");
                            json += ',"' + $this.attr("class") + '":"' + p.trim() + '"'
                        })
                        obj = JSON.parse('{' + json.substr(1) + '}');
                        detalle.push(obj)
                    });

                    data = new FormData();
                    data.append("detalle", JSON.stringify(detalle));
                    axios.post("/producto/updateStock", data)
                        .then(function(respuesta) {
                            console.log(respuesta)
                            toastr.success(respuesta.data.mensaje.trimEnd());
                            clearTbGStock();
                            $("#mdStockProducto").modal('hide');
                        }).catch(function(error) {
                            console.log(error)
                        });
                }
            });
        }
    }

    function clearTbGStock() {
        $('#tblGestionStock tbody tr').remove();
    }

    $(document).on('click', '.borrar', function(event) {
        event.preventDefault();
        $(this).closest('tr').remove();
    });
</script>