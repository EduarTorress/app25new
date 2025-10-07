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
                                            <div class="col-8" style="display:inline-block;">
                                                <div class="input-group row">
                                                    <div class="col-md-3">
                                                        <span class="input-group-btn">
                                                            <button type="submit" class="btn btn-primary">Consultar</button>
                                                        </span>
                                                    </div>
                                                    <div class="col-2"><button onclick="modalCreate()" type="button" class="btn btn-success">Registrar</button></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-12">
                        <div class="row table-responsive">
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
        titulo("<?php echo $titulo ?>");
    }

    function buscar() {
        axios.get('/numeroscuenta/lista', {
            "params": {
                "cbuscar": '%%'
            }
        }).then(function(respuesta) {
            const contenido_tabla = respuesta.data;
            $('#search').html(contenido_tabla);
        }).catch(function(error) {
            toastr.error('Error al cargar el listado' + error, 'Mensaje del sistema')
        });
    }

    function modalCreate() {
        axios.get('/numeroscuenta/create')
            .then(function(respuesta) {
                $('#modal-mantenimiento-contenido').html(respuesta.data)
                $('#modal-mantenimiento').modal('show');
            }).catch(function(error) {
                toastr.error('Error al cargar el modal de crear ' + error)
            })
    }

    function modalEdit(id) {
        const ruta = '/numeroscuenta/edit/' + id;
        axios.get(ruta)
            .then(function(respuesta) {
                $('#modal-mantenimiento-contenido').html(respuesta.data)
                $('#modal-mantenimiento').modal('show');
            })
            .catch(function(error) {
                toastr.error('Error al cargar el modal de editar ' + error)
            })
    }

    $('#modal-mantenimiento').on('shown.bs.modal', function() {
        $('#txtnombre').focus();
    })

    function cerrarmodal() {
        $('#modal-mantenimiento').modal('hide');
        $('#txtbuscar').focus();
    }

    function store(modo, id) {
        let txtnrocuenta = document.querySelector("#txtnrocuenta").value;
        if (txtnrocuenta.length == 0) {
            toastr.warning('Ingrese el Número de Cuenta');
            return;
        }
        const formulario = document.getElementById('formulario-crear');
        const data = new FormData(formulario);
        if (modo == 'N') {
            Swal.fire({
                icon: 'question',
                title: '¿Registrar Número de Cuenta?',
                text: "Se insertará como un nuevo registro.",
                // showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: 'SI',
                cancelButtonText: 'NO',
                cancelButtonColor: '#d33',
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.post('/numeroscuenta/store', data)
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
                            toastr.error('Error al registrar');
                        })
                }
            })
        } else {
            const ruta = '/numeroscuenta/update/' + id;
            Swal.fire({
                title: '¿Actualizar Número de Cuenta?',
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
                        })
                        .catch(function(error) {
                            if (error.hasOwnProperty('response')) {
                                if (error.response.status === 422) {
                                    mostrarErrores('formulario-crear', error.response.data.errors);
                                }
                            }
                            toastr.error('Hubo error al actualizar');
                        });
                }
            })
        }
    }
</script>
<?php
$this->endSection('javascript');
?>