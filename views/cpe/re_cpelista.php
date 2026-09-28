<div class="card">
    <div class="card-body">
        <table id="tablavtasxenviar" class="table table-bordered table-hover table-sm small">
            <thead>
                <tr>
                    <th>Número</th>
                    <th>Fecha</th>
                    <th>Cliente</th>
                    <th>Moneda</th>
                    <th class="text-center">Gravado</th>
                    <th class="text-center">Exonerado</th>
                    <th class="text-center">IGV</th>
                    <th class="text-center">Total</th>
                    <th class="text-center">Opción</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($listado as $item) : ?>
                    <tr>
                        <td><?php echo $item['ndoc'] ?></td>
                        <td><?php echo $item['fech'] ?></td>
                        <td><?php echo trim($item['razo']) ?></td>
                        <td><?php echo ($item['mone'] == 'S' ? 'PEN' : 'DÓLARES'); ?></td>
                        <td class="text-right"><?php echo number_format($item['valor'], 2, '.', ',') ?></td>
                        <td class="text-right"><?php echo '0.00' ?></td>
                        <td class="text-right"><?php echo number_format($item['igv'], 2, '.', ',') ?></td>
                        <td class="text-right"><?php echo number_format($item['impo'], 2, '.', ',') ?></td>
                        <td class="text-center">
                            <a href="javascript:void(0);" class="btn btn-sm btn-success" onclick=enviarASUNAT(<?php echo $item['idauto'] . ',"' . trim($item['tdoc']) . '","' . $item['tcom'] . '"'; ?>) title="Enviar a SUNAT"><i class="fas fa-paper-plane"></i></a>
                            <a href="javascript:void(0);" class="btn btn-sm btn-success" onclick=consultarcdr(<?php echo $item['idauto'] . ',"' . trim($item['tdoc']) . '","' . $item['ndoc'] . '"'; ?>) title="Descargar CDR"><i class="fa fa-arrow-circle-o-down"></i></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<script>
    // $('#example2').DataTable({
    //     "paging": true,
    //     "lengthChange": false,
    //     "searching": false,
    //     "ordering": true,
    //     "info": true,
    //     "autoWidth": false,
    //     "responsive": true,
    //     "columnDefs": [{
    //         targets: 8,
    //         orderable: false,
    //         searchable: false
    //     }]
    // });
    reportetablebt("#tablavtasxenviar");
</script>