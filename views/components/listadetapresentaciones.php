<div class="table-responsive">
    <table id="tblpresentaciones" class="table table-bordered table-hover table-sm small">
        <thead>
            <tr>
                <th>U. M.</th>
                <th class="text-end">Cantidad</th>
                <th class="text-end">Costo</th>
                <th class="text-end">Margen</th>
                <th class="text-end">Precio</th>
                <th class="text-center">Eliminar</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($listadetapresxproducto as $p) : ?>
                <tr>
                    <td><?php echo $p['pres_desc'] ?></td>
                    <td class="text-end"><?php echo $p['epta_cant'] ?></td>
                    <td class="text-end"><?php echo $p['epta_cost'] ?></td>
                    <td class="text-end"><?php echo $p['epta_marg'] ?></td>
                    <td class="text-end"><?php echo $p['epta_prec'] ?></td>
                    <td class="text-center">
                        <button onclick="eliminardetallepres(<?php echo $p['epta_idep'] ?>)" class="btn btn-danger">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>