<!-- Modal Compras -->
<div class="modal fade" id="modal_compras" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="background-color:white;">
            <div class="modal-header" id="header_modal" style="background-color:#0c1c3f;">
                <h4 class="modal-title" id="">Canje de Compras</h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="">
                <div class="input-group mb-3">
                    <div class="col-12" id="searchcompra">
                        <table id="tablacompras" class="table table-bordered table-hover table-sm small">
                            <thead>
                                <tr>
                                    <th>Nro. Compra</th>
                                    <th>Fecha</th>
                                    <th>Proveedor</th>
                                    <th>Importe</th>
                                    <th class="text-center">Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($listado as $item) : ?>
                                    <tr>
                                        <td><?php echo $item['dcto'] ?></td>
                                        <td><?php echo $item['fech'] ?></td>
                                        <td><?php echo $item['razo'] ?></td>
                                        <td><?php echo $item['impo'] ?></td>
                                        <td class="text-center">
                                            <?php
                                            $parametro1 = $item['idauto'];
                                            $parametro2 = $item['fech'];
                                            $parametro3 = $item['idprov'];
                                            $parametro4 = $item['dire'];
                                            $parametro5 = $item['nruc'];
                                            $parametro6 = $item['ubig'];
                                            $parametro7 = $item['razo'];
                                            $parametros = compact('parametro1', 'parametro2', 'parametro3', 'parametro4', 'parametro5', 'parametro6', 'parametro7');
                                            $cadena_json = json_encode($parametros);
                                            ?>
                                            <a class="btn btn-success" role="button" onclick='seleccionarcompra(<?php echo $cadena_json ?>)'>
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function seleccionarcompra(datos) {
        $("#txtidproveedor").val(datos.parametro3);
        $("#txtUbigeoproveedor").val(datos.parametro6);
        $("#txtproveedor").val(datos.parametro7)
        $("#txtptopartida").val(datos.parametro4);
        $("#txtrucproveedor").val(datos.parametro5)
        $("#idautot").val(datos.parametro1);
        axios.get('/traspasos/listardetallecompratocanje', {
            "params": {
                "idauto": datos.parametro1
            }
        }).then(function(respuesta) {
            const contenido_tabla = respuesta.data;
            $('#detalle').html(contenido_tabla);
            calcularPesoTotal();
        }).catch(function(error) {
            toastr.error('Error al cargar el listado ' + error, 'Mensaje del sistema')
        });
        $("#modal_compras").modal('hide');
    }

    $('#tablacompras').DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": true,
        "info": false,
        "autoWidth": true,
        "responsive": true,
        "columnDefs": [{
            targets: 4,
            orderable: false,
            searchable: false
        }]
    });
</script>