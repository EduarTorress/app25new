<?php

use App\View\Components\ModalImprimir;

$this->setLayout('layouts/admin');
?>
<?php
$this->startSection('contenido');
?>
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="card card-primary card-outline">
                <div class="card-body">
                    <div class="input-group">
                        <label for="txtfecha" class="col-sm-0.5 col-form-label col-form-label-sm">Fecha: </label>
                        <div class="col-sm-2">
                            <input type="date" class="form-control form-control-sm" id="txtfecha" value="<?php echo date("Y-m-d") ?>">
                        </div>
                        <?php
                        $lu = new \App\View\Components\ListasusuarioscomboComponent('');
                        echo $lu->render();
                        ?>
                        <div class="col-sm-1">
                            <button type="button" class="btn btn-success btn-sm" onclick="buscar()">Consultar</button>
                        </div>
                        <label for="" class="">Ingresos: </label>
                        <div class="col-sm-1">
                            <input type="text" class="form-control form-control-sm" id="txtingresos" value="" readonly>
                        </div>
                        <label for="" class="">Egresos: </label>
                        <div class="col-sm-1">
                            <input type="text" class="form-control form-control-sm" id="txtegresos" value="" readonly>
                        </div>
                        <label for="" class="">Saldo Anterior: </label>
                        <div class="col-sm-1">
                            <input type="text" class="form-control form-control-sm" id="txtsaldoanterior" value="" readonly>
                        </div>
                    </div>
                </div>
            </div>
            <div id="resultado"></div>
        </div>
    </div>
</div>
<!-- 
<div class="modal fade" id="modalversaldo" role="dialog" data-keyboard="true" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5>Saldos</h5>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label" for="">Saldo Anterior:</label>
                    <div class="col-sm-8">
                        <div class="input-group-append">
                            <input type="text" class="form-control form-control" name="txtsaldoanterior" id="txtsaldoanterior" readonly>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label" for="">Ingresos:</label>
                    <div class="col-sm-8">
                        <div class="input-group-append">
                            <input type="text" class="form-control form-control" name="txtingresos" id="txtingresos" readonly>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label" for="">Egresos:</label>
                    <div class="col-sm-8">
                        <div class="input-group-append">
                            <input type="text" class="form-control form-control" name="txtegresos" id="txtegresos" readonly>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> -->
</div>
<?php
$oimp = new ModalImprimir();
echo $oimp->render();
?>
<?php
$this->endSection('contenido');
?>
<?php
$this->startSection('javascript');
?>
<script>
    window.onload = function() {
        titulo("<?php echo $titulo ?>");
        $('#griddetalle').DataTable({
            "responsive": true,
            "autoWidth": true,
            "searching": true,
            "paging": false
        });
        tipousuario = "<?php echo $_SESSION['tipousuario'] ?>";
        if (tipousuario != 'A') {
            $("#cmbusuarios").attr("disabled", "disabled");
            $("#cmbusuarios").val("<?php echo $_SESSION['usuario_id']; ?>");
        }
    }

    function buscar() {
        axios.get('/cajas/buscar', {
            "params": {
                "txtfech": $("#txtfecha").val(),
                "cmbusuarios": $("#cmbusuarios").val()
            }
        }).then(function(respuesta) {
            const contenido_tabla = respuesta.data;
            $('#resultado').html(contenido_tabla);
        }).catch(function(error) {
            toastr.error('Error al cargar el listado' + error, 'Mensaje del sistema')
        });
    }

    function enviarcorreo() {
        // data = new FormData();
        // data.append("idcliev", $("#txtidcliente").val());
        // axios.post("/cajas/enviarresumenxcorreo", data)
        //     .then(function(respuesta) {
        //         toastr.success("Se envió el correo satisfactoriamente");
        //     }).catch(function(error) {
        //         console.log(error)
        //     });
        idusuario = "<?php empty($_SESSION['usuario']) ? '0' : '1' ?>";
        if (idusuario == '0') {
            toastr.error("Sesión vencida, por favor ingrese de nuevo al sistema", 'Mensaje del Sistema');
            return;
        }
        txtfecha = $("#txtfecha").val();
        var cruta = '/cajas/enviarresumenxcorreo/?fecha=' + txtfecha;
        var xhr = new XMLHttpRequest();
        xhr.open('GET', cruta, true);
        xhr.responseType = 'blob';
        xhr.onload = function(e) {
            Swal.fire({
                title: "Se envío satisfactoriamente",
                text: "La liquidación de caja fue enviada correctamente",
                icon: "success"
            });
        }
        xhr.send();
    }

    function generarticketcaja(listamovimientos) {
        yape = 0;
        efectivo = 0;
        plin = 0;
        tarjeta = 0;
        credito = 0;
        deposito = 0;
        fecha = "";
        usuario = "";
        egresos = 0;
        i = 0;
        for (l of listamovimientos) {
            if (i == 0) {
                fecha = l.fechao;
                usuario = l.usua;
            }
            yape += Number(l.yape);
            efectivo += Number(l.efectivo);
            plin += Number(l.plin);
            tarjeta += Number(l.tarjeta);
            deposito += Number(l.deposito);
            credito += Number(l.credito);
            egresos += Number(l.egresos);
            i += 1;
        }
        axios.get('/cajas/generarticketcaja', {
            "params": {
                "yape": yape,
                "efectivo": efectivo,
                "plin": plin,
                "tarjeta": tarjeta,
                "deposito": deposito,
                "credito": credito,
                'fecha': fecha,
                'usuario': usuario,
                'egresos': egresos,
                "txtreferencia": $("#txtreferencia").val(),
                "sobrante": $("#txtsobrante").val(),
                'nidusua': $("#cmbusuarios").val()
            }
        }).then(function(respuesta) {
            // var fileLink = document.createElement('a');
            // fileLink.href = 'ticketcaja.pdf';
            // fileLink.setAttribute('download', 'ticketcaja.pdf');
            // document.body.appendChild(fileLink);
            // fileLink.click();
            var w = screen.width;
            url = "ticketcaja.pdf"
            // console.log(w)
            if (w <= 768) {
                var req = new XMLHttpRequest();
                req.open("GET", url, true);
                req.responseType = "blob";
                req.onload = function(event) {
                    var blob = req.response;
                    // console.log(blob.size);
                    var link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = respuesta.data.ndoc + ".pdf"
                    link.click();
                };
                req.send();
            } else {
                $("#pdfguia").attr("src", url)
                $("#abrirguia").click();
            }
        }).catch(function(error) {
            toastr.error('Error al generar ticket' + error, 'Mensaje del sistema')
        });
    }
</script>
<?php
$this->endSection("javascript");
?>