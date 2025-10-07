<div class="modal fade" id="modallotesfvto" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Lotes / Fechas Vencimiento</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="card-body">
                    <table id="tabla_lotesfvto" class="table table-bordered table-hover table-sm small">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Lote</th>
                                <th>Fecha Vencimiento</th>
                                <th class="text-center">Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($lista as $item) : ?>
                                <tr>
                                    <td><?php echo $item['fech_idka'] ?></td>
                                    <td><?php echo $item['kar_lote'] ?></td>
                                    <td><?php echo $item['kar_fvto'] ?></td>
                                    <td class="text-center" id="iniciarlotefvto">
                                        <?php
                                        $parametro1 = $item['fech_idka'];
                                        $parametro2 = $item['kar_lote'];
                                        $parametro3 = $item['kar_fvto'];
                                        $parametros = compact('parametro1', 'parametro2', 'parametro3');
                                        $cadena_json = json_encode($parametros);
                                        ?>
                                        <button id="<?php echo "agregarlotevto" . $parametro1 ?>" onclick='seleccionaropcion(<?php echo $cadena_json; ?>)' class="btn btn-success">+</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <input type="text" id="txtidartselect" style="display:none" readonly value="<?php echo $idart; ?>">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        focustablalotefvto('#tabla_lotesfvto')
    });
</script>