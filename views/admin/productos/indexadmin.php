<?php

use App\View\Components\ModalGestionStockComponent;
use App\View\Components\Modaload;
?>
<?php
$this->setLayout('layouts/admin');
?>
<?php
$this->startSection('contenido');
?>
<?php
$prod = new Modaload();
echo $prod->render();
$mdGs = new ModalGestionStockComponent();
echo $mdGs->render();
?>
<div class="content-wrapper">
    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#divlista" role="tab" aria-selected="true">Lista</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#divvtasxprod" role="tab" aria-selected="false">Ventas</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="contact-tab" data-toggle="tab" href="#divcompxprod" role="tab" aria-selected="false">Compras</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="contact-tab" data-toggle="tab" href="#divlogs" role="tab" aria-selected="false">Logs</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="contact-tab" data-toggle="tab" href="#diveliminados" role="tab" aria-selected="false">Consultar Eliminados</a>
                        </li>
                        <?php if ($_SESSION['tipousuario'] == 'A') : ?>
                            <li class="nav-item">
                                <a class="nav-link" id="contact-tab" data-toggle="tab" href="#divcalcularstock" role="tab" aria-selected="false">Calcular Stock</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="divlista" role="tabpanel"><br>
                            <div class="col-lg-12">
                                <div class="card card-primary card-outline">
                                    <div class="card-body">
                                        <form class="" id="form-search">
                                            <div class="container">
                                                <div class="col-sm-9">
                                                    <div class="row">
                                                        <div class="col-sm-2">
                                                            <label class="radio-inline">
                                                                <input type="radio" name="optradios" value="nombre" onchange="obtener()" checked>Nombre&nbsp;
                                                            </label>
                                                        </div>
                                                        <div class="col-sm-2">
                                                            <label class="radio-inline">
                                                                <input type="radio" name="optradios" value="codigo" onchange="obtener()">Código&nbsp;
                                                            </label>
                                                        </div>
                                                        <div class="col-sm-2">
                                                            <label class="radio-inline">
                                                                <input type="radio" name="optradios" value="codigo1" onchange="obtener()">Código Fab.&nbsp;
                                                            </label>
                                                        </div>
                                                        <div class="col-8" style="display:inline-block;">
                                                            <div class="input-group">
                                                                <input type="text" class="form-control" style="width:40%;" name="txtbuscar" id="txtbuscar" placeholder="Ingrese nombre o código de Producto" onkeyup="mayusculas(this)" aria-label="Buscar" value="<?php echo session()->get('busqueda') ?>">
                                                                <span class="input-group-btn">
                                                                    <button type="submit" id="buscar" class="btn btn-outline-secondary">Buscar</button>
                                                                </span>&nbsp;
                                                                <?php $opt = session()->get('tiposel', '0');
                                                                if ($opt == 4) : ?>
                                                                    <button type="button" class="btn btn-success" onclick="modalCrear();" style="position:relative;"> Registrar Producto</button>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12" id="search">
                            </div>
                        </div>
                        <div class="tab-pane fade" id="divvtasxprod" role="tabpanel"><br>
                            <?php
                            $ca = new \App\View\Components\ComboAnosComponent('V');
                            echo $ca->render();
                            ?>
                            <div class="card">
                                <div class="card-body" id="resultadovtas">
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="divcompxprod" role="tabpanel"><br>
                            <?php
                            $ca = new \App\View\Components\ComboAnosComponent('C');
                            echo $ca->render();
                            ?>
                            <div class="card">
                                <div class="card-body" id="resultadocompras">
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="divlogs" role="tabpanel"><br>
                            <button class="btn btn-success" onclick="consultarcambios();">Consultar Cambios</button>
                            <br>
                            <div class="card">
                                <div class="card-body" id="resultadologs">
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="diveliminados" role="tabpanel"><br>
                            <button class="btn btn-danger" onclick="consultareliminados();">Consultar Eliminados</button>
                            <br>
                            <div class="card">
                                <div class="card-body" id="resultadoeliminados">
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="divcalcularstock" role="tabpanel"><br>
                            <button class="btn btn-primary" onclick="calcularstock();">Calcular Stock General</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<input type="hidden" id="txtidartt">
<div id="item" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" id="modal-contenido">
        </div>
    </div>
</div>
<div id="modal-mantenimiento" data-bs-backdrop="static" data-bs-keyboard="false" class="modal fade" tabindex="-1" data-keyboard="false" aria-hidden="true">
</div>
<div id="detallecombo"></div>
<div id="divpresentaciones"></div>
<?php
$this->endSection('contenido');
?>
<?php
$this->startSection('javascript');
?>
<script>
    window.onload = function() {
        moverCursorFinalTexto("txtbuscar");
        titulo("<?php echo $titulo ?>");
    }

    $(document).ready(function() {
        $('#tabla_productos').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "columnDefs": [{
                targets: 3,
                orderable: false,
                searchable: false
            }]
        });
    });

    function consultarcambios() {
        var txtidart = $("#txtidartt").val();
        if (txtidart == '') {
            toastr.error("Haga clic en un producto para consultar")
            return;
        }
        var cmbano = $("#cmbanov").val();
        axios.get('/productos/consultarlogs', {
            "params": {
                "txtidart": txtidart,
                "cmbano": cmbano
            }
        }).then(function(respuesta) {
            const contenido_tabla = respuesta.data;
            $('#resultadologs').html(contenido_tabla);
        }).catch(function(error) {
            toastr.error('Error al cargar el listado')
        });
    }

    function consultareliminados() {
        axios.get('/productos/consultareliminados', {}).then(function(respuesta) {
            const contenido_tabla = respuesta.data;
            $('#resultadoeliminados').html(contenido_tabla);
        }).catch(function(error) {
            toastr.error('Error al cargar el listado')
        });
    }

    function calcularstock() {
        axios.get('/inventarios/calcularstock')
            .then(function(respuesta) {
                rpta = respuesta.data.mensaje.trimEnd();
                Swal.fire({
                    title: "Se ejecutó correctamente",
                    text: rpta,
                    icon: "success"
                });
            }).catch(function(error) {
                toastr.error(error, 'Mensaje del sistema')
            })
    }

    document.getElementById('form-search').addEventListener('submit', function(evento) {
        evento.preventDefault();
        buscar();
    });

    $('#modal-mantenimiento').on('hidden.bs.modal', function() {
        $("#tblpresentaciones").remove();
        $("#txtidart").val("0");
    });

    function modalCrear() {
        axios.get('/productos/create')
            .then(function(respuesta) {
                $('#modal-mantenimiento').html(respuesta.data)
                $('#modal-mantenimiento').modal('show');
                $("#txtidart").val("");
            }).catch(function(error) {
                toastr.error('Error al cargar el modal ' + error, 'Mensaje del sistema')
            })
    }

    function buscarProductoxId(producto) {
        datos = new FormData();
        datos.append("idart", producto.parametro2);
        datos.append("idcat", producto.idcat);
        datos.append("idmar", producto.idmarca);
        datos.append("unid", producto.parametro3);
        datos.append("idgrupo", producto.idgrupo);
        datos.append("descri", producto.parametro1);
        datos.append('codigo', producto.prod_cod1);
        datos.append('peso', producto.peso);
        datos.append('idflete', producto.idflete);
        datos.append('prod_smin', producto.prod_smin);
        datos.append('prod_smax', producto.prod_smax);
        datos.append('costocigv', producto.costocigv);
        datos.append('costosigv', producto.costosigv);
        datos.append('flete', producto.flete);
        datos.append("prod_uti1", producto.prod_uti1)
        datos.append("prod_uti2", producto.prod_uti2)
        datos.append("prod_uti3", producto.prod_uti3)
        datos.append("tmon", producto.tmon);
        datos.append("pre1", producto.parametro5);
        datos.append("pre2", producto.parametro6);
        datos.append("pre3", producto.parametro7);
        datos.append("tipop", producto.tipro);
        datos.append("txtcoda1", producto.txtcoda1);
        <?php if (!empty($_SESSION['config']['ventasexon'])) : ?>
            datos.append("prod_tigv", producto.prod_tigv);
        <?php endif; ?>
        // console.log(Object.fromEntries(datos));
        axios.post('/productos/consultarProductoPorID/', datos)
            .then(function(respuesta) {
                $('#modal-mantenimiento').html(respuesta.data);
                $("#modal-mantenimiento").modal('show');
                $("#btnagregarpresentaciones").css("display", "block");
            }).catch(function(error) {
                toastr.error('Error al cargar el modal ' + error, 'Mensaje del sistema');
            });
    }

    function cerrarModal() {
        $("#modal-mantenimiento").modal('hide');
    }

    function buscar() {
        var abuscar = document.getElementById("txtbuscar").value;
        if (abuscar.length == 0) {
            toastr.info("Ingrese parametro a buscar", 'Mensaje del Sistema')
            return;
        }
        if (abuscar.length < 3) {
            toastr.error("La busqueda es muy corta, DELIMITAR BUSQUEDA", 'Mensaje del Sistema');
            return;
        }
        var noption = obtener();
        $("#buscar").attr('disabled', true);
        axios.get('/productos/listaadmin', {
            "params": {
                "cbuscar": abuscar,
                "option": noption
            }
        }).then(function(respuesta) {
            $("#buscar").attr('disabled', false);
            // $('#loading').modal('hide');
            const contenido_tabla = respuesta.data;
            $('#search').html(contenido_tabla);
        }).catch(function(error) {
            $("#buscar").attr('disabled', false);
            // $('#loading').modal('hide');
            toastr.error('Error al cargar el listado ' + error, 'Mensaje del sistema')
        });
    }

    function convertprectodolar() {
        cmbmoneda = $("#cmbMoneda").val();
        dolar = Number("<?php echo session()->get('gene_dola'); ?>");
        txtprecioma = $("#txtprecioma").val();
        txtprecioe = $("#txtprecioe").val();
        txtpreciome = $("#txtpreciome").val();
        txtcoston = $("#txtcoston").val();
        // costoconigv = $("#txtcostocig").val();
        // costosingiv = $("#txtcostosig").val();
        if (cmbmoneda == 'S') {
            $("#txtcoston").val(Number(txtcoston / dolar).toFixed(2));
            // $("#txtprecioma").val(Number(txtprecioma * dolar).toFixed(2));
            // $("#txtprecioe").val(Number(txtprecioe * dolar).toFixed(2));
            // $("#txtpreciome").val(Number(txtpreciome * dolar).toFixed(2));
        } else {
            $("#txtcoston").val(Number(txtcoston * dolar).toFixed(2));
            // $("#txtprecioma").val(Number(txtprecioma / dolar).toFixed(2));
            // $("#txtprecioe").val(Number(txtprecioe / dolar).toFixed(2));
            // $("#txtpreciome").val(Number(txtpreciome / dolar).toFixed(2));
        }
        calcularPreciosPorPorcentaje("#txtporcprecma", "#txtprecioma");
        calcularPreciosPorPorcentaje("#txtporcpreces", "#txtprecioe");
        calcularPreciosPorPorcentaje("#txtporcprecem", "#txtpreciome");
        // calcularcostoneto();
    }

    function obtener() {
        let vdvto = 0;
        if (document.getElementsByName('optradios')[0].checked) {
            vdvto = 'nombre';
            document.getElementById('txtbuscar').type = 'text';
        }
        if (document.getElementsByName('optradios')[1].checked) {
            vdvto = 'codigo';
            document.getElementById('txtbuscar').type = 'text';
        }
        if (document.getElementsByName('optradios')[2].checked) {
            document.getElementById('txtbuscar').type = 'text';
            vdvto = 'codigofab';
        }
        return vdvto;
    }

    function obteneridart(idart) {
        $("#txtidartt").val(idart);
    }

    function consultarvtasxprod() {
        var txtidart = $("#txtidartt").val();
        if (txtidart == '') {
            toastr.error("Haga clic en un producto para consultar", 'Mensaje del Sistema')
            return;
        }
        var cmbano = $("#cmbanov").val();
        axios.get('/productos/consultarvtasxprod', {
            "params": {
                "txtidart": txtidart,
                "cmbano": cmbano
            }
        }).then(function(respuesta) {
            const contenido_tabla = respuesta.data;
            $('#resultadovtas').html(contenido_tabla);
            $('input[type=search]').css('color', 'black');
            $('.dataTables_filter').css('color', 'black');
            $('.paginate_button').css('background-color', '#006CA7');
            $('.previous').removeClass('disabled');
        }).catch(function(error) {
            toastr.error('Error al cargar el listado', 'Mensaje del Sistema')
        });
    }

    function consultarcompxprod() {
        var txtidart = $("#txtidartt").val();
        if (txtidart == '') {
            toastr.error("Haga clic en un producto para consultar",'Mensaje del Sistema')
            return;
        }
        var cmbano = $("#cmbanoc").val();
        axios.get('/productos/consultarcompxprod', {
            "params": {
                "txtidart": txtidart,
                "cmbano": cmbano
            }
        }).then(function(respuesta) {
            const contenido_tabla = respuesta.data;
            $('#resultadocompras').html(contenido_tabla);
            $('input[type=search]').css('color', 'black');
            $('.dataTables_filter').css('color', 'black');
            $('.paginate_button').css('background-color', '#006CA7');
            $('.previous').removeClass('disabled');
            // console.log(respuesta.data.message)
        }).catch(function(error) {
            toastr.error('Error al cargar el listado' + error, 'Mensaje del sistema')
        });
    }

    function anularproducto(idart) {
        Swal.fire({
            icon: 'error',
            title: '¿Estás seguro de eliminar?',
            text: 'Esta acción no se puede revertir',
            showCancelButton: true,
            confirmButtonText: 'Si, estoy seguro',
            cancelButtonText: 'No, cancelar'
        }).then(function(respuesta) {
            if (respuesta.isConfirmed) {
                const ruta = '/productos/darBaja/' + idart;
                axios.post(ruta)
                    .then(function(respuesta) {
                        toastr.success(respuesta.data.message);
                        buscar();
                    }).catch(function(error) {
                        if (error.hasOwnProperty('response')) {
                            toastr.error(error.response.data.message, 'Mensaje del sistema');
                        }
                    })
            }
        })
    }
</script>
<?php
$this->endSection('javascript');
?>