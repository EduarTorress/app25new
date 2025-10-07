<?php

use App\View\Components\DocumentoComponent;
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
                                <br>
                                <label class="my-1 mr-2" for="txtfechai">Fecha:</label>
                                <input type="date" class="form-control form-control-sm" value="<?php echo date('Y-m-d'); ?>" id="txtfecha" name="txtfecha"> &nbsp;
                                <?php
                                $ec = new EmpresaComponent('');
                                echo $ec->render();
                                ?>
                                &nbsp;&nbsp;
                                <label class="my-1 mr-2" for="">Forma/Pago:</label>
                                <select name="select" class="form-control form-control-sm" id="cmbForma">
                                    <option value="0" selected>Todas</option>
                                    <option value="E">Efectivo</option>
                                    <option value="C" selected>Crédito</option>
                                    <option value="D">Deposito</option>
                                    <option value="T">Tarjeta</option>
                                    <option value="Y">YAPE</option>
                                    <option value="P">PLIN</option>
                                </select>
                                <button type="submit" id="btnbuscar" class="btn btn-primary my-1">Consultar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12" id="resultado">
            </div>
        </div>
    </div>
</div>
<div id="modaldetalle" class="modal fade " tabindex="-1" data-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="lblmodaldetalle">Detalle del comprobante</h5>
            </div>
            <div class="modal-body">
                <table class="table table-bordered" id="tbldetalle">
                    <thead>
                        <tr>
                            <th scope="col">Producto</th>
                            <th scope="col">Unidad</th>
                            <th scope="col">Cantidad</th>
                            <th scope="col">Precio</th>
                            <th scope="col">Sub. Total</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <div class="float-right">
                    <div class="input-group mb-3">
                        <span class="input-group-text form-control-sm" id=""><b>Total:</b> </span>
                        <input type="text" id="txtimportemodal" class=" form-control" value="0.00" readonly>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnEliminar" class="btn btn-danger" onclick="cerrarModal();" data-bs-dismiss="modal">Cerrar</button>
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
<style>
    div.dataTables_info {
        color: black !important;
    }
</style>
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

    function search() {
        var txtfecha = document.getElementById("txtfecha").value;
        cmbForma = $("#cmbForma").val();
        cmbalmacen = $("#cmbAlmacen").val();
        $("#btnbuscar").attr('disabled', true);
        axios.get('/cobranzas/listarcobranzastodo', {
            "params": {
                "cmbformapago": cmbForma,
                "txtfecha": txtfecha,
                "cmbalmacen": cmbalmacen
            }
        }).then(function(respuesta) {
            // const contenido_tabla = respuesta.data;
            // $('#search').html(contenido_tabla);
            listado = respuesta.data.listado;
            detalletabla = [
                ['Fecha', 'fech',
                    [new Map([
                        ['class', ''],
                        ['width', ''],
                        ['id', ''],
                        ['attr', 'data-sortable="true"'],
                    ])],
                    [new Map([
                        ['class', ''],
                        ['width', ''],
                        ['id', ''],
                        ['attr', ''],
                        ['type', 'fech']
                    ])],
                ],
                ['Documento', 'ndoc',
                    [new Map([
                        ['class', 'ndoc'],
                        ['width', ''],
                        ['id', ''],
                        ['attr', ''],
                    ])],
                    [new Map([
                        ['class', ''],
                        ['width', ''],
                        ['id', ''],
                        ['attr', ''],
                        ['type', 'text']
                    ])],
                ],
                ['Cliente', 'proveedor',
                    [new Map([
                        ['class', ''],
                        ['width', ''],
                        ['id', ''],
                        ['attr', ''],
                    ])],
                    [new Map([
                        ['class', ''],
                        ['width', ''],
                        ['id', ''],
                        ['attr', ''],
                        ['type', 'text']
                    ])],
                ],
                ['Mon.', 'mone',
                    [new Map([
                        ['class', ''],
                        ['width', ''],
                        ['id', ''],
                        ['attr', ''],
                    ])],
                    [new Map([
                        ['class', ''],
                        ['width', ''],
                        ['id', ''],
                        ['attr', ''],
                        ['type', 'text']
                    ])],
                ],
                ['Tienda', 'Tienda',
                    [new Map([
                        ['class', ''],
                        ['width', ''],
                        ['id', ''],
                        ['attr', '']
                    ])],
                    [new Map([
                        ['class', ''],
                        ['width', ''],
                        ['id', ''],
                        ['attr', ''],
                        ['type', 'text']
                    ])],
                ],
                ['Total', 'tsoles',
                    [new Map([
                        ['class', 'text-end'],
                        ['width', ''],
                        ['id', ''],
                        ['attr', 'data-footer-formatter="formatTotal"'],
                    ])],
                    [new Map([
                        ['class', 'text-end'],
                        ['width', ''],
                        ['id', ''],
                        ['attr', 'data-footer-formatter="formatTotal"'],
                        ['type', 'number']
                    ])],
                ],
                ['', 'buttons',
                    [new Map([
                        ['class', ''],
                        ['text', ''],
                        ['id', ''],
                        ['attr', ''],
                    ])],
                    [
                        [new Map([
                            ['class', 'btn btn-success'],
                            ['onclick', 'consultardetalle'],
                            ['text', 'Ver'],
                            ['id', ''],
                            ['attr', ''],
                        ])],
                        [new Map([
                            ['class', 'btn btn-primary '],
                            ['onclick', 'descargarpdf10'],
                            ['text', 'TICKET'],
                            ['id', ''],
                            ['attr', ''],
                        ])],
                        [new Map([
                            ['class', 'btn btn-danger '],
                            ['onclick', 'descargarpdf'],
                            ['text', 'PDF A4'],
                            ['id', ''],
                            ['attr', ''],
                        ])]
                    ],
                ]
            ]
            cargartabla(listado, "table", detalletabla);
            reportetablebt('#table');
            $("#btnbuscar").attr('disabled', false);
        }).catch(function(error) {
            toastr.error('Error al cargar el listado' + error, 'Mensaje del sistema')
            $("#btnbuscar").attr('disabled', false);
        });
    }

    function descargarpdf10(nidauto, e) {
        nombrepdf = $(e).parent().parent().find(".ndoc").html();
        nombrepdf = nombrepdf + ".pdf";
        tipo = 'K';
        tdoc = '01';
        var params = "nidauto=" + nidauto + '&tipo=' + tipo + '&nombrepdf=' + nombrepdf + '&tdoc=' + tdoc;
        var xhr = new XMLHttpRequest();
        var cruta = '/cpe/descargarpdfticket';
        xhr.open('GET', cruta + "?" + params, true);
        xhr.responseType = 'blob';
        xhr.onload = function(e) {
            if (this.status == 200) {
                var blob = new Blob([this.response]);
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = nombrepdf;
                link.click();
            }
        };
        xhr.send();
    }

     function descargarpdf(nidauto, e) {
        nombrepdf = $(e).parent().parent().find(".ndoc").html();
        nombrepdf = nombrepdf + ".pdf";
        tipo = 'K';
        tdoc = '01';
        var params = "nidauto=" + nidauto + '&tipo=' + tipo + '&nombrepdf=' + nombrepdf + '&tdoc=' + tdoc;
        var xhr = new XMLHttpRequest();
        var cruta = '/cpe/descargarpdf';
        xhr.open('GET', cruta + "?" + params, true);
        xhr.responseType = 'blob';
        xhr.onload = function(e) {
            if (this.status == 200) {
                var blob = new Blob([this.response]);
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = nombrepdf;
                link.click();
            }
        };
        xhr.send();
    }

    function consultardetalle(idauto) {
        axios.get('/cobranzas/consultardetalleventa', {
            "params": {
                "idauto": idauto
            }
        }).then(function(respuesta) {
            detalle = respuesta.data.listado;
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
            $("#txtimportemodal").val("S/ " + total)
            $("#modaldetalle").modal('show');
        }).catch(function(error) {
            toastr.error('Error al cargar el listado' + error, 'Mensaje del sistema')
        });
    }
</script>
<?php
$this->endSection('javascript');
?>