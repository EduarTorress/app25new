<table class="table table-sm small table table-hover" id="gridpedidos">
    <thead>
        <tr>
            <th scope="col" style="width:7%">Opciones</th>
            <th scope="col" style="width:5%" class="coda">Código</th>
            <th scope="col" style="width:25%">Producto</th>
            <th scope="col" style="width:5%">U.M.</th>
            <th scope="col" style="width:5%">Cantidad</th>
            <th scope="col" style="width:5%">Precio</th>
            <th scope="col" style="width:5%" class="preciosgv">Valor Unitario</th>
            <th scope="col" style="width:5%">Importe</th>
        </tr>
    </thead>
    <tbody id="carritocompras">
        <?php $i = 0; ?>
        <?php foreach ($carrito as $indice => $item) : ?>
            <?php if ($item['activo'] == 'A') { ?>
                <tr>
                    <?php
                    $parametro1 = $item['descri'];
                    $parametro2 = $item['coda'];
                    $parametro3 = $item['unidad'];
                    $parametro4 = $item['stock'];
                    $parametro5 = $item['precio1'];
                    $parametro6 = $item['precio2'];
                    $parametro7 = $item['precio3'];
                    $parametro8 = $item['costo'];
                    $parametro9 = $item['cantidad'];
                    $parametro10 = $item['precio'];
                    $parametro11 = $indice;
                    $parametros = compact('parametro1', 'parametro2', 'parametro3', 'parametro4', 'parametro5', 'parametro6', 'parametro7', 'parametro8', 'parametro9', 'parametro10', 'parametro11');
                    $cadena_json = json_encode($parametros);
                    ?>
                    <td>
                        <button class="btn btn-warning" onclick="quitaritem(<?php echo $indice ?>)"><a style="color:white" class="fas fa-trash-alt"></a></button>
                        <button class="btn btn-success" onclick='editaritem(<?php echo $cadena_json ?>);'><a style="color:white" class="fas fa-edit"></a></button>
                        <?php if ($_SESSION['config']['cambiarproductoxposicion'] == 'S') : ?>
                            <button class="btn btn-secondary" onclick='cambiaritem(<?php echo $cadena_json ?>);'><a style="color:white" class="fa fa-exchange"></a></button>
                        <?php endif; ?>
                    </td>
                    <td class="coda"><?php echo $item['coda'] ?></td>
                    <td><?php echo $item['descri'] ?></td>
                    <td><?php echo $item['textopresentacion'] ?></td>
                    <td class="text-center"><?php echo number_format($item['cantidad'], 2, '.', ',') ?></td>
                    <td class="precio text-center"><?php echo number_format($item['precio'], 2, '.', ',') ?></td>
                    <td class="preciosgv"></td>
                    <td class="text-center"><?php echo number_format(round($item['cantidad'] * $item['precio'], 2), 2, '.', ',') ?></td>
                    <?php $i++; ?>
                </tr>
            <?php } ?>
        <?php endforeach; ?>
    </tbody>
</table><br>
<div class="col-lg-12">
    <div class="card card-primary card-outline" style="width:auto;">
        <div class="input-group">
            <label for="" class="col-form-label form-control-sm ">Observaciones:</label>
            <div>
                <textarea class="form-control form-control-sm" placeholder="" id="txtdetalle" name="txtdetalle" style="width:200%; height:65%"></textarea>
            </div>
        </div>
        <div class="row">
            <div class="col-6"><br>
                <button class="btn btn-primary btn-sm" role="button"><a style="color:white;" href="<?php echo "/productos/index/1" ?>">Agregar</a></button>
                <button class="btn btn-danger btn-sm" id="cancelar" role="button" onclick="cancelarpedido()">Limpiar</button>
                <button class="btn btn-success btn-sm" role="button" onclick="grabarpedido()">Grabar </button>
                <?php if ($_SESSION['tipousuario'] == 'A') : ?>
                    <!-- <button class="btn btn-warning btn-sm" onclick="verutilidad();">Ver Utilidad</button> -->
                <?php endif; ?>
                <?php if (!empty($_SESSION['idpedido'])) : ?>
                    <?php if ($_SESSION['config']['guardarpedidocomonuevo'] == 'S') : ?>
                        <button class="btn btn-success btn-sm" role="button" onclick="guardarpedido('Registrar Pedido como nuevo')">Grabar Nuevo </button>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
            <div class="col-2 align-items-end"><br>
                <div class="input-group mb-3" style="width: 85%; display:none" id="divutilidad">
                    <div class="input-group-prepend">
                        <span class="input-group-text text-sm" id=""><strong>Costo:</strong></span>
                    </div>
                    <input type="text" class="form-control text-right text-sm" id="txtutilidad" aria-label="Small" aria-describedby="inputGroup-sizing-sm" disabled>
                </div>
            </div>
            <div class="col-2 align-items-end"><br>
                <div class="input-group mb-3" style="width: 85%;">
                    <div class="input-group-prepend">
                        <span class="input-group-text text-sm" id=""><strong>Items:</strong></span>
                    </div>
                    <input type="text" class="form-control text-right text-sm" id="totalitems" aria-label="Small" value=<?php echo  $items ?> aria-describedby="inputGroup-sizing-sm" disabled>
                    <input type="text" name="nropedido" id="nropedido" hidden value=" <?php echo isset($nropedido) ? $nropedido : '' ?> ">
                </div>
            </div>
            <div class="col-2 align-items-start"><br>
                <div class="input-group mb-3" style="width: 90%;">
                    <div class="input-group-prepend">
                        <span class="input-group-text text-sm" id=""><strong>TOTAL</strong></span>
                    </div>
                    <input type="text" class="form-control text-right text-sm" id="total" aria-label="Small" value=<?php echo  $total ?> aria-describedby="inputGroup-sizing-sm" disabled>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var table = $('#gridpedidos').DataTable({
        "paging": true,
        "keys": true,
        "lengthChange": false,
        "searching": true,
        "ordering": false,
        "info": false,
        "autoWidth": false,
        "responsive": true,
        "columnDefs": [{
            targets: 3,
            orderable: false,
            searchable: false
        }]
    });
    $(".coda").css("display", "none");
    <?php if ($_SESSION['config']['multiigv'] == 'N') : ?>
        $(".preciosgv").css("display", "none");
    <?php endif; ?>
</script>