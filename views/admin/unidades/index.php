<?php
$this->setLayout('layouts/admin');
?>
<?php
$this->startSection('contenido');
?>
<div class="content-wrapper" id="container">
    <div class="content">
        <div class="container-fluid">
            <div class="col-lg-12">
                <div class="card card-primary card-outline">
                    <div class="card-body">
                        <form class="" id="form-search">
                            <div class="container">
                                <div class="row">
                                    <div class="col-sm-9">
                                        <div class="row">
                                            <div>
                                                <label class="radio-inline">
                                                    <input type="radio" name="optradios" value="nombre" checked>&nbsp;Nombre&nbsp;
                                                </label>
                                            </div>
                                            <div class="col-8 col-sm-4" style="display:inline-block;">
                                                <div class="input-group">
                                                    <input type="text" class="form-control" name="txtbuscar" id="txtbuscar" placeholder="Ingrese el nombre del chofer" onkeyup="mayusculas(this)">
                                                    <span class="input-group-btn">
                                                        <button type="submit" class="btn btn-primary">Buscar</button>
                                                    </span>
                                                </div>
                                            </div>
                                            <button onclick="modalCreate()" type="button" class="btn btn-success">Nuevo</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="row">
                            <div class="col-lg-12" id="search">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal-mantenimiento" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" id="modal-mantenimiento-contenido">
        </div>
    </div>
</div>
<?php
$this->endSection('contenido');
?>

<?php
$this->startSection('javascript');
?>
<script>
    document.getElementById('form-search').addEventListener('submit', function(evento) {
        evento.preventDefault();
        buscar();
    });

    window.onload = function() {
        document.getElementById("txtbuscar").focus();
        titulo("<?php echo $titulo ?>");
    }

    function buscar() {
        var abuscar = document.querySelector('#txtbuscar').value;
        if (abuscar.length = 0) {
            return;
        }
        axios.get('/unidades/lista', {
            "params": {
                "cbuscar": abuscar
            }
        }).then(function(respuesta) {
            const contenido_tabla = respuesta.data;
            $('#search').html(contenido_tabla);
        }).catch(function(error) {
            toastr.error('Error al cargar el listado ' + error, 'Mensaje del sistema')
        });
    }

    function modalCreate() {
        axios.get('/unidades/create')
            .then(function(respuesta) {
                $('#modal-mantenimiento-contenido').html(respuesta.data)
                $('#modal-mantenimiento').modal('show');
            }).catch(function(error) {
                toastr.error('Error al cargar el modal de crear ' + error, 'Mensaje del sistema')
            })
    }

    function modalEdit(id) {
        const ruta = '/unidades/edit/' + id;
        axios.get(ruta)
            .then(function(respuesta) {
                $('#modal-mantenimiento-contenido').html(respuesta.data)
                $('#modal-mantenimiento').modal('show');
            }).catch(function(error) {
                toastr.error('Error al cargar el modal de editar ' + error, 'Mensaje del sistema')
            })
    }

    $('#modal-mantenimiento').on('shown.bs.modal', function() {
        $('#txtnombre').focus();
    })

    function cerrarmodal() {
        $('#modal-mantenimiento').modal('hide');
        $('#txtbuscar').focus();
    }

    function darBaja(id) {
        Swal.fire({
            icon: 'error',
            title: '¿Estás seguro de eliminar?',
            text: 'Esta acción no se puede revertir',
            showCancelButton: true,
            confirmButtonText: 'Si, estoy seguro',
            cancelButtonText: 'No, cancelar'
        }).then(function(respuesta) {
            if (respuesta.isConfirmed) {
                const ruta = '/unidades/darBaja/' + id;
                axios.post(ruta)
                    .then(function(respuesta) {
                        toastr.success('Eliminado correctamente', 'Mensaje del Sistema');
                        buscar();
                    }).catch(function(error) {
                        if (error.hasOwnProperty('response')) {
                            toastr.error(error.response.data.message, 'Mensaje del sistema');
                        }
                    })
            }
        })
    }

    function store(modo, id) {
        let cnombre = document.querySelector("#txtChofer").value;
        if (cnombre.length == 0) {
            toastr.error('Ingrese un Nombre de Unidad', 'Mensaje del sistema');
            return;
        }
        const formulario = document.getElementById('formulario-crear');
        const data = new FormData(formulario);
        if (modo == 'N') {
            Swal.fire({
                icon: 'question',
                title: '¿Registrar Unidad Vehícular?',
                // showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: 'SI',
                cancelButtonText: 'NO',
                cancelButtonColor: '#d33',
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.post('/unidades/store', data)
                        .then(function(respuesta) {
                            $('#modal-mantenimiento').modal('hide');
                            toastr.success('Registrado correctamente');
                            buscar();
                        }).catch(function(error) {
                            if (error.hasOwnProperty('response')) {
                                if (error.response.status === 422) {
                                    const respuesta_servidor = error.response.data;
                                    const errores = respuesta_servidor.errors;
                                    mostrarErrores('formulario-crear', errores);
                                }
                            }
                            toastr.error('Error al registrar' + error, 'Mensaje del sistema');
                        })
                }
            });
        } else {
            const ruta = '/unidades/update/' + id;
            Swal.fire({
                title: '¿Actualizar Unidad Vehícular?',
                text: "Se modificará con los nuevos datos",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si',
                cancelButtonText: 'NO'
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.post(ruta, data)
                        .then(function() {
                            $('#modal-mantenimiento').modal('hide');
                            buscar();
                            toastr.success('Actualizado correctamente');
                        }).catch(function(error) {
                            if (error.hasOwnProperty('response')) {
                                if (error.response.status == 422) {
                                    mostrarErrores('formulario-crear', error.response.data.errors);
                                }
                            }
                            toastr.error('Hubo error al actualizar' + error, 'Mensaje del sistema');
                        });
                }
            })
        }
    }
</script>
<?php
$this->endSection('javascript');
?>