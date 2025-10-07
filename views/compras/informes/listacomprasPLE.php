<table id="tablacompras" class="table table-bordered table-hover table table-sm small">
    <thead>
        <tr class="text-center">
            <th>ID</th>
            <th>Tipo D.</th>
            <th>Documento</th>
            <th>Guía</th>
            <th>Fecha</th>
            <th>Fecha R.</th>
            <th>Proveedor</th>
            <th>Forma</th>
            <th>Moneda</th>
            <th style="text-align: right;"  data-footer-formatter="formatTotal">Valor</th>
            <th style="text-align: right;" data-footer-formatter="formatTotal">IGV</th>
            <th style="text-align: right;" data-footer-formatter="formatTotal">Importe</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($listado as $item) : ?>
            <tr>
                <td><?php echo $item['auto'] ?></td>
                <td><?php echo $item['tdoc'] ?></td>
                <td><?php echo $item['dcto'] ?></td>
                <td><?php echo $item['ndo2'] ?></td>
                <td><?php echo $item['fech'] ?></td>
                <td><?php echo $item['fecr'] ?></td>
                <td><?php echo $item['razo'] ?></td>
                <td><?php echo $item['form'] == 'C' ? 'CRÉDITO' : 'EFECTIVO' ?></td>
                <td><?php echo $item['mone'] == 'S' ? 'SOLES' : 'DÓLARES' ?></td>
                <td style="text-align: right;"><?php echo $item['valor'] ?></td>
                <td style="text-align: right;"><?php echo $item['igv'] ?></td>
                <td style="text-align: right;"><?php echo number_format($item['impo'], 2, '.', '') ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
    <!-- <tfoot>
        <tr>
            <th colspan="5" style="text-align:right">Total:</th>
            <th class="text-right"></th>
        </tr>
    </tfoot> -->
</table>
<script>
    // $('#tablacompras').DataTable({
    //     "paging": true,
    //     "lengthChange": false,
    //     "searching": true,
    //     "ordering": true,
    //     "info": true,
    //     "autoWidth": false,
    //     "responsive": true,
    //     "columnDefs": [{
    //         targets: 3,
    //         orderable: false,
    //         searchable: false
    //     }],
    // });
    reportetablebt("#tablacompras");

    function limpiarsesion() {
        localStorage.clear();
    }
</script>