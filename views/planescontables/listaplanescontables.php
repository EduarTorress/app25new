<table id="tablaplanescontables" class="table table-bordered table-hover table-sm small">
    <thead>
        <tr>
            <th class="text-center">ID</th>
            <th>Número</th>
            <th>Descripción</th>
            <th>Destino Debe</th>
            <th>Destino Haber</th>
            <th>N° SUNAT</th>
            <th class="text-center">Seleccionar</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($lista as $item) : ?>
            <tr>
                <td class="text-center"><?php echo $item['idcta'] ?></td>
                <td><?php echo $item['ncta'] ?></td>
                <td><?php echo strtoupper($item['nomb']) ?></td>
                <td><?php echo $item['cdestinod'] ?></td>
                <td><?php echo $item['cdestinoh'] ?></td>
                <td><?php echo $item['ctasunat'] ?></td>
                <td class="text-center" id="iniciar">
                    <?php
                    $parametro1 = $item['idcta'];
                    ?>
                    <button onclick='modalEdit(<?php echo $parametro1 ?>)' class="btn btn-warning">Editar</button>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<script>
      reportetablebt("#tablaplanescontables");
</script>