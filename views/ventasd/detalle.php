<div class="table-responsive">
    <table class="table table-sm small table table-hover" id="griddetalle">
        <thead>
            <tr>
                <th scope="col" style="width:2%">Opciones</th>
                <th scope="col" style="width:3%" class="codigo">Código</th>
                <th scope="col" style="width:28%">Producto</th>
                <th scope="col" style="width:5%">U.M.</th>
                <th scope="col" style="width:5%" class="text-center">Cantidad</th>
                <th scope="col" style="width:5%" class="text-center">Precio</th>
                <?php if (!empty($_SESSION['config']['tipobotica'])) : ?>
                    <th scope="col" class="text-center" style="width:5%">Lote</th>
                    <th scope="col" class="text-center" style="width:5%">Fecha Vto.</th>
                <?php endif; ?>
                <th scope="col" style="width:5%" class="text-center valorunitario">Valor Unit.</th>
                <th scope="col" style="width:5%">Importe</th>
            </tr>
        </thead>
        <tbody id="carritoventas">
            <?php
            use Core\Foundation\Application;
            $i = 0; ?>
            <?php foreach ($carritov as $indice => $item) : ?>
                <?php if ($item['activo'] == 'A') { ?>
                    <tr onkeyup="verificarValores(this); actualizarProducto(this,<?php echo $indice ?>);" onblur="actualizarProducto(this,<?php echo $indice ?>);">
                        <?php
                        $parametro1 = $item['descripcion'];
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
                        $parametros = compact('parametro1', 'parametro2', 'parametro3', 'parametro4', 'parametro5', 'parametro6', 'parametro8', 'parametro9', 'parametro10', 'parametro11');
                        $cadena_json = json_encode($parametros);
                        ?>
                        <td>
                            <button class="btn btn-warning" onclick="quitaritem(<?php echo $indice ?>,<?php echo $item['coda']; ?>)"><a style="color:white" class="fas fa-trash-alt"></a></button>
                        </td>
                        <td class="codigo"><?php echo $item['coda'] ?></td>
                        <?php $app = Application::getInstance(); ?>
                        <td <?php echo (($app->empresa == 'bustamante') ? 'contenteditable="true"' : ''); ?>><?php echo $item['descripcion'] ?></td>
                        <td onkeyup="abrirmodalregistro(event)">
                            <?php
                            $presentaciones = json_decode($item['presentaciones'], true); ?>
                            <select onchange="cambiarpresentacion(this,<?php echo $indice ?>)" class="form-control form-control-sm" name="cmbpresentaciones" id="cmbpresentaciones" onkeypress="entertest(this)">
                                <?php foreach ($presentaciones as $p) : ?>
                                    <option value="<?php echo $p['epta_idep'] . '-' . $p['epta_prec'] ?>" <?php echo (($p['epta_idep'] == $item['presseleccionada']) ? 'selected' : '') ?>>
                                        <?php echo trim($p['pres_desc']) . '-' . $p['epta_cant']; ?>
                                    </option>
                                <?php endforeach;
                                ?>
                            </select>
                        </td>
                        <td class="text-center cantidad" onclick="funcionEnterCant(this,<?php echo $indice ?>)" contenteditable="false" name="cantidad"><input type="text" class="inputright" onkeyup="abrirmodalregistro(event)" onkeypress="return isNumber(event);" value="<?php echo number_format($item['cantidad'], 2, '.', '') ?>"></td>
                        <td class="precio text-center" id="precio" contenteditable="false" name="precio"><input readonly onkeypress="return isNumber(event);" type="text" class="inputright" onkeyup="abrirmodalregistro(event)" value="<?php echo number_format($item['precio'], 2, '.', '') ?>"></td>
                        <?php if (!empty($_SESSION['config']['tipobotica'])) : ?>
                            <td class="text-center" class="lote"><input readonly onclick="this.select(); clicksubtotal=0;" ondblclick="listarlotes(<?php echo $item['coda']; ?>);" type="text" id="<?php echo 'txtlote' . $item['coda'] ?>" class="" value="<?php echo (empty($item['lote']) ? ' ' : $item['lote']); ?>"></td>
                            <td class="text-center" class="fechavto"><input readonly class="fechavtoproducto" onclick="this.select(); clicksubtotal=0;" type="date" id="<?php echo 'txtfechavto' . $item['coda'] ?>" value="<?php echo (empty($item['fechavto']) ? ' ' : $item['fechavto']); ?>"></td>
                            <style>
                                .fechavtoproducto::-webkit-inner-spin-button,
                                .fechavtoproducto::-webkit-calendar-picker-indicator {
                                    display: none;
                                    -webkit-appearance: none;
                                }
                            </style>
                        <?php endif; ?>
                        <td class="preciosgv text-center valorunitario"></td>
                        <td class="text-center" class="total"><?php echo number_format(round($item['cantidad'] * $item['precio'], 2), 2, '.', '') ?></td>
                        <?php $i++; ?>
                    </tr>
                <?php } ?>
            <?php endforeach; ?>
        </tbody>
    </table>
</div><br>
<div class="col-lg-12">
    <div class="card card-success card-outline" style="width:auto;">
        <div class="row">
            <div class="col-6 align-items-start">
                <br>
                <div class="input-group">
                    <!-- "/productos/index/3" para index de productos de ventas  -->
                    <button class="btn btn-primary btn-sm" role="button" data-bs-toggle="modal" data-bs-target="#modal_productos">Agregar</button>
                    <button class="btn btn-danger btn-sm" id="cancelar" role="button" onclick="cancelarVenta()">Limpiar</button>
                    <button class="btn btn-success btn-sm" id="grabar" role="button" onclick="preregistro();"><?php echo (isset($btn) ? $btn : 'Grabar') ?></button>
                    <!-- <?php if ($_SESSION['tipousuario'] == 'A') : ?> -->
                        <!-- <button class="btn btn-warning btn-sm" onclick="verutilidad();">Ver Utilidad</button> -->
                    <!-- <?php endif; ?> -->
                </div>
            </div>
            <div class="col-2 align-items-start">
                <div class="input-group mb-3" style="width: 85%; display:none" id="divutilidad">
                    <div class="input-group-prepend">
                        <span class="input-group-text text-sm" id=""><strong>Ganancia:</strong></span>
                    </div>
                    <input type="text" class="form-control text-right text-sm" id="txtutilidad" aria-label="Small" aria-describedby="inputGroup-sizing-sm" disabled>
                </div>
            </div>
            <div class="col-2 align-items-start">
                <div class="input-group mb-3" style="width: 85%;">
                    <div class="input-group-prepend">
                        <span class="input-group-text text-sm" id=""><strong>Items:</strong></span>
                    </div>
                    <input type="text" class="form-control text-right text-sm" id="totalitems" aria-label="Small" value="<?php echo  $items ?>" aria-describedby="inputGroup-sizing-sm" disabled>
                </div>
            </div>
            <div class="col-2 align-items-start">
                <div class="input-group" style="width: 90%;">
                    <div class="input-group-prepend">
                        <span class="input-group-text text-sm"><strong id="lblsubtotal">SubTotal</strong></span>
                    </div>
                    <input type="text" class="form-control text-right text-sm" id="subtotal" aria-label="Small" value="" aria-describedby="inputGroup-sizing-sm" disabled>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-10"></div>
            <div class="col-2">
                <div class="input-group " style="width: 90%;">
                    <div class="input-group-prepend">
                        <span class="input-group-text text-sm" id=""><strong>IGV&emsp;&emsp;&nbsp;&nbsp;</strong></span>
                    </div>
                    <input type="text" class="form-control text-right text-sm" id="igv" aria-label="Small" value="" aria-describedby="inputGroup-sizing-sm" disabled>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-10"></div>
            <div class="col-2 align-items-start">
                <div class="input-group mb-3" style="width: 90%;">
                    <div class="input-group-prepend">
                        <span class="input-group-text text-sm" id=""><strong>TOTAL&emsp;</strong></span>
                    </div>
                    <input type="text" style="font-size:25px;" class="form-control text-right" id="total" aria-label="Small" value="<?php echo $total ?>" disabled>
                    <input type="text" style="display:none" class="form-control text-right text-sm" id="numeroDocumento" aria-label="Small" value="<?php echo isset($numeroDocumento) ?  $numeroDocumento : '' ?>" disabled>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="div-lotes-fvto"></div>
<script>
    // $('#griddetalle').DataTable({
    //     "paging": true,
    //     "keys": true,
    //     "lengthChange": false,
    //     "searching": true,
    //     "ordering": false,
    //     "info": false,
    //     "autoWidth": false,
    //     "responsive": true,
    //     "columnDefs": [{
    //             "responsivePriority": 1,
    //             "target": 0
    //         },
    //         {
    //             "responsivePriority": 2,
    //             "target": 2
    //         },
    //         {
    //             "responsivePriority": 3,
    //             "target": -3
    //         },
    //         {
    //             "responsivePriority": 4,
    //             "target": -2
    //         }
    //     ]
    // });

    $(document).ready(function() {
        $(".codigo").css("display", "none");
        <?php if ($_SESSION['config']['multiigv'] != 'S') : ?>
            $(".valorunitario").css("display", "none");
        <?php endif; ?>
        // console.log($("#txtreferencia").val())
        ventasexon = "<?php echo empty($_SESSION['config']['ventasexon']) ? 'N' : 'S'; ?>";
        if (ventasexon == 'S') {
            $("#lblsubtotal").text("EXON.")
        }
    });

     function listarlotes(idart) {
        const ruta = '/productos/listarlotesyfechasvto/' + idart;
        axios.post(ruta)
            .then(function(respuesta) {
                const contenido_tabla = respuesta.data;
                $('#div-lotes-fvto').html(contenido_tabla);
                $("#modallotesfvto").modal('show');
                $("#txtidartselect").val(idart);
            }).catch(function(error) {
                if (error.hasOwnProperty('response')) {
                    toastr.error(error.response.data.message, 'Mensaje del sistema');
                }
            })
    }

    function seleccionaropcion(datos) {
        txtidart = $("#txtidartselect").val();
        $("#txtlote" + txtidart).val(datos.parametro2);
        $("#txtfechavto" + txtidart).val(datos.parametro3);
        $("#modallotesfvto").modal('hide');
        const data = new FormData();
        data.append("txtidart", txtidart);
        data.append("txtlote", datos.parametro2);
        data.append("txtfechavto", datos.parametro3);
        axios.post('/ventasrapidas/EditarLoteFechavto', data)
            .then(function(respuesta) {}).catch(function(error) {
                if (error.hasOwnProperty("response")) {
                    console.log(error);
                }
            });
    }

    $("#griddetalle tr:last td:eq(5) .inputright").on("keypress", function(evt) {
        if (evt.key === "Enter") {
            $("#modal_productos").modal('show');
            // $("#txtbuscarProducto").blur();
            // $("#cmdbuscarP").attr("disabled", "disabled");
        }
    });

    function abrirmodalregistro(e) {
        if (e.keyCode == 27) {
            grabarVenta();
        }
    }

    function actualizarProducto(o, i) {
        $(o).each(function() {
            var _tr = $(o);
            cmbpresentacion = _tr.find("td").eq(3).find("select").val();
            cmbpresentacion = cmbpresentacion.split("-");
            textpresentacion = _tr.find("td").eq(3).find("select option:selected").text();
            textpresentacion = textpresentacion.split("-");
            const data = new FormData();
            var id = _tr.find("td").eq(1).html();
            data.append("txtdescri", _tr.find("td").eq(2).html());
            data.append("txtprecio", _tr.find("td").eq(5).find("input").val());
            data.append("unidad", textpresentacion[0].trim());
            data.append("txtcantidad", _tr.find("td").eq(4).find("input").val());
            data.append("cantequi", textpresentacion[1]);
            data.append("presseleccionada", cmbpresentacion[0])
            data.append("cmbmoneda", $("#cmbmoneda").val());
            // console.log(_tr.find("td").eq(4).html())
            tipobotica = "<?php echo empty($_SESSION['config']['tipobotica']) ? 'N'  : 'S'; ?>";
            if (tipobotica == 'S') {
                data.append("lote", _tr.find("td").eq(6).find("input").val());
                data.append("fechavto", _tr.find("td").eq(7).find("input").val());
            }
            data.append("indice", i);
            axios.post('/vtas/EditarUno', data)
                .then(function(respuesta) {
                    calcularIGV();
                    //console.log('correctamente editado')
                }).catch(function(error) {
                    if (error.hasOwnProperty("response")) {
                        console.log(error);
                    }
                });
        });
    }

    function cambiarpresentacion(o, i) {
        row = $(o).parent().parent();
        // console.log(row);
        $(row).each(function() {
            var _tr = $(row);
            cmbpresentacion = _tr.find("td").eq(3).find("select").val();
            cmbpresentacion = cmbpresentacion.split("-");
            textpresentacion = _tr.find("td").eq(3).find("select option:selected").text();
            textpresentacion = textpresentacion.split("-");
            _tr.find("td").eq(5).find("input").val(Number(cmbpresentacion[1]).toFixed(2));
            const data = new FormData();
            var id = _tr.find("td").eq(1).html();
            data.append("txtdescri", _tr.find("td").eq(2).html());
            data.append("txtcantidad", _tr.find("td").eq(4).find("input").val());
            data.append("txtprecio", _tr.find("td").eq(5).find("input").val());
            data.append("unidad", textpresentacion[0].trim());
            data.append("cmbmoneda", $("#cmbmoneda").val());
            data.append("cantequi", textpresentacion[1]);
            data.append("presseleccionada", cmbpresentacion[0])
              tipobotica = "<?php echo empty($_SESSION['config']['tipobotica']) ? 'N'  : 'S'; ?>";
            if (tipobotica == 'S') {
                data.append("lote", _tr.find("td").eq(6).find("input").val());
                data.append("fechavto", _tr.find("td").eq(7).find("input").val());
            }
            data.append("indice", i);
            axios.post('/vtas/EditarUno', data)
                .then(function(respuesta) {
                    calcularIGV();
                    calcularsubtotal(row);
                }).catch(function(error) {
                    if (error.hasOwnProperty("response")) {
                        console.log(error);
                    }
                });
        });
    }

    function funcionEnterCant(o, i) {

        var id1 = document.getElementById("1");
        $(id1).removeAttr('id', '1');
        var id2 = document.getElementById("2");
        $(id2).removeAttr('id', '2');

        cant = $(o).find("input");
        $(cant).attr('id', '1');
        $("#1").select();

        var tr = $(o).parent();
        tr.find("td").eq(5).find("input").attr('id', '2');

        var cant = document.getElementById("1");
        cant.addEventListener("keypress", function(event) {
            if (event.key === "Enter") {
                event.preventDefault();
                tr.find("td").eq(4).removeClass('focus');
                $("#2").select();
                // $('#1').removeAttr('contenteditable');
                // $('#2').focus().select();
            }
        });

        var prec = document.getElementById("2");
        prec.addEventListener("keypress", function(event) {
            if (event.key === "Enter") {
                event.preventDefault();
                // $('#2').removeClass('focus');
                // $('#2').removeAttr('contenteditable');
                tr.find("td").eq(5).removeClass('focus');
                $("#2").blur();
                // $('#body').trigger('click');
                tr.next('tr').find("td:nth-child(5) input").click();
            }
        });
    }

    // Evento enter con el precio
    $("table tbody tr td:nth-child(6) input").click(function() {
        var id = document.getElementById("2");
        $(id).removeAttr('id', '2')
        tr = $(this).closest('tr');
        $(this).attr('id', '2')
        $(this).select()
        var prec = document.getElementById("2");
        prec.addEventListener("keypress", function(event) {
            if (event.key === "Enter") {
                event.preventDefault();
                tr.find("td").eq(5).removeClass('focus');
                $("#2").blur();
                // $('#body').trigger('click');
                tr.next('tr').find("td:nth-child(5) input").click();
            }
        });
    });

    // Evento enter con precio
    // $("table tbody tr td:nth-child(7)").click(function() {
    //     var id = document.getElementById("3");
    //     $(id).removeAttr('id', '3')
    //     $(this).attr('id', '3')
    //     var prec = document.getElementById("3");
    //     prec.addEventListener("keypress", function(event) {
    //         if (event.key === "Enter") {
    //             event.preventDefault();
    //             $('#3').removeClass('focus');
    //             $('#3').removeAttr('contenteditable');
    //             $('#body').trigger('click');
    //         }
    //     });
    // });

    //Calculamos en el subtotal y total
    function calcularsubtotal(o) {
        var _tr = $(o);
        var cant = _tr.find("td").eq(4).find("input").val();
        var prec = _tr.find("td").eq(5).find("input").val();
        var subt = parseFloat(cant) * parseFloat(prec);
         columantotal = "<?php echo empty($_SESSION['config']['tipobotica']) ? 7 : 9; ?>";
        var campo = _tr.find("td").eq(columantotal);
        if (isNaN(subt)) {
            toastr.info("Dígite un número correcto")
        } else {
            campo.html(subt.toFixed(2));
            var total_col1 = 0;
            $('table tbody').find('tr').each(function(i, el) {
                //Voy incrementando las variables segun la fila ( .eq(0) representa la columna 1 )
                total_col1 += parseFloat($(this).find('td').eq(columantotal).text());
                calcularIGV();
            });
        }
    }

    //Validar precios
    function verificarValores(o) {
        calcularsubtotal(o);
        $(o).each(function() {
            var _tr = $(o);
            let premiun = <?php echo json_encode($_SESSION["carritov"]) ?>;
            var id = _tr.find("td").eq(1).html();
            var cant = _tr.find("td").eq(4).find("input").val();
            var precio = _tr.find("td").eq(5).find("input").val();

            const resultado = premiun.find(elemento => elemento.coda == id);
            cmbmoneda = $("#cmbmoneda").val();

            if (cmbmoneda == 'D') {
                preciomenor = (resultado.precio3) / Number("<?php echo $_SESSION["gene_dola"] ?>");
            } else {
                preciomenor = resultado.precio3;
            }
            preciomenor = Number(preciomenor).toFixed(2);
            // if (Number(precio) < preciomenor) {
            //     _tr.find("td").eq(5).css("backgroundColor", "#F67979");
            //     $("#grabar").attr("disabled", true);
            // } else {
            //     _tr.find("td").eq(5).css("backgroundColor", "");
            //     $("#grabar").attr("disabled", false);
            // }
        });
        validarvaloresporgrupo();
    }

    function validarvaloresporgrupo() {
        $('#griddetalle tbody tr').each(function() {
            _tr = $(this);
            let premiun = <?php echo json_encode($_SESSION["carritov"]) ?>;
            var id = _tr.find("td").eq(1).html();
            var cant = _tr.find("td").eq(4).find("input").val();
            var precio = _tr.find("td").eq(5).find("input").val();

            const resultado = premiun.find(elemento => elemento.coda == id);
            cmbmoneda = $("#cmbmoneda").val();
            // console.log(resultado);
            if (cmbmoneda == 'D') {
                preciomenor = (resultado.precio3) / Number(<?php echo $_SESSION["gene_dola"] ?>);
            } else {
                preciomenor = resultado.precio3;
            }

            preciomenor = Number(preciomenor).toFixed(2);
            // if (Number(precio) < preciomenor) {
            //     _tr.find("td").eq(5).css("backgroundColor", "#F67979");
            //     $("#grabar").attr("disabled", true);
            // } else {
            //     _tr.find("td").eq(5).css("backgroundColor", "");
            // }
        })
    }
</script>