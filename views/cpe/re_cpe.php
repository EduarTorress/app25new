<?php
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
                                <input type="checkbox" class="form-check-input" id="chkfechas" name="chkfechas">
                                <label class="my-1 mr-2" for="txtfechai">Inicio:</label>
                                <input type="date" class="form-control form-control-sm" id="txtfechai" name="txtfechai"> &nbsp;
                                <label class="my-1 mr-2" for="txtfechai">Hasta:</label>
                                <input type="date" class="form-control form-control-sm" id="txtfechaf" name="txtfechaf"> &nbsp;
                                <label class="my-1 mr-2" for="">Dcto:</label>
                                <select name="select" class="form-control form-control-sm" id="cmbForma">
                                    <option value="TT" selected>Todas</option>
                                    <option value="01">Factura</option>
                                    <option value="03">Boleta</option>
                                    <option value="07">Nota de Crédito</option>
                                    <option value="08">Nota Debito</option>
                                    <option value="09">Guias Remitente</option>
                                </select>
                                <button type="submit" class="btn btn-primary my-1">Consultar</button>
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
    window.onload = function() {
        titulo("<?php echo $titulo ?>");
        obtenerFechas();
    }

    document.getElementById('form-search').addEventListener('submit', function(evento) {
        evento.preventDefault();
        search();
    });

    function search() {
        let cforma = $('#cmbForma').val();
        let datos = {};
        if (document.getElementById('chkfechas').checked) {
            var fechai = document.getElementById('txtfechai').value;
            var fechaf = document.getElementById('txtfechaf').value;
            if (fechai == '' || fechaf == '') {
                toastr.error('Debe seleccionar un rango de fechas', 'Mensaje del Sistema');
                return;
            }
            datos = {
                chkfechas: 1,
                fechai: fechai,
                fechaf: fechaf,
                cforma: cforma
            }

        } else {
            datos = {
                chkfechas: 0,
                cforma: cforma
            }
        }
        axios.get('/cpe/lista', {
            "params": {
                "datos": JSON.stringify(datos)
            }
        }).then(function(respuesta) {
            // 100, 200, 300
            const contenido_tabla = respuesta.data;
            $('#search').html(contenido_tabla);
            // console.log(respuesta.data.message)
        }).catch(function(error) {
            // 400, 500
            toastr.error('Error al cargar el listado', 'Mensaje del Sistema')
        });
    }

    function enviarASUNAT(idauto, tdoc, tcom) {
        axios.get('/cpe/enviardctosunat', {
            "params": {
                "idauto": idauto,
                "tdoc": tdoc,
                "tcom": tcom
            }
        }).then(function(respuesta) {
            // 100, 200, 300
            //  console.log(respuesta.data.rpta);
            toastr.success(respuesta.data.rpta, 'Mensaje del Sistema');
            search();
            // console.log(respuesta.data.message)
        }).catch(function(error) {
            // 400, 500
            toastr.error('Error al enviar el documento a SUNAT', 'Mensaje del Sistema')
        });
    }

    function consultarcdr(idauto, tdoc, ndoc) {
        axios.get('/cpe/consultarcdr', {
            "params": {
                "idauto": idauto,
                "tdoc": tdoc,
                "ndoc": ndoc
            }
        }).then(function(respuesta) {
            // 100, 200, 300
            console.log(respuesta.data);
            toastr.success(respuesta.data.mensaje, 'Mensaje del Sistema');
            search();
            // console.log(respuesta.data.message)
        }).catch(function(error) {
            // 400, 500
            toastr.error('Error al enviar el documento a SUNAT', 'Mensaje del Sistema')
        });
    }
</script>
<?php
$this->endSection('javascript');
?>