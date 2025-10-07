<?php

use App\View\Components\EmpresaComponent;

$this->setLayout('layouts/admin');
?>
<?php
$this->startSection('contenido');
?>
<div class="content-wrapper">
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-primary card-outline">
                        <div class="card-body">
                            <form class="form-inline" id="form-search">
                                <label class="my-1 mr-2" for="txtfechai">Inicio</label>
                                <input type="date" class="form-control form-control-sm" id="txtfechai" name="txtfechai">&nbsp;
                                <label class="my-1 mr-2" for="txtfechai">Hasta</label>
                                <input type="date" class="form-control form-control-sm" id="txtfechaf" name="txtfechaf">
                                <label class="my-1 mr-2" for="">Bancos</label>
                                <select name="select" class="form-control form-control-sm" id="cmbbancos">
                                    <?php foreach ($listabancos as $b) : ?>
                                        <option value="<?php echo $b['ctas_idct'] ?>"><?php echo $b['ctas_deta'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <button class="btn btn-primary my-1">Consultar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12" id="search">
                </div>
            </div>
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
        search();
    });
    window.onload = function() {
        titulo("<?php echo $titulo ?>");
        obtenerFechas();
    }

    function search() {
        var dfechai = document.getElementById("txtfechai").value;
        var dfechaf = document.getElementById("txtfechaf").value;
        axios.get('/cajaybancos/listarinformes', {
            "params": {
                "txtfechai": dfechai,
                "txtfechaf": dfechaf,
                "cmbbancos": $("#cmbbancos").val()
            }
        }).then(function(respuesta) {
            const contenido_tabla = respuesta.data;
            $('#search').html(contenido_tabla);
        }).catch(function(error) {
            toastr.error('Error al cargar el listado ' + error, 'Mensaje del sistema')
        });
    }
</script>
<?php
$this->endSection('javascript');
?>