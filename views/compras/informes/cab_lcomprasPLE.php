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
                            <!-- <form class="form-inline" id="form-search">
                                <label class="my-1 mr-2" for="txtfechai">Inicio</label>
                                <input type="date" class="form-control form-control-sm" id="txtfechai" name="txtfechai">
                                <label class="my-1 mr-2" for="txtfechai">Hasta</label>
                                <input type="date" class="form-control form-control-sm" id="txtfechaf" name="txtfechaf">
                                <button class="btn btn-primary my-1">Consultar</button>
                            </form> -->
                            <form class="form-inline" id="form-search"><br>
                                <!-- <label class="my-1 mr-2" for="txtfechai">Inicio</label>
                                <input type="date" class="form-control form-control-sm" id="txtfechai" name="txtfechai">
                                <input type="date" class="form-control form-control-sm" id="txtfechaf" name="txtfechaf"> -->
                                <label class="my-1 mr-2" for="">Mes</label>
                                <select class="form-control form-control-sm" name="cmbmes" id="cmbmes">
                                    <option value="01">Enero</option>
                                    <option value="02">Febrero</option>
                                    <option value="03">Marzo</option>
                                    <option value="04">Abril</option>
                                    <option value="05">Mayo</option>
                                    <option value="06">Junio</option>
                                    <option value="07">Julio</option>
                                    <option value="08">Agosto</option>
                                    <option value="09">Septiembre</option>
                                    <option value="10">Octubre</option>
                                    <option value="11">Noviembre</option>
                                    <option value="12">Diciembre</option>
                                </select>
                                <label class="my-1 mr-2" for="">Año</label>
                                <select class="form-control form-control-sm" name="cmbano" id="cmbano">
                                    <option value="2025">2025</option>
                                    <option value="2026">2026</option>
                                    <option value="2027">2027</option>
                                    <option value="2028">2028</option>
                                    <option value="2029">2029</option>
                                    <option value="2030">2030</option>
                                    <option value="2031">2031</option>
                                </select>
                                <button type="submit" class="btn btn-primary my-1" id="btnconsultar">Consultar</button>
                                <button type="button" class="btn btn-success my-1" onclick="exportarsire();" id="btndescargarsire">Exportar SIRE</button>
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
        titulo('<?php echo $titulo ?>');
        mes = "<?php echo date('m'); ?>";
        year = "<?php echo date('Y'); ?>";
        $("#cmbmes").val(mes);
        $("#cmbano").val(year);
    }

    // function search() {
    //     var dfechai = document.getElementById("txtfechai").value;
    //     var dfechaf = document.getElementById("txtfechaf").value;
    //     axios.get('/compras/listadoPLE', {
    //         "params": {
    //             "dfechai": dfechai,
    //             "dfechaf": dfechaf
    //         }
    //     }).then(function(respuesta) {
    //         // 100, 200, 300
    //         const contenido_tabla = respuesta.data;
    //         $('#search').html(contenido_tabla);
    //         $('input[type=search]').css('color', 'black');
    //         $('.paginate_button').css('background-color', '#006CA7');
    //         $('.previous').removeClass('disabled');
    //         // console.log(respuesta.data.message)
    //     }).catch(function(error) {
    //         // 400, 500
    //         toastr.error('Error al cargar el listado')
    //     });
    // }

    function search() {
        var mes = document.getElementById("cmbmes").value;
        var ano = document.getElementById("cmbano").value;
        $("#btnconsultar").attr('disabled', true);
        axios.get('/compras/listadoPLE', {
            "params": {
                "mes": mes,
                "ano": ano
            }
        }).then(function(respuesta) {
            const contenido_tabla = respuesta.data;
            $('#search').html(contenido_tabla);
            $("#btnconsultar").attr('disabled', false);
        }).catch(function(error) {
            $("#btnconsultar").attr('disabled', false);
            toastr.error('Error al cargar el listado' + error, 'Mensaje del sistema')
        });
    }

    function exportarsire() {
        nruc = "<?php echo $_SESSION['gene_nruc'] ?>";
        namefile = "LE" + nruc + $("#cmbano").val() + $("#cmbmes").val() + '00080400021112.zip';
        axios.get('/compras/exportarsire', {
            "params": {
                "mes": $("#cmbmes").val(),
                "namemes": $('#cmbmes').find(":selected").text(),
                "ano": $("#cmbano").val()
            }
        }).then(function(respuesta) {
            // var fileURL = window.URL.createObjectURL();
            var fileLink = document.createElement('a');
            fileLink.href = namefile;
            fileLink.setAttribute('download', namefile);
            document.body.appendChild(fileLink);
            fileLink.click();
        }).catch(function(error) {
            console.log(error);
            toastr.error("Error al exportar" + error, 'Mensaje del sistema');
        });
    }
</script>
<?php
$this->endSection('javascript');
?>