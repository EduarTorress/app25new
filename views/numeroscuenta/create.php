<div class="modal-header">
    <h4 class="modal-title"><?php echo $titulo ?></h4>
</div>
<form action="" id="formulario-crear" autocomplete="off">
    <div class="modal-body">
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="">Bancos:</label>
            <div class="col-sm-8">
                <select class="form-control" name="cmbbancos" id="cmbbancos">
                    <?php foreach ($listabancos as $b) : ?>
                        <option value="<?php echo $b['banc_idba'] ?>" <?php echo ((!empty($lista['ctas_idba']) ? $lista['ctas_idba'] : '') == $b['banc_idba'] ? 'selected' : ' ') ?>><?php echo strtoupper($b['banc_nomb']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="">N° Cuenta:</label>
            <div class="col-sm-8">
                <input type="text" name="txtnrocuenta" id="txtnrocuenta" class="form-control" onkeyup="mayusculas(this)" value="<?php echo ($modo == 'A' ?  $lista['ctas_ctas'] : '') ?>">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="">Moneda:</label>
            <div class="col-sm-8">
                <select class="form-control" name="cmbmoneda" id="cmbmoneda">
                    <option value="S" <?php echo ((!empty($lista['ctas_mone']) ? $lista['ctas_mone'] : '') == 'S' ? 'selected' : ' ') ?>>SOLES</option>
                    <option value="D" <?php echo ((!empty($lista['ctas_mone']) ? $lista['ctas_mone'] : '') == 'D' ? 'selected' : ' ') ?>>DÓLARES</option>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="">Referencia:</label>
            <div class="col-sm-8">
                <input type="text" name="txtreferencia" id="txtreferencia" class="form-control" onkeyup="mayusculas(this)" value="<?php echo ($modo == 'A' ?  $lista['ctas_deta'] : '') ?>">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="">Cuenta Asociada:</label>
            <div class="col-sm-8">
                <select class="form-control" name="cmbcuentasociada" id="cmbcuentasociada">
                    <?php foreach ($listaplanes as $lp) : ?>
                        <option value="<?php echo $lp['idcta'] ?>" <?php echo ((!empty($lista['ctas_ncta']) ? $lista['ctas_ncta'] : '') == $lp['ncta'] ? 'selected' : ' ') ?>><?php echo strtoupper($lp['ncta']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-danger" id="cmdcerrar" onclick="cerrarmodal()" data-dismiss="modal"><i class="fa fa-window-close"></i> Cerrar
        </button>
        <button id="btn-submit" type="submit" class="btn btn-primary"><i class="fas fa-save"></i>
            <?php echo ($modo == 'N') ? 'Registrar' : 'Actualizar' ?></button>
    </div>
</form>
<script>
    document.getElementById('formulario-crear').addEventListener('submit', function(evento) {
        evento.preventDefault();
        store('<?php echo $modo ?>', <?php echo $id ?>);
    })
</script>