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
                                <?php
                                $ec = new EmpresaComponent('');
                                echo $ec->render();
                                ?> &nbsp;
                                <div>
                                    <div class="form-inline">
                                        <input type="date" value="<?php echo date('Y-m-d'); ?>" class="form-control form-control-sm" id="txtfechaf" name="txtfechaf">
                                        <button class="btn btn-primary my-1" id="btnconsultar">Consultar</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12" id="search">
            </div>
        </div>
    </div>
</div>
<?php
$this->endSection('contenido');
$this->startSection("javascript")
?>
<script>
    document.getElementById('form-search').addEventListener('submit', function(evento) {
        evento.preventDefault();
        search();
    });

    window.onload = function() {
        titulo("<?php echo $titulo ?>");
        $("#cmbAlmacen").attr("disabled", false);
        $("#cmbAlmacen").val("<?php echo $_SESSION['idalmacen'] ?>");

    }

    $(document).ready(function() {
        obtenerFechas();
    });

    function search() {
        dfechaf = document.getElementById("txtfechaf").value;
        $("#btnconsultar").attr('disabled', true);
        axios.get('/inventarios/listarstockxalmacen', {
            "params": {
                "txtfechaf": dfechaf,
                "cmbAlmacen": $("#cmbAlmacen").val()
            }
        }).then(function(respuesta) {
            $("#btnconsultar").attr('disabled', false);
            $("#search").html(respuesta.data);
        }).catch(function(error) {
            $("#btnconsultar").attr('disabled', false);
            toastr.error("Error al cargar el listado" + error, 'Mensaje del sistema')
        });
    }
</script>
<?php
$this->endSection("javascript");
?>