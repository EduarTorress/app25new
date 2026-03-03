<table id="table" data-show-export="true" class="table table-bordered table-hover table-sm small">
    <thead>
        <tr>
            <th data-sortable="true">Fecha</th>
            <th data-sortable="true">Documento</th>
            <th data-sortable="true">Cliente</th>
            <th data-sortable="true">Forma</th>
            <th>Mon.</th>
            <th class="text-end" data-sortable="true" data-footer-formatter="formatTotal">Grav.</th>
            <th class="text-end" data-sortable="true" data-footer-formatter="formatTotal">Exon.</th>
            <th class="text-end" data-sortable="true" data-footer-formatter="formatTotal">Inaf.</th>
            <th class="text-end" data-sortable="true" data-footer-formatter="formatTotal">IGV</th>
            <th class="text-end" data-sortable="true" data-footer-formatter="formatTotal">Total</th>
            <th class="text-center" data-sortable="true">Usuario</th>
            <th class="text-center" data-sortable="true">Fecha Hora</th>
            <th class="text-center">Opciones</th>
        </tr>
    </thead>
    <tbody>
        <?php $fnow = new DateTime(date('Y-m-d h:i:s a')); ?>
        <?php foreach ($listado as $item) : ?>
            <tr>
                <td><?php echo $item['fech'] ?></td>
                <td><?php echo $item['dcto'] ?></td>
                <td><?php echo $item['razo'] ?></td>
                <td>
                    <b>
                        <?php echo mostrarformapago($item['form']); ?>
                    </b>
                </td>
                <td><?php echo $item['mone'] ?></td>
                <td class="text-end"><?php echo empty($_SESSION['config']['ventasexon']) ?  evaluarvalortdoc($item['tdoc'], $item['valor']) : '0.00'; ?></td>
                <td class="text-end"><?php echo empty($_SESSION['config']['ventasexon']) ?  '0.00' : evaluarvalortdoc($item['tdoc'], $item['rcom_exon']); ?></td>
                <td class="text-end"><?php echo evaluarvalortdoc($item['tdoc'], $item['inafecto']); ?></td>
                <td class="text-end"><?php echo evaluarvalortdoc($item['tdoc'], $item['igv']); ?></td>
                <td class="text-end"><?php echo evaluarvalortdoc($item['tdoc'], $item['impo']); ?></td>
                <td class="text-center"><b><?php echo $item['usuario']; ?></b></td>
                <?php
                $fusua = new DateTime($item['fusua']);
                $ffinal = $fusua->diff($fnow);
                $tooltipfecha = 'Hace ' . $ffinal->h . ' horas con ' . $ffinal->i . ' minutos y ' . $ffinal->s . ' segundos.';
                ?>
                <td class="text-center" data-bs-toggle="tooltip" title="<?php echo $tooltipfecha; ?>"><?php echo $item['fusua']; ?>
                </td>
                <td class="text-center">
                    <a class="btn btn-primary" role="button" onclick="descargarpdf10('<?= $item['idauto'] ?>','<?= $item['tcom'] ?>','<?= pathinfo($item['nombrexml'], PATHINFO_FILENAME) . '.pdf' ?>','<?= $item['tdoc'] ?>')">
                        <i class="fas fa-print"></i>
                    </a>
                    <a class="btn btn-secondary" role="button" onclick="descargarpdfticket('<?= $item['idauto'] ?>','<?= $item['tcom'] ?>','<?= pathinfo($item['nombrexml'], PATHINFO_FILENAME) . '.pdf' ?>','<?= $item['tdoc'] ?>')">
                        <i class="fas fa-print"></i>
                    </a>
                    <a class="btn btn-info" role="button" onclick="descargarxml('<?= $item['idauto'] ?>','<?= $item['nombrexml'] ?>')">
                        <i class="fas fa-cloud-download-alt"></i>
                    </a>
                    <?php if ($item['tdoc'] != '07') : ?>
                        <?php if (floatval($item['impo']) > 0) : ?>
                            <?php if ($item['tcom'] == 'K') : ?>
                                <?php $linkventa = "/vtas/buscarventa/" . $item['idauto']; ?>
                                <a class="btn btn-success" role="button" onclick="buscarventa('<?php echo $linkventa ?>')">
                                    <i class="fas fa-eye"></i>
                                <?php else : ?>
                                    <a class="btn btn-success" role="button" onclick="" href="<?php echo "/ovtas/buscarventa/" . $item['idauto'] ?>">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                <?php endif; ?>
                            <?php endif; ?>
                        <?php endif; ?>
                        <a class="btn btn-outline-dark" role="button" onclick="enviarwhatsapp('<?= $item['idauto'] ?>','<?= $item['tcom'] ?>','<?= pathinfo($item['nombrexml'], PATHINFO_FILENAME) . '.pdf' ?>','<?= $item['tdoc'] ?>')">
                            <i class="fa fa-whatsapp"></i>
                        </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<!-- <tfoot>
        <tr>
            <th colspan="9" style="text-align:right">Total:</th>
            <th></th>
        </tr>
    </tfoot> -->
<!-- <div class="modal fade" id="modalEliminarVenta" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title fs-5" id="lblEliminarVenta">Eliminar Venta: </h4>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="col-form-label">Usuario:</label>
                    <input type="usuario" class="form-control" name="txtUsuario" id="txtUsuario">
                </div>
                <div class="mb-3">
                    <label class="col-form-label">Contraseña:</label>
                    <input type="password" class="form-control" name="txtPassword" id="txtPassword">
                </div>
                <input style="display:none" type="text" class="form-control" name="txtIdauto" id="txtIdauto">
                <div class="text-end">
                    <input type="submit" class="btn btn-warning" onclick="eliminarVenta()" value="Eliminar">
                    <input type="submit" class="btn btn-danger" value="Cancelar">
                </div>
            </div>
        </div>
    </div>
</div> -->

<script>
    $(document).ready(function() {
        reportetablebt('#table');
    });

    // var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    // var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
    //     return new bootstrap.Tooltip(tooltipTriggerEl)
    // })

    $('#table tbody').on('dblclick', 'tr ', function(e) {
        ndoc = $(this).find('td:nth-child(2)').html();
        axios.get('/vtas/consultardetalleventaxndoc', {
            "params": {
                "ndoc": ndoc
            }
        }).then(function(respuesta) {
            detalle = respuesta.data.listado;
            $("#tbldetalle tbody").empty();
            var subtotal = 0;
            var total = 0;
            detalle.forEach(function(d) {
                $("#lblmodaldetalle").text("Detalle: " + ndoc);
                subtotal = Number(d.cant) * Number(d.prec);
                var tr = `<tr> 
                        <td>` + d.descri + `</td>
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
    });

    // $('#table').on('click-row.bs.table', function(e, row, $element) {
    //     // console.log(row, $element);
    // });

    function buscarventa(link) {
        Swal.fire({
            title: "¿Desea verificar esa venta seleccionada?",
            text: "Asegurese de guardar todos los datos ingresados (detalle) en ventas directas / rapidas, ya que seran borrados para modificar la venta seleccionada ",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si'
        }).then(function(respuesta) {
            if (respuesta.isConfirmed) {
                window.location.href = link;
            }
        });
    }
</script>
<!-- <div id="resultado"></div> -->