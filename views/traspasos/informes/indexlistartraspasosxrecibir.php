<?php

use App\View\Components\EmpresaComponent;
use App\View\Components\ModalDetalleDctoComponent;

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
                                $ec = new EmpresaComponent($_SESSION['idalmacen']);
                                echo $ec->render();
                                ?> &nbsp;
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
$md = new ModalDetalleDctoComponent();
echo $md->render();
?>
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
        axios.get('/traspasos/listarxrecibir', {
            "params": {
                "cmbalmacen": $("#cmbAlmacen").val()
            }
        }).then(function(respuesta) {
            const contenido_tabla = respuesta.data;
            $('#search').html(contenido_tabla);
        }).catch(function(error) {
            toastr.error('Error al cargar el listado ' + error, 'Mensaje del sistema')
        });
    }

    function consultardetalle(idauto) {
        axios.get('/traspasos/verdetalletraspaso', {
            "params": {
                "idauto": idauto
            }
        }).then(function(respuesta) {
            detalle = respuesta.data.listado;
            $("#txtidauto").val(idauto);
            $("#tbldetalle tbody").empty();
            var subtotal = 0;
            var total = 0;
            detalle.forEach(function(d) {
                $("#lblmodaldetalle").text("Detalle: ");
                subtotal = Number(d.cant) * Number(d.prec);
                var tr = `<tr> 
                        <td class="text-sm">` + d.descri + `</td>
                         <td>` + d.unid + `</td>
                        <td>` + d.cant + `</td>
                        <td>` + d.prec + `</td>
                        <td>` + subtotal.toFixed(2) + `</td>
                        </tr>`;
                total = total + subtotal;
                $('#tbldetalle tbody').append(tr);
            });
            $("#txtimportemodal").val("" + total.toFixed(3))
            $("#modaldetalle").modal('show');
        }).catch(function(error) {
            toastr.error('Error al cargar el listado' + error, 'Mensaje del sistema')
        });
    }

    function recibirtraspaso() {
        $("#btnrecibir").attr("disabled", "disabled");
        axios.get('/traspasos/aceptartraspaso', {
            "params": {
                "idauto": $("#txtidauto").val()
            }
        }).then(function(respuesta) {
            $("#btnrecibir").removeAttr("disabled");
            console.log(respuesta);
            est = respuesta.data.estado;
            if (est == '1') {
                $("#modaldetalle").modal('hide');
                search();
                toastr.success("El traspaso fue aceptado satisfactoriamente", 'Mensaje del Sistema')
            }
        }).catch(function(error) {
            $("#btnrecibir").removeAttr("disabled");
            toastr.error('Error al cargar el listado ' + error, 'Mensaje del sistema')
        });
    }
</script>
<?php
$this->endSection('javascript');
?>