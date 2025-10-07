<!-- <table id="table" class="table table-bordered table-hover table-sm small">
    <thead>
        <tr>
            <th style="width:8%;">Fecha</th>
            <th style="width:4%;">Tipo</th>
            <th style="width:5%;">Serie</th>
            <th style="width:7%;">Documento</th>
            <th style="width:7%;">RUC/DNI</th>
            <th style="width:10%;">Cliente</th>
            <th style="width:4%;" data-footer-formatter="formatTotal">Grav.</th>
            <th style="width:4%;" data-footer-formatter="formatTotal">Exon.</th>
            <th style="width:4%;" data-footer-formatter="formatTotal">Inaf.</th>
            <th style="width:6%;" data-footer-formatter="formatTotal">IGV</th>
            <th style="width:6%;" data-footer-formatter="formatTotal">Total</th>
            <th style="width:8%;">Rpta SUNAT</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($listado as $item) : ?>
            <tr>
                <td><?php echo $item['fech'] ?></td>
                <td><?php echo $item['tdoc'] ?></td>
                <td><?php echo $item['serie'] ?></td>
                <td><?php echo $item['ndoc'] ?></td>
                <td><?php echo ($item['tdoc'] == '03') ? $item['ndni'] : $item['nruc'] ?></td>
                <td><?php echo $item['razo'] ?></td>
                <td class="text-right"><?php echo number_format($item['valor'], 2, '.', '') ?></td>
                <td class="text-right"><?php echo number_format($item['exon'], 2, '.', '') ?></td>
                <td class="text-right"><?php echo number_format($item['inafecto'], 2, '.', '') ?></td>
                <td class="text-right"><?php echo number_format($item['igv'], 2, '.', '') ?></td>
                <td class="text-right"><?php echo number_format($item['importe'], 2, '.', '') ?></td>
                <td><?php echo $item['mensaje'] ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<script>
    reportetablebt("#table");
</script> -->