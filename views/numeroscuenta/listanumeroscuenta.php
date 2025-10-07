<table id="tabla_numeroscuenta" class="table table-bordered table-hover table-sm small">
    <thead>
        <tr>
            <th>N° Cuenta</th>
            <th>Banco</th>
            <th>Moneda</th>
            <th class="text-center">Seleccionar</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($lista as $item) : ?>
            <tr>
                <td><?php echo $item['ctas_ctas'] ?></td>
                <td><?php echo $item['banc_nomb'] ?></td>
                <td><?php echo $item['ctas_mone'] ?></td>
                <td class="text-center" id="iniciar">
                    <?php
                    $parametro1 = $item['ctas_idct'];
                    ?>
                    <button onclick='modalEdit(<?php echo $parametro1 ?>)' class="btn btn-warning">Editar</button>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<script>
      reportetablebt("#tabla_numeroscuenta");
</script>