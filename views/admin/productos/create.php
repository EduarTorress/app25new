<div class="modal-dialog modal-lg divproducto" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title"><?php echo $titulo; ?></h4>
        </div>
        <div class="modal-body">
            <div class="form-row">
                <div class="form-group col-4">
                    <?php
                    $ccat = isset($datosProducto['idcat']) ? $datosProducto['idcat'] : '';
                    $cat = new \App\View\Components\CategoriaComponent($ccat);
                    echo $cat->render();
                    ?>
                </div>
                <div class="form-group col-3">
                    <?php
                    $cmar = isset($datosProducto['idmar']) ? $datosProducto['idmar'] : '';
                    $mar = new \App\View\Components\MarcaComponent($cmar);
                    echo $mar->render();
                    ?>
                </div>
                <div class="form-group col-3">
                    <?php
                    $cunid = isset($datosProducto['unid']) ? $datosProducto['unid'] : '';
                    $unid = new \App\View\Components\UnidadComponent($cunid);
                    echo $unid->render();
                    ?>
                </div>
                <div class="form-group col-2" <?php echo (($_SESSION['config']['allcamposproductos'] == 'N') ? 'style="display:none"' : '') ?>>
                    <?php
                    $ctipp = isset($datosProducto['tipop']) ? $datosProducto['tipop'] : '';
                    $tipop = new \App\View\Components\TipoProductoComponent($ctipp);
                    echo $tipop->render();
                    ?>
                </div>
                <div class="form-group col-6">
                    <input style="display:none" type="text" style="width:200%;" class="form-control form-control-sm" id="txtidart" value="<?php echo (empty($datosProducto) ? '' : $datosProducto['idart']) ?>">
                    <label for="nombre">Descripción:</label>
                    <input type="text" onkeyup="mayusculas(this)" placeholder="Ingrese descripción del producto" name="txtdescrip" id="txtdescrip" placeholder="" class="form-control form-control-sm" value='<?php echo (empty($datosProducto) ? '' : $datosProducto['descri']) ?>' required>
                </div>
                <div class="form-group col-2">
                    <label for="codigo">Código:</label>
                    <input type="text" onclick="select()" class="form-control form-control-sm inputright" id="txtcodigoo" value="<?php echo (empty($datosProducto) ? '' : $datosProducto['codigo']) ?>" required>
                </div>
                <div class="form-group col-2">
                    <label for="peso">Peso:</label>
                    <input type="text" onclick="select()" class="form-control form-control-sm inputright" placeholder="KG" id="txtpeso" value="<?php echo (empty($datosProducto) ? '' : $datosProducto['peso']) ?>" required>
                </div>
                <div class="form-group col-2">
                    <?php
                    $idflete = isset($datosProducto['idflete']) ? $datosProducto['idflete'] : '';
                    $flet = new \App\View\Components\FleteComponent($idflete);
                    echo $flet->render();
                    ?>
                </div>
                <div class="form-group col-3" <?php echo (($_SESSION['config']['allcamposproductos'] == 'N') ? 'style="display:none"' : '') ?>>
                    <label for="" class="">Stock Mínimo:</label>
                    <input type="text" onkeypress="return isNumber(event);" onclick="select()" class="form-control form-control-sm" id="txtStockMin" value="<?php echo (empty($datosProducto) ? '' : $datosProducto['prod_smin']) ?>">
                </div>
                <div class="form-group col-3" <?php echo (($_SESSION['config']['allcamposproductos'] == 'N') ? 'style="display:none"' : '') ?>>
                    <label for="">Stock Máximo:</label>
                    <input type="text" onkeypress="return isNumber(event);" onclick="select()" class="form-control form-control-sm" id="txtStockMax" value="<?php echo (empty($datosProducto) ? '' : $datosProducto['prod_smax']) ?>">
                </div>
                <div class="form-group <?php echo (($_SESSION['config']['allcamposproductos'] == 'N') ? 'col-4' : 'col-3') ?>">
                    <label for="">Costo sin IGV:</label>
                    <input type="text" onkeypress="return isNumber(event);" onclick="select()" class="form-control form-control-sm inputright" id="txtcostosig" value="<?php echo (empty($datosProducto) ? '' : $datosProducto['costosigv']) ?>" required>
                </div>
                <div class="form-group <?php echo (($_SESSION['config']['allcamposproductos'] == 'N') ? 'col-4' : 'col-3') ?>">
                    <label for="">Costo con IGV:</label>
                    <input type="text" onkeypress="return isNumber(event);" onclick="select()" class="form-control form-control-sm inputright" id="txtcostocig" value="<?php echo (empty($datosProducto) ? '' : $datosProducto['costocigv']) ?>" required>
                </div>
                <div class="form-group col-4" <?php echo (($_SESSION['config']['allcamposproductos'] == 'N') ? 'style="display:none"' : '') ?>>
                    <label for="">Costo Transp:</label>
                    <input type="text" onkeypress="return isNumber(event);" onclick="select()" disabled class="form-control form-control-sm inputright" id="txtcostot" value="<?php echo (empty($datosProducto) ? '' : $datosProducto['flete']) ?>">
                </div>
                <div class="form-group col-4">
                    <label for="">Costo Neto:</label>
                    <input type="text" onkeypress="return isNumber(event);" onclick="select()" class="form-control form-control-sm inputright" id="txtcoston" value="<?php echo (empty($datosProducto) ? '' : $datosProducto['costocigv']) ?>" required>
                </div>
                <div style="display:none" class="form-group col-4" <?php echo (($_SESSION['config']['allcamposproductos'] == 'N') ? 'style="display:none"' : '') ?>>
                    <label for="" class="">Moneda:</label>
                    <div>
                        <?php $cmon = isset($datosProducto['tmon']) ? $datosProducto['tmon'] : ''; ?>
                        <select onchange="convertprectodolar()" class="form-control form-control-sm" id="cmbMoneda" name="cmbMoneda">
                            <option <?php echo empty($cmon) ? 'selected ' : ($cmon == 'S' ? 'selected' : '') ?> value="S">Soles</option>
                            <?php if ($_SESSION['config']['facturardolares'] == 'S') : ?>
                                <option <?php echo ($cmon == 'D' ? 'selected' : '') ?> value="D">Dólares</option>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>
                <div class="form-group col-4" <?php echo (($_SESSION['config']['allcamposproductos'] == 'N') ? 'style="display:none"' : '') ?>>
                    <label for="codigo">Cod Prov:</label>
                    <input type="text" onclick="select()" class="form-control form-control-sm inputright" id="txtcoda1" value="<?php echo (empty($datosProducto) ? '' : $datosProducto['txtcoda1']) ?>" required>
                </div>
                <div class="form-group col-3" style="display:none;">
                    <label for="">Comisión Efect:</label>
                    <input type="text" onkeypress="return isNumber(event);" onclick="select()" class="form-control form-control-sm" id="txtcomisione" value="<?php echo (empty($datosProducto) ? '' : $datosProducto['prod_come']) ?>" required>
                </div>
                <div class="form-group col-3" style="display:none;">
                    <label for="">Comisión Cred:</label>
                    <input type="text" onkeypress="return isNumber(event);" onkeypress="return isNumber(event);" onclick="select()" class="form-control form-control-sm" id="txtcomisionc" value="<?php echo (empty($datosProducto) ? '' : $datosProducto['prod_comc']) ?>" required>
                </div>
                <div class="form-group col-4" <?php echo (($_SESSION['config']['allcamposproductos'] == 'N') ? 'style="display:none"' : '') ?>>
                    <label for="">% Precio Mayor:</label>
                    <input type="text" onkeypress="return isNumber(event);" onclick="select()" class="form-control form-control-sm inputright" id="txtporcprecma" value="<?php echo (empty($datosProducto) ? '' : $datosProducto['prod_uti1']) ?>" required>
                </div>
                <div class="form-group col-4" <?php echo (($_SESSION['config']['allcamposproductos'] == 'N') ? 'style="display:none"' : '') ?>>
                    <label for="">% Precio Especial:</label>
                    <input type="text" onkeypress="return isNumber(event);" onclick="select()" class="form-control form-control-sm inputright" id="txtporcpreces" value="<?php echo (empty($datosProducto) ? '' : $datosProducto['prod_uti2']) ?>" required>
                </div>
                <div class="form-group col-4" <?php echo (($_SESSION['config']['allcamposproductos'] == 'N') ? 'style="display:none"' : '') ?>>
                    <label for="">% Precio Menor:</label>
                    <input type="text" onkeypress="return isNumber(event);" onclick="select()" class="form-control form-control-sm inputright" id="txtporcprecem" value="<?php echo (empty($datosProducto) ? '' : $datosProducto['prod_uti3']) ?>" required>
                </div>
                <div class="form-group col-4" <?php echo (($_SESSION['config']['allcamposproductos'] == 'N') ? 'style="display:none"' : '') ?>>
                    <label for="">Precio Mayor:</label>
                    <input type="text" onkeypress="return isNumber(event);" onclick="select()" class="form-control form-control-sm inputright" id="txtprecioma" value="<?php echo (empty($datosProducto) ? '' : $datosProducto['pre1']) ?>" required>
                </div>
                <div class="form-group col-4" <?php echo (($_SESSION['config']['allcamposproductos'] == 'N') ? 'style="display:none"' : '') ?>>
                    <label for="">Precio Especial:</label>
                    <input type="text" onkeypress="return isNumber(event);" onclick="select()" class="form-control form-control-sm inputright" id="txtprecioe" value="<?php echo (empty($datosProducto) ? '' : $datosProducto['pre2']) ?>" required>
                </div>
                <div class="form-group col-4" <?php echo (($_SESSION['config']['allcamposproductos'] == 'N') ? 'style="display:none"' : '') ?>>
                    <label for="">Precio Menor:</label>
                    <input type="text" onkeypress="return isNumber(event);" onclick="select()" class="form-control form-control-sm inputright" id="txtpreciome" value="<?php echo (empty($datosProducto) ? '' : $datosProducto['pre3']) ?>" required>
                </div>
                <div class="form-group col-12">
                    <div class="row" id="divdetapresentaciones">
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <div class="form-group col-6 text-start" <?php echo ((empty($_SESSION['config']['ventasexon'])) ? 'style="display:none"' : '')  ?>>
                <!-- <?php $dp = (empty($datosProducto['prod_tigv']) ? $_SESSION['gene_igv'] : $_SESSION['gene_igv']); ?> -->
                 <?php $dp = $datosProducto['prod_tigv']; ?>
                <?php $prod_tigv = (floatval($dp) == floatval($_SESSION['gene_igv'])) ? $_SESSION['gene_igv'] : $datosProducto['prod_tigv']; ?>
                  <?php if (empty($_SESSION['config']['ventasexon'])) {
                    $prod_tigv = $_SESSION['gene_igv'];
                } ?>
                <div class="form-check form-check-inline">
                    <input class="form-check-input igvproducto" type="radio" name="igvproducto" <?php echo (floatval($prod_tigv) == 1 ? 'checked' : '') ?> onclick="" value="E" onchange="cambiarcostoigv();">
                    <label class="form-check-label" for="incluido"><b>Exonerado</b></label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input igvproducto" type="radio" <?php echo (floatval($prod_tigv) == floatval($_SESSION['gene_igv']) ? 'checked' : '') ?> name="igvproducto" value="N" onchange="cambiarcostoigv();">
                    <label class="form-check-label" for="noincluido"><b>No Exonerado</b></label>
                </div>
            </div>
            <?php if (($_SESSION['config']['variasunidmed'] == 'S')) : ?>
                <button class="btn btn-dark" id="btnagregarpresentaciones" onclick="openmodalpresent()" style="<?php echo (empty($datosProducto) ? 'display:none' : '') ?>"><i class="fas fa-plus"></i> Agregar</button>
            <?php endif; ?>
            <button class="btn btn-primary" onclick="grabarproducto()"><i class="fas fa-save"></i> Grabar</button>
            <?php if (empty($datosProducto)) : ?>
                <button type="button" id="btncerrarpres" class="btn btn-danger" onclick="cerrarModal()">Cerrar</button>
            <?php endif; ?>
        </div>
    </div>
</div>
<script>
    $('#cmbpresentacionesc').selectpicker();

    function validarcampos() {
        let txtdescrip = document.getElementById("txtdescrip").value;
        let txtcostosig = document.getElementById("txtcostosig").value;
        let txtcostocig = document.getElementById("txtcostocig").value;
        let txtpeso = document.getElementById("txtpeso").value;
        let txtcoston = document.getElementById("txtcoston").value;
        if (txtdescrip == '') {
            toastr.error("Ingrese una descripción al producto", 'Mensaje del sistema');
            return false;
        }
        if (txtcostosig == '') {
            toastr.error("Ingrese un costo", 'Mensaje del sistema');
            return false;
        }
        if (txtcostocig == '') {
            toastr.error("Ingrese un costo", 'Mensaje del sistema');
            return false;
        }
        if (txtpeso == '' || Number(txtpeso) == 0) {
            toastr.error("Ingrese un peso", 'Mensaje del sistema');
            return false;
        }
        if (txtcoston == '') {
            toastr.error("Ingrese el costo neto", 'Mensaje del sistema');
            return false;
        }
        return true;
    }

    function openmodalpresent() {
        axios.get('/productos/listarmodalpres', {
            // "params": {
            //     "cbuscar": abuscar,
            //     "option": noption
            // }
        }).then(function(respuesta) {
            const contenido_tabla = respuesta.data;
            $('#divpresentaciones').html(contenido_tabla);
            $("#modal_presentaciones").modal('show');
        }).catch(function(error) {
            toastr.error('Error al cargar el listado' + error, 'Mensaje del sistema')
        });
    }

    function actualizar() {
        if (validarcampos() == false) {
            return;
        }
        Swal.fire({
            title: "Mensaje del Sistema",
            text: "¿Desea actualizar el producto? ",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, modificar'
        }).then(function(respuesta) {
            if (respuesta.isConfirmed) {
                let txtcodigo = document.getElementById("txtcodigoo").value;
                let cmbgrupo = 1;
                let cmbcategoria = document.getElementById("cmbcategoria").value;
                let cmbmarca = document.getElementById("cmbmarca").value;
                let txtdescrip = document.getElementById("txtdescrip").value;
                let cmbunidad = document.getElementById("cmbunidad").value;
                let cmbtipp = document.getElementById("cmbtipoproducto").value;
                let cmbest = "1";
                let txtpeso = document.getElementById("txtpeso").value;
                let txtStockMin = document.getElementById("txtStockMin").value;
                let txtStockMax = document.getElementById("txtStockMax").value;
                // let txtcodprov = document.getElementById("txtStockMax").value;
                let cmbMoneda = document.getElementById("cmbMoneda").value;
                // let txtprecioc = document.getElementById("txtprecioc").value;
                // let txttcprod = document.getElementById("txttcproducto").value;
                let txtcostosig = document.getElementById("txtcostosig").value;
                let txtcostocig = document.getElementById("txtcostocig").value;
                cmbCostoT = document.getElementById("cmbCostoT").value;
                cmbCostoT = cmbCostoT.split('-');
                let txtcostot = cmbCostoT[0];
                let txtcoston = document.getElementById("txtcoston").value;
                let porcprecma = document.getElementById("txtporcprecma").value;
                txtporcprecma = ((Number(porcprecma) / 100) + 1).toFixed(6);
                let txtprecioma = document.getElementById("txtprecioma").value;
                let porcpreces = document.getElementById("txtporcpreces").value;
                txtporcpreces = ((Number(porcpreces) / 100) + 1).toFixed(6);
                let txtprecioe = document.getElementById("txtprecioe").value;
                let porcprecem = document.getElementById("txtporcprecem").value;
                txtporcprecem = ((Number(porcprecem) / 100) + 1).toFixed(6);
                let txtpreciome = document.getElementById("txtpreciome").value;
                let txtcomisione = document.getElementById("txtcomisione").value;
                let txtcomisionc = document.getElementById("txtcomisionc").value;

                //(Porcentaje / 100 ) + 1

                data = new FormData();
                data.append("idart", $("#txtidart").val());
                data.append("txtcodigo", txtcodigo);
                data.append("cmbgrupo", cmbgrupo);
                data.append("cmbcategoria", cmbcategoria);
                data.append("cmbmarca", cmbmarca);
                data.append("txtdescrip", txtdescrip);
                data.append("cmbunidad", cmbunidad);
                data.append("cmbtipp", cmbtipp);
                data.append("cmbest", cmbest);
                data.append("txtpeso", txtpeso);
                data.append("txtStockMin", txtStockMin);
                data.append("txtStockMax", txtStockMax);
                data.append("txtcodprov", "0");
                data.append("cmbMoneda", cmbMoneda);
                data.append("txtprecioc", 0.00);
                data.append("txttcprod", 0.00);
                data.append("txtcostosig", txtcostosig);
                data.append("txtcostocig", txtcostocig);
                data.append("txtcostot", txtcostot);
                data.append("txtcoston", txtcoston);
                data.append("txtporcprecma", txtporcprecma);
                data.append("txtprecioma", txtprecioma);
                data.append("txtporcpreces", txtporcpreces);
                data.append("txtprecioe", txtprecioe);
                data.append("txtporcprecem", txtporcprecem);
                data.append("txtpreciome", txtpreciome);
                data.append("txtcomisione", txtcomisione);
                data.append("txtcomisionc", txtcomisionc);
                data.append("txtcoda1", $("#txtcoda1").val())
                <?php if (!empty($_SESSION['config']['ventasexon'])) : ?>
                    data.append("prod_tigv", obtenerTipoIGVProducto());
                <?php endif; ?>
                axios.post("/productos/actualizar", data)
                    .then(function(respuesta) {
                        toastr.success(respuesta.data.message, 'Mensaje del Sistema')
                        limpiarTodo();
                        $("#modal-mantenimiento").modal('hide');
                        buscar();
                    }).catch(function(error) {
                        if (error.response.status === 422) {
                            errors = error.response.data.errors;
                            showtoastrerrors(errors);
                        }
                    });
            }
        });
    }

    function grabarproducto() {
        idart = $("#txtidart").val();
        if (idart == '') {
            registrar();
        } else {
            actualizar();
        }
    }

    function registrar() {
        if (validarcampos() == false) {
            return;
        }
        Swal.fire({
            title: "Mensaje del Sistema",
            text: "¿Desea grabar el producto? ",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si'
        }).then(function(respuesta) {
            if (respuesta.isConfirmed) {
                let txtcodigo = document.getElementById("txtcodigoo").value;
                let cmbgrupo = 1;
                let cmbcategoria = document.getElementById("cmbcategoria").value;
                let cmbmarca = document.getElementById("cmbmarca").value;
                let txtdescrip = document.getElementById("txtdescrip").value;
                let cmbunidad = document.getElementById("cmbunidad").value;
                let cmbtipp = document.getElementById("cmbtipoproducto").value;
                let cmbest = "1";
                let txtpeso = document.getElementById("txtpeso").value;
                let txtStockMin = document.getElementById("txtStockMin").value;
                let txtStockMax = document.getElementById("txtStockMax").value;
                // let txtcodprov = document.getElementById("txtStockMax").value;
                let cmbMoneda = document.getElementById("cmbMoneda").value;
                // let txtprecioc = document.getElementById("txtprecioc").value;
                // let txttcprod = document.getElementById("txttcproducto").value;
                let txtcostosig = document.getElementById("txtcostosig").value;
                let txtcostocig = document.getElementById("txtcostocig").value;
                cmbCostoT = document.getElementById("cmbCostoT").value;
                cmbCostoT = cmbCostoT.split('-');
                let txtcostot = cmbCostoT[0];
                let txtcoston = document.getElementById("txtcoston").value;
                let porcprecma = document.getElementById("txtporcprecma").value;
                txtporcprecma = ((Number(porcprecma) / 100) + 1).toFixed(6);
                let txtprecioma = document.getElementById("txtprecioma").value;
                let porcpreces = document.getElementById("txtporcpreces").value;
                txtporcpreces = ((Number(porcpreces) / 100) + 1).toFixed(6);
                let txtprecioe = document.getElementById("txtprecioe").value;
                let porcprecem = document.getElementById("txtporcprecem").value;
                txtporcprecem = ((Number(porcprecem) / 100) + 1).toFixed(6);
                let txtpreciome = document.getElementById("txtpreciome").value;
                let txtcomisione = document.getElementById("txtcomisione").value;
                let txtcomisionc = document.getElementById("txtcomisionc").value;

                // if (cmbMoneda == 'D') {
                //     txtcoston = Number(txtcoston / dolar).toFixed(2);
                //     txtprecioma = Number(txtprecioma * dolar).toFixed(2);
                //     txtprecioe = Number(txtprecioe * dolar).toFixed(2);
                //     txtpreciome = Number(txtpreciome * dolar).toFixed(2);
                // }

                data = new FormData();
                data.append("txtcodigo", txtcodigo);
                data.append("cmbgrupo", cmbgrupo);
                data.append("cmbcategoria", cmbcategoria);
                data.append("cmbmarca", cmbmarca);
                data.append("txtdescrip", txtdescrip);
                data.append("cmbunidad", cmbunidad);
                data.append("cmbtipp", cmbtipp);
                data.append("cmbest", cmbest);
                data.append("txtpeso", txtpeso);
                data.append("txtStockMin", txtStockMin);
                data.append("txtStockMax", txtStockMax);
                data.append("txtcodprov", "0");
                data.append("cmbMoneda", cmbMoneda);
                data.append("txtprecioc", 0.00);
                data.append("txttcprod", 0.00);
                data.append("txtcostosig", txtcostosig);
                data.append("txtcostocig", txtcostocig);
                data.append("txtcostot", txtcostot);
                data.append("txtcoston", txtcoston);
                data.append("txtporcprecma", txtporcprecma);
                data.append("txtprecioma", txtprecioma);
                data.append("txtporcpreces", txtporcpreces);
                data.append("txtprecioe", txtprecioe);
                data.append("txtporcprecem", txtporcprecem);
                data.append("txtpreciome", txtpreciome);
                data.append("txtcomisione", txtcomisione);
                data.append("txtcomisionc", txtcomisionc);
                data.append("txtcoda1", $("#txtcoda1").val())
                <?php if (!empty($_SESSION['config']['ventasexon'])) : ?>
                    data.append("prod_tigv", obtenerTipoIGVProducto());
                <?php endif; ?>
                axios.post("/productos/registrar", data)
                    .then(function(respuesta) {
                        // toastr.success(respuesta.data.message)
                        Swal.fire({
                            icon: "success",
                            title: respuesta.data.message,
                            text: "Se ingresó a la base de datos.",
                            showConfirmButton: false,
                            timer: 4750
                        });
                        $("#txtidart").val(respuesta.data.idregistro);
                        $("#btnagregarpresentaciones").css("display", "block");
                        $("#btncerrarpres").css("display", "none");
                        // limpiarTodo();
                        // $("#modal-mantenimiento").modal('hide');
                    }).catch(function(error) {
                        if (error.hasOwnProperty("response")) {
                            if (error.response.status === 422) {
                                errors = error.response.data.errors;
                                showtoastrerrors(errors);
                            }
                        }
                    });
            }
        });
    }

    function obtenerFlete() {
        cmbCostoT = document.getElementById("cmbCostoT").value;
        cmbCostoT = cmbCostoT.split('-');
        $("#txtcostot").val(cmbCostoT[1]);
        costot = $("#txtcostot").val();
        txtcostocig = $("#txtcostocig").val();
        $("#txtcoston").val((Number(costot) + Number(txtcostocig)).toFixed(2));
    }

    function listardetapresxproducto() {
        idart = $("#txtidart").val();
        axios.get('/presentaciondetalle/listar', {
            "params": {
                "idart": idart
            }
        }).then(function(respuesta) {
            const contenido_tabla = respuesta.data;
            $('#divdetapresentaciones').html(contenido_tabla);
        }).catch(function(error) {
            toastr.error('Error al cargar el listado ' + error, 'Mensaje del sistema')
        });
    }

    function limpiarTodo() {
        var elements = document.getElementsByTagName(".divproducto input");
        for (var ii = 0; ii < elements.length; ii++) {
            if (elements[ii].type == "text") {
                if (elements[ii].id != "txtbuscar") {
                    elements[ii].value = "";
                }
            }
        }
    }

    listardetapresxproducto();

    function eliminardetallepres(id) {
        filabtlpresentaciones = $('#tblpresentaciones tbody tr').length;
        if (filabtlpresentaciones == 1) {
            toastr.error("El producto no puede quedarse sin ninguna presentación, tiene que agregar otra si lo desea eliminar", "Mensaje del sistema");
            return;
        }
        data = new FormData();
        data.append("id", id);
        axios.post("/presentaciondetalle/eliminar", data)
            .then(function(respuesta) {
                toastr.success(respuesta.data.message);
                listardetapresxproducto();
            }).catch(function(error) {
                toastr.error(error, 'Mensaje del sistema');
            });
    }

    function obtenerTipoIGVProducto() {
        let vdvto = Number("<?php echo $_SESSION['gene_igv']; ?>");
        if (document.getElementsByName("igvproducto")[0].checked) {
            vdvto = 1;
        }
        if (document.getElementsByName("igvproducto")[1].checked) {
            vdvto = Number("<?php echo $_SESSION['gene_igv']; ?>");
        }
        return vdvto;
    }

    cambiarcostoigv();

    function cambiarcostoigv() {
        igv = obtenerTipoIGVProducto();
        if (igv == 1) {
            costocigv = $("#txtcostocig").val();
            $("#txtcostocig").val(Number(costocigv).toFixed(2));
            $("#txtcostosig").val(Number(costocigv).toFixed(2));
            $("#txtcoston").val(Number(costocigv).toFixed(2));
        } else {
            calcularcostosinigv();
        }
    }

    function calcularcostoneto() {
        costoconigv = $("#txtcostocig").val();
        costotransporte = $("#txtcostot").val();
        $("#txtcoston").val(Number(costoconigv) + Number(costotransporte));
    }

    function calcularcostosinigv() {
        txtcostocig = parseFloat($("#txtcostocig").val());
        txtcostosig = (txtcostocig / <?php echo $_SESSION['gene_igv'] ?>);
        if (isNaN(txtcostosig)) {
            $("#txtcostosig").val("0.00");
        } else {
            $("#txtcostosig").val(txtcostosig.toFixed(4));
        }
    }

    function calcularCostoConIGV() {
        txtcostosig = parseFloat($("#txtcostosig").val());
        txtcostocig = ((txtcostosig * 0.18) + txtcostosig);
        if (isNaN(txtcostocig)) {
            $("#txtcostocig").val("0.00");
        } else {
            $("#txtcostocig").val(txtcostocig.toFixed(2));
        }
    }

    //Calcular precios por porcentaje
    function calcularPreciosPorPorcentaje(precioporc, precio) {
        txtcoston = parseFloat($("#txtcoston").val());
        txtporprecio = parseFloat($(precioporc).val());
        <?php if ($_SESSION['config']['valorutilidad'] == 'D') : ?>
            preciod = ((txtporprecio / 100) + 1) * <?php echo  $_SESSION['config']['valorutilidad']; ?>;
            preciod = txtcoston / preciod;
        <?php else : ?>
            preciod = ((txtporprecio / 100) + 1) * txtcoston;
        <?php endif; ?>
        if (isNaN(preciod)) {
            $(precio).val("0.00");
        } else {
            $(precio).val(preciod.toFixed(2));
        }
    }

    //Calcular porcentaje por precio
    function calcularPorcentajePorPrecio(precio, porcentaje) {
        txtcoston = parseFloat($("#txtcoston").val());
        txtprecio = parseFloat($(precio).val());
        diferencia = txtprecio - txtcoston;
        $porcprecio = ((diferencia * 100) / txtcoston);
        if (isNaN($porcprecio)) {
            $(porcentaje).val("0.00");
        } else {
            $(porcentaje).val($porcprecio.toFixed(2));
        }
    }

    //Evento para agregar IGV
    var txtcostosig = document.getElementById("txtcostosig");
    txtcostosig.addEventListener("blur", function(event) {
        igv = obtenerTipoIGVProducto();
        if (igv == 1) {
            txtcostosig = $("#txtcostosig").val();
            $("#txtcostocig").val(Number(txtcostosig).toFixed(2));
            $("#txtcoston").val(Number(txtcostosig).toFixed(2));
        } else {
            calcularCostoConIGV();
            calcularcostoneto();
        }
    }, true);

    var txtcostocig = document.getElementById("txtcostocig");
    txtcostocig.addEventListener("blur", function(event) {
        igv = obtenerTipoIGVProducto();
        if (igv == 1) {
            costocigv = $("#txtcostocig").val();
            $("#txtcostocig").val(Number(costocigv).toFixed(2));
            $("#txtcostosig").val(Number(costocigv).toFixed(2));
            $("#txtcoston").val(Number(costocigv).toFixed(2));
        } else {
            calcularcostoneto();
            calcularcostosinigv();
        }
    }, true);

    var txtcostot = document.getElementById("txtcostot");
    txtcostot.addEventListener("blur", function(event) {
        calcularcostoneto();
    }, true);

    //Porcentaje precio mayor
    var txtporcprecma = document.getElementById("txtporcprecma");
    txtporcprecma.addEventListener("blur", function(event) {
        calcularPreciosPorPorcentaje("#txtporcprecma", "#txtprecioma");
    }, true);

    //Porcentaje precio especial
    var txtporcpreces = document.getElementById("txtporcpreces");
    txtporcpreces.addEventListener("blur", function(event) {
        calcularPreciosPorPorcentaje("#txtporcpreces", "#txtprecioe");
    }, true);

    //Porcentaje precio menor    
    var txtporcprecem = document.getElementById("txtporcprecem");
    txtporcprecem.addEventListener("blur", function(event) {
        calcularPreciosPorPorcentaje("#txtporcprecem", "#txtpreciome");
    }, true);

    //Precio mayor
    var txtprecioma = document.getElementById("txtprecioma");
    txtprecioma.addEventListener("blur", function(event) {
        calcularPorcentajePorPrecio("#txtprecioma", "#txtporcprecma");
    }, true);

    //Precio especial
    var txtprecioe = document.getElementById("txtprecioe");
    txtprecioe.addEventListener("blur", function(event) {
        calcularPorcentajePorPrecio("#txtprecioe", "#txtporcpreces");
    }, true);

    //Precio menor
    var txtprecioe = document.getElementById("txtpreciome");
    txtpreciome.addEventListener("blur", function(event) {
        calcularPorcentajePorPrecio("#txtpreciome", "#txtporcprecem");
    }, true);

    //UNIDADES DE MEDIDA (PRESENTACIONES)
    function store(modo, id) {
        let cnombre = document.querySelector("#txtnombre").value;
        if (cnombre.length == 0) {
            toastr.error('Ingrese un Nombre de Unidad Medida', 'Mensaje del sistema');
            return;
        }
        let txtcantidad = document.querySelector("#txtcantidadd").value;
        if (txtcantidad.length == 0) {
            toastr.error('Ingrese una Cantidad', 'Mensaje del sistema');
            return;
        }
        const formulario = document.getElementById('formulario-crear');
        const data = new FormData(formulario);
        if (modo == 'N') {
            axios.post('/admin/unidadesmedida/store', data)
                .then(function(respuesta) {
                    $('#modal-mantenimiento-presentacion').modal('hide');
                    $('#modal_presentaciones').modal('hide');
                    idpres = respuesta.data.id;
                    toastr.success('Registrado correctamente', 'Mensaje del Sistema');
                    axios.get('/productos/listarmodalpres', {}).then(function(respuesta) {
                        const contenido_tabla = respuesta.data;
                        $('#divpresentaciones').html(contenido_tabla);
                        $("#modal_presentaciones").modal('show');
                        valpres = idpres + "-" + txtcantidad;
                        $('#cmbpresentacionesc').selectpicker('val', valpres);
                        $("#modal_presentaciones").on("shown.bs.modal", function() {
                            $("#txtpreciopres").focus();
                            $("#txtpreciopres").select();
                        });
                    }).catch(function(error) {
                        toastr.error('Error al cargar el listado' + error, 'Mensaje del sistema')
                    });
                }).catch(function(error) {
                    toastr.error('Error al registrar ' + error, "Mensaje del sistema");
                })
        }
    }
</script>
<script>
    $('#txtdescrip').keypress(function(e) {
        if (e.keyCode == 13) {
            $("#txtcodigoo").focus();
            $("#txtcodigoo").click();
        }
    });
    $('#txtcodigoo').keypress(function(e) {
        if (e.keyCode == 13) {
            $("#txtpeso").focus();
            $("#txtpeso").click();
        }
    });
    $('#txtpeso').keypress(function(e) {
        if (e.keyCode == 13) {
            $("#txtStockMin").focus();
            $("#txtStockMin").click();
        }
    });
    $('#txtStockMin').keypress(function(e) {
        if (e.keyCode == 13) {
            $("#txtStockMax").focus();
            $("#txtStockMax").click();
        }
    });
    $('#txtStockMax').keypress(function(e) {
        if (e.keyCode == 13) {
            $("#txtcostosig").focus();
            $("#txtcostosig").click();
        }
    });
    $('#txtcostosig').keypress(function(e) {
        if (e.keyCode == 13) {
            $("#txtcostocig").focus();
            $("#txtcostocig").click();
        }
    });
    $('#txtcostocig').keypress(function(e) {
        if (e.keyCode == 13) {
            $("#txtcoston").focus();
            $("#txtcoston").click();
        }
    });
    $('#txtcoston').keypress(function(e) {
        if (e.keyCode == 13) {
            $("#txtcoda1").focus();
            $("#txtcoda1").click();
        }
    });
    $('#txtcoda1').keypress(function(e) {
        if (e.keyCode == 13) {
            $("#txtporcprecma").focus();
            $("#txtporcprecma").click();
        }
    });
    $('#txtporcprecma').keypress(function(e) {
        if (e.keyCode == 13) {
            $("#txtporcpreces").focus();
            $("#txtporcpreces").click();
        }
    });
    $('#txtporcprecma').keypress(function(e) {
        if (e.keyCode == 13) {
            $("#txtporcpreces").focus();
            $("#txtporcpreces").click();
        }
    });
    $('#txtporcpreces').keypress(function(e) {
        if (e.keyCode == 13) {
            $("#txtporcprecem").focus();
            $("#txtporcprecem").click();
        }
    });
    $('#txtporcprecem').keypress(function(e) {
        if (e.keyCode == 13) {
            $("#txtprecioma").focus();
            $("#txtprecioma").click();
        }
    });
    $('#txtprecioma').keypress(function(e) {
        if (e.keyCode == 13) {
            $("#txtprecioe").focus();
            $("#txtprecioe").click();
        }
    });
    $('#txtprecioe').keypress(function(e) {
        if (e.keyCode == 13) {
            $("#txtpreciome").focus();
            $("#txtpreciome").click();
        }
    });
</script>