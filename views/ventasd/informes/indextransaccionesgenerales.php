<?php

use App\View\Components\ComboAnosComponent;

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
                            <form class="form-inline" id="form-search"><br>
                                <label class="my-1 mr-2" for="">Mes</label>
                                <?php
                                $mes = new ComboAnosComponent('');
                                echo $mes->renderreportmes();
                                ?>
                                <label class="my-1 mr-2" for="">Año</label>
                                <?php
                                $ano = new ComboAnosComponent('');
                                echo $ano->renderreport();
                                ?>
                                <button type="submit" class="btn btn-primary my-1" id="btnconsultar">Consultar</button>
                                <button type="button" class="btn btn-success my-1" onclick="exportarsire();" id="btndescargarsire">Exportar SIRE</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12" id="resultado">
            </div>
            <!-- <div class="row">u
            </div> -->
            <div class="col-12" id="resultadoctas">
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
        $("#titulo").html("<?php echo $titulo ?>");
        mes = "<?php echo date('m'); ?>";
        year = "<?php echo date('Y'); ?>";
        $("#cmbmes").val(mes);
        $("#cmbano").val(year);
    }

    function search() {
        var mes = document.getElementById("cmbmes").value;
        var ano = document.getElementById("cmbano").value;
        $("#btnconsultar").attr('disabled', true);
        axios.get('/cajas/listatransaccionesgenerales', {
            "params": {
                "mes": mes,
                "ano": ano
            }
        }).then(function(respuesta) {
            const contenido_tabla = respuesta.data;
            $('#resultado').html(contenido_tabla);
            $("#btnconsultar").attr('disabled', false);
        }).catch(function(error) {
            $("#btnconsultar").attr('disabled', false);
            toastr.error('Error al cargar el listado ' + error, 'Mensaje del sistema')
        });
    }
</script>
<?php
$this->endSection('javascript');
?>