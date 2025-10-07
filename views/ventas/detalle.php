<table class="table table-sm small table table-hover" id="griddetalle">
    <thead>
        <tr>
            <th scope="col" style="width:2%">Opciones</th>
            <th scope="col" style="width:3%">Código</th>
            <th scope="col" style="width:28%">Producto</th>
            <th scope="col" style="width:5%">U.M.</th>
            <th scope="col" style="width:5%">Cantidad</th>
            <!-- <th scope="col" style="width:5%">Precios</th> -->
            <th scope="col" style="width:5%">Precio</th>
            <th scope="col" style="width:5%">Importe</th>
        </tr>
    </thead>
    <tbody id="carritoventas">
        <?php $i = 0; ?>
        <?php foreach ($carritov as $indice => $item) : ?>
            <?php if ($item['activo'] == 'A') { ?>
                <tr onkeyup="verificarValores(this)" onchange="obtenerPrecio(this,<?php echo $indice ?>);" onkeypress="actualizarProducto(this,<?php echo $indice ?>)">
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
                    $parametros = compact('parametro1', 'parametro2', 'parametro3', 'parametro4', 'parametro5', 'parametro6', 'parametro8', 'parametro9', 'parametro10', 'parametro11');
                    $cadena_json = json_encode($parametros);
                    ?>
                    <td><button class="btn btn-warning" onclick="quitaritem(<?php echo $indice ?>)"><a style="color:white" class="fas fa-trash-alt"></a></button>
                        <!-- <button class="btn btn-success" onclick='editaritem(<?php echo $cadena_json ?>);'><a style="color:white" class="fas fa-edit"></a></button> -->
                    </td>
                    <td><?php echo $item['coda'] ?></td>
                    <td><?php echo $item['descri'] ?></td>
                    <td><?php echo $item['unidad'] ?></td>
                    <td class="text-center" onclick="funcionEnterCant(this,<?php echo $indice ?>)" onkeypress="return isNumber(event);" contenteditable="true" name="cantidad"><?php echo number_format($item['cantidad'], 2, '.', ',') ?></td>
                    <!-- <td class="text-center">
                        <select id="precios_<?php echo $indice ?>" name="precios">
                            <option><?php echo $item['precio1']  ?></option>
                            <option><?php echo  $item['precio2'] ?></option>
                            <option selected><?php echo $item['precio3'] ?></option>
                        </select>
                    </td> -->
                    <td class="precio text-center" id="precio" onkeypress="return isNumber(event);" contenteditable="true" name="precio"><?php echo number_format($item['precio'], 2, '.', ',') ?></td>
                    <td class="text-center" class="total"><?php echo number_format(round($item['cantidad'] * $item['precio'], 2), 2, '.', ',') ?></td>
                    <?php $i++; ?>
                </tr>
            <?php } ?>
        <?php endforeach; ?>
    </tbody>
</table><br>
<div class="col-lg-12">
    <div class="card card-success card-outline" style="width:auto;">
        <div class="row">
            <div class="col-7 align-items-start">
                <br>
                <div class="input-group">
                    <button class="btn btn-primary btn-sm" role="button"><a style="color:white;" href="<?php echo "/productos/index/3" ?>">Agregar</a></button>
                    <button class="btn btn-danger btn-sm" id="cancelar" role="button" onclick="cancelarVenta()">Cancelar</button>
                    <button class="btn btn-success btn-sm" id="grabar" role="button" onclick="grabarVenta();"><?php echo (isset($btn) ? $btn : 'Grabar') ?></button>
                </div>
            </div>
            <div class="col-2 align-items-start">
                <div class="input-group mb-3" style="width: 85%;">
                    <div class="input-group-prepend">
                        <span class="input-group-text text-sm" id=""><strong>Items:</strong></span>
                    </div>
                    <input type="text" class="form-control text-right text-sm" id="totalitems" aria-label="Small" value=<?php echo  $items ?> aria-describedby="inputGroup-sizing-sm" disabled>
                </div>
            </div>
            <div class="col-3 align-items-start">
                <div class="input-group" style="width: 90%;">
                    <div class="input-group-prepend">
                        <span class="input-group-text text-sm"><strong>SubTotal</strong></span>
                    </div>
                    <input type="text" class="form-control text-right text-sm" id="subtotal" aria-label="Small" value="<?php ?>" aria-describedby="inputGroup-sizing-sm" disabled>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-9">
            </div>
            <div class="col-3">
                <div class="input-group " style="width: 90%;">
                    <div class="input-group-prepend">
                        <span class="input-group-text text-sm" id=""><strong>IGV</strong></span>
                    </div>
                    <input type="text" class="form-control text-right text-sm" id="igv" aria-label="Small" value="<?php ?>" aria-describedby="inputGroup-sizing-sm" disabled>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-9">
            </div>
            <div class="col-3 align-items-start">
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
    $('#griddetalle').DataTable({
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

    //No admitir letras, solo numeros con punto y coma.
    function isNumber(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if ((charCode < 48 || charCode > 57) && (charCode !== 8) && (charCode !== 46)) {
            return false;
        }
        return true;
    }

    // function isNumber(e) {
    //     var key = e.which;
    //     // Dígito código entre 48 y 57
    //     var isDigit = (d) => d >= 48 && d <= 57;
    //     // Punto código 46, sólo si no hay uno anterior
    //     var isValidSeparator = (d, current) => d === 46 && current.indexOf('.') < 0;
    //     if (!isDigit(key) && !isValidSeparator(key, $(this).val()))
    //         e.preventDefault();
    // }

    //Poner editable luego de quitar el focus a los campos.
    $("#body").on('click', function() {
        $('#1').attr('contenteditable', 'true');
        $('#2').attr('contenteditable', 'true');
        $('#3').attr('contenteditable', 'true');
    });

    function actualizarProducto(o, i) {
        $(o).each(function() {
            var _tr = $(o);
            const data = new FormData();
            var id = _tr.find("td").eq(1).html();
            data.append("txtprecio", _tr.find("td").eq(6).html());
            data.append("txtcantidad", _tr.find("td").eq(4).html());
            data.append("indice", i);
            axios.post('/vtas/EditarUno', data)
                .then(function(respuesta) {}).catch(function(error) {
                    console.log(error);
                });
        });
    }

    function funcionEnterCant(o, i) {
        //Eliminamos los id anteriores
        var id1 = document.getElementById("1");
        $(id1).removeAttr('id', '1');
        var id2 = document.getElementById("2");
        $(id2).removeAttr('id', '2');
        var id3 = document.getElementById("3");
        $(id3).removeAttr('id', '3');
        // var id4 = document.getElementById("4");
        // $(id4).removeAttr('id', '4');

        //Obtenemos la celda cant y le asignamos un id
        $(o).attr('id', '1');
        //Obtenemos la celda precios y precio, a ambos le asignamos un id
        var tr = $(o).parent();
        tr.find("td").eq(5).attr('id', '2');
        tr.find("td").eq(6).attr('id', '3');
        //Buscamos lo que hay dentro de la celda precios
        var p = document.getElementById('precios_' + i);

        //Obtenemos la celda cantidad con función enter
        var cant = document.getElementById("1");
        cant.addEventListener("keypress", function(event) {
            if (event.key === "Enter") {
                event.preventDefault();
                $('#1').removeClass('focus');
                $('#1').removeAttr('contenteditable');
                $('#precios_' + i).focus().select();
            }
        });
        var preci = document.getElementById("precios_" + i);
        // console.log(preci)
        $('body').on('keydown', preci, function(e) {
            if (e.which == 9) {
                e.preventDefault();
                $('#precios_' + i).blur();
                $('#3').addClass('focus');
                $('#3').focus();
            }
        });
        var prec = document.getElementById("3");
        prec.addEventListener("keypress", function(event) {
            if (event.key === "Enter") {
                event.preventDefault();
                $('#3').removeClass('focus');
                $('#3').removeAttr('contenteditable');
                $('#body').trigger('click');
            }
        });
    }

    // // Evento enter con el cantidad
    // $("table tbody tr td:nth-child(5)").click(function() {
    //     var id = document.getElementById("1");
    //     $(id).removeAttr('id', '1')
    //     $(this).attr('id', '1')
    //     var cant = document.getElementById("1");
    //     cant.addEventListener("keypress", function(event) {
    //         if (event.key === "Enter") {
    //             event.preventDefault();
    //             $('#1').removeClass('focus');
    //             $('#1').removeAttr('contenteditable');
    //             $('#body').trigger('click');
    //         }
    //     });
    // });

    // Evento enter con precio
    $("table tbody tr td:nth-child(7)").click(function() {
        var id = document.getElementById("3");
        $(id).removeAttr('id', '3')
        $(this).attr('id', '3')
        var prec = document.getElementById("3");
        prec.addEventListener("keypress", function(event) {
            if (event.key === "Enter") {
                event.preventDefault();
                $('#3').removeClass('focus');
                $('#3').removeAttr('contenteditable');
                $('#body').trigger('click');
            }
        });
    });

    //Calculamos en el subtotal y total
    function calcularsubtotal(o) {
        var _tr = $(o);
        var cant = _tr.find("td").eq(4).html();
        var prec = _tr.find("td").eq(6).html();
        var subt = parseFloat(cant) * parseFloat(prec);
        var campo = _tr.find("td").eq(7);
        if (isNaN(subt)) {
            toastr.info("Dígite un número correcto")
        } else {
            campo.html(subt.toFixed(2));
            var total_col1 = 0;
            $('table tbody').find('tr').each(function(i, el) {
                //Voy incrementando las variables segun la fila ( .eq(0) representa la fila 1 )     
                total_col1 += parseFloat($(this).find('td').eq(7).text());
                calcularIGV();
            });
        }
    }

    //Funcionamiento del combobox
    function obtenerPrecio(o, i) {
        $(o).each(function() {
            var precios = $(this).find("#precios_" + i).val();
            $(this).find(".precio").text(precios);
        });
        verificarValores(o);
        calcularsubtotal(o);
        actualizarProducto(o, i);
    }

    // //Evento con control
    $(document).keydown(function(event) {
        if (event.keyCode == 17) {
            window.location.href = '/productos/index/0';
        }
        // if (event.keyCode == 13) {
        //     toastr.info("Producto modificado correctamente")
        // }
    });

    //Validar precios
    function verificarValores(o) {
        $(o).each(function() {
            var _tr = $(o);
            let premiun = <?php echo json_encode($_SESSION["carritov"]) ?>;
            var id = _tr.find("td").eq(1).html();
            var precio = _tr.find("td").eq(6).html();
            var cant = _tr.find("td").eq(4).html();
            const resultado = premiun.find(elemento => elemento.coda === id);
            if (Number(precio) < Number(resultado.precio3)) {
                _tr.find("td").eq(6).css("backgroundColor", "#F67979");
            } else {
                _tr.find("td").eq(6).css("backgroundColor", "");
            }
            if (Number(cant) > Number(resultado.stock)) {
                _tr.find("td").eq(4).css("backgroundColor", "#F67979");
            } else {
                _tr.find("td").eq(4).css("backgroundColor", "");
            }
        })
        calcularsubtotal(o);
    }
</script>