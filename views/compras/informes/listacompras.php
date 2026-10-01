<table id="tablacompras" class="table table-bordered table-hover table table-sm small">
    <thead>
        <tr>
            <th>Fecha</th>
            <th>Documento</th>
            <th>Proveedor</th>
            <th>Guía de Remisión</th>
            <th>Forma</th>
            <th>Moneda</th>
            <th style="text-align: center;" class="text-center">Usuario</th>
            <th class="text-center">Fecha / Hora</th>
            <th style="text-align: right;" data-footer-formatter="formatTotal" class="text-end" data-sortable="true">Importe</th>
            <th class="text-center">Opciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($listado as $item) : ?>
            <tr>
                <td><?php echo $item['fech'] ?></td>
                <td>
                    <b><?php echo $item['dcto'] ?></b>
                </td>
                <td><?php echo $item['razo'] ?></td>
                <td><?php echo $item['ndo2'] ?></td>
                <td>
                    <b> <?php echo mostrarformapago($item['form']); ?></b>
                </td>
                <td><?php echo $item['mone'] == 'S' ? 'SOLES' : 'DÓLARES' ?></td>
                <td><b><?php echo $item['usuario'] ?></b></td>
                <td><?php echo $item['fusua'] ?></td>
                <td style="text-align: right;"><?php echo (number_format($item['impo'], 2, '.', '')) ?></td>
                <td class="small" style="text-align: center;">
                    <?php if (($item['tdoc'] != '07') && floatval($item['impo']) > 0) : ?>
                        <?php if ($item['tcom'] == '1') : ?>
                            <a class="btn btn-success" role="button" title="Modificar Compra" onclick="limpiarsesion();" href="<?php echo "/compras/buscarcompra/" . $item['idauto'] ?>">
                                <i class="fas fa-eye"></i>
                            </a>
                        <?php else : ?>
                            <a class="btn btn-info" role="button" title="Modificar Compra" onclick="" href="<?php echo "/ocompras/buscarcompra/" . $item['idauto'] ?>">
                                <i class="fas fa-eye"></i>
                            </a>
                        <?php endif; ?>
                    <?php endif; ?>
                    <?php if ($item['tdoc'] == '09' && floatval($item['impo']) > 0): ?>
                        <a class="btn btn-primary" role="button" title="Canjear guia por factura" onclick="limpiarsesion();" href="<?php echo "/compras/documentoguiaparacanje/" . $item['idauto'] ?>">
                            <i class="fa fa-retweet"></i>
                        </a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<script>
    reportetablebt("#tablacompras");

    function limpiarsesion() {
        localStorage.clear();
    }
</script>