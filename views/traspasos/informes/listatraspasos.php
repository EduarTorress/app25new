<table id="tablaGuias" class="table table-bordered table-hover table-sm small">
    <thead>
        <tr>
            <th data-sortable="true">Nro.Guia</th>
            <th data-sortable="true">Fecha</th>
            <th data-sortable="true">Fecha Tras.</th>
            <th>Punto de Partida</th>
            <th>Punto de Llegada</th>
            <th class="text-center">Sucursal Destino</th>
            <th class="text-center" data-sortable="true">Estado</th>
            <th class="text-center">Usuario</th>
            <th class="text-center" data-sortable="true">Fecha / Hora</th>
            <th class="text-center">Opciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($listado as $item) : ?>
            <tr>
                <td><?php echo $item['guia_ndoc'] ?></td>
                <td><?php echo $item['guia_fech'] ?></td>
                <td><?php echo $item['guia_fect'] ?></td>
                <td><?php echo trim($item['guia_ptop']) ?></td>
                <td><?php echo trim($item['guia_ptoll']) ?></td>
                <td class="text-center"><?php echo trim($item['destino']) ?> </td>
                <td class="text-center">
                    <b>
                        <?php
                        if ($item['rcom_reci'] == 'P') {
                            echo 'Pendiente';
                        } else {
                            echo 'Entregado';
                        }
                        ?>
                    </b>
                </td>
                <td><?php echo $item['usuario'] ?></td>
                <td><?php echo $item['fusua'] ?></td>
                <td class="text-center">
                    <a class="btn btn-primary " role="button" onclick="descargarpdf('<?= $item['guia_idgui'] ?>','<?= $item['guia_ndoc'] . '.pdf' ?>')">
                        <i class="fas fa-print"></i>
                    </a>
                    <!-- <a class="btn btn-success" role="button" onclick="" href="<?php echo 'traspasos/buscarxid/' . $item['guia_idgui'] ?>">
                        <i class="fas fa-eye"></i>
                    </a> -->
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<script>
    reportetablebt("#tablaGuias");
</script>