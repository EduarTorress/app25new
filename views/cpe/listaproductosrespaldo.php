<div class="card">
    <div class="card-body">
        <table id="tabla_productos" class="table table-bordered table-hover" style="font-size:small;">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>ID</th>
                    <th>Unidad</th>
                    <th>Stock</th>
                    <th>Precio</th>
                    <th>Precio Myr.</th>
                    <th>Agregar</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($lista['lista']['items'] as $item) : ?>
                    <tr>
                        <td><?php echo $item['descri'] ?></td>
                        <td><?php echo $item['idart'] ?></td>
                        <td><?php echo $item['unid'] ?></td>
                        <td><?php echo $item['uno'] ?></td>
                        <td><?php echo $item['pre1'] ?></td>
                        <td><?php echo $item['pre2'] ?></td>
                        <td>
                            <?php
                            $parametro1 = $item['descri'];
                            $parametro2 = $item['idart'];
                            $parametro3 = $item['unid'];
                            $parametro4 = $item['uno'];
                            $parametro5 = $item['pre1'];
                            $parametro6 = $item['pre2'];
                            $parametros = compact('parametro1', 'parametro2', 'parametro3', 'parametro4', 'parametro5', 'parametro6');
                            $cadena_json = json_encode($parametros);
                            ?>
                            <button class="btn btn-success" data-target="#agregar_cantidad" onclick='agregar_producto(<?php echo $cadena_json ?>)'><i href="" style="color:white;" class="fas fa-plus-circle"></i></button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<script>
    $('#tabla_productos').DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
        "columnDefs": [{
            targets: 3,
            orderable: false,
            searchable: false
        }]
    });
</script>