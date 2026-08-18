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
                <td><b><?php echo $item['dcto'] ?></b></td>
                <td><?php echo $item['razo'] ?></td>
                <td><?php echo $item['ndo2'] ?></td>
                <td>
                    <b> <?php echo mostrarformapago($item['form']); ?></b>
                </td>
                <td><?php echo $item['mone'] == 'S' ? 'SOLES' : 'DÓLARES' ?></td>
                <td><b><?php echo $item['usuario'] ?></b></td>
                <td><?php echo $item['fusua'] ?></td>
                <td style="text-align: right;"><?php echo ($item['tdoc'] != '07' ?  number_format($item['impo'], 2, '.', '')  : '-' . number_format($item['impo'], 2, '.', '')) ?></td>
                <td class="small" style="text-align: center;">
                    <?php if ($item['tdoc'] != '07') : ?>
                        <?php if ($item['tcom'] == '1') : ?>
                            <a class="btn btn-success" role="button" onclick="limpiarsesion();" href="<?php echo "/compras/buscarcompra/" . $item['idauto'] ?>">
                                <i class="fas fa-eye "></i>
                            </a>
                        <?php else : ?>
                            <a class="btn btn-info" role="button" onclick="" href="<?php echo "/ocompras/buscarcompra/" . $item['idauto'] ?>">
                                <i class="fas fa-eye "></i>
                            </a>
                        <?php endif; ?>
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