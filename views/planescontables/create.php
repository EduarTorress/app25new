<div class="modal-header">
    <h4 class="modal-title"><?php echo $titulo ?></h4>
</div>
<form action="" id="formulario-crear" autocomplete="off">
    <div class="modal-body">
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="">N° Cuenta:</label>
            <div class="col-sm-8">
                <input type="text" name="txtnrocuenta" id="txtnrocuenta" class="form-control" onkeyup="mayusculas(this)" value="<?php echo ($modo == 'A' ?  $lista['ncta'] : '') ?>">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="">Descripción:</label>
            <div class="col-sm-8">
                <input type="text" name="txtnombre" id="txtnombre" class="form-control" onkeyup="mayusculas(this)" value="<?php echo ($modo == 'A' ?  $lista['nomb'] : '') ?>">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="">Tipo de Cuenta:</label>
            <div class="col-sm-8">
                <select class="form-control" name="cmbtipocta" id="cmbtipocta">
                    <option value="ACTIVO" <?php echo ((!empty($lista['tipocta']) ? $lista['tipocta'] : '') == 'ACTIVO' ? 'selected' : ' ') ?>>ACTIVO</option>
                    <option value="PASIVO" <?php echo ((!empty($lista['tipocta']) ? $lista['tipocta'] : '') == 'PASIVO' ? 'selected' : ' ') ?>>PASIVO</option>
                    <option value="NATURALEZA" <?php echo ((!empty($lista['tipocta']) ? $lista['tipocta'] : '') == 'NATURALEZA' ? 'selected' : ' ') ?>>NATURALEZA</option>
                    <option value="FUNCION" <?php echo ((!empty($lista['tipocta']) ? $lista['tipocta'] : '') == 'FUNCION' ? 'selected' : ' ') ?>>FUNCIÓN</option>
                    <option value="ORDEN" <?php echo ((!empty($lista['tipocta']) ? $lista['tipocta'] : '') == 'ORDEN' ? 'selected' : ' ') ?>>ORDEN</option>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="">Operación a ejecutar:</label>
            <div class="col-sm-8">
                <input type="text" name="txtoperacion" id="txtoperacion" class="form-control" onkeyup="mayusculas(this)" value="<?php echo ($modo == 'A' ?  $lista['plan_oper'] : '') ?>">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="">Cta. de Destino al Debe:</label>
            <div class="col-sm-8">
                <select class="form-control" name="txtcuentadestinodebe" id="txtcuentadestinodebe">
                    <?php foreach ($listarcuentasd as $d) : ?>
                        <option value="<?php echo $d['cdestinod'] ?>" <?php echo ((!empty($lista['cdestinod']) ? $lista['cdestinod'] : '') == $d['cdestinod'] ? 'selected' : ' ') ?>><?php echo $d['cdestinod'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="">Cta. de Destino al Haber:</label>
            <div class="col-sm-8">
                <select class="form-control" name="txtcuentadestinohaber" id="txtcuentadestinohaber">
                <?php foreach ($listarcuentash as $h) : ?>
                        <option value="<?php echo $h['cdestinoh'] ?>" <?php echo ((!empty($lista['cdestinoh']) ? $lista['cdestinoh'] : '') == $h['cdestinoh'] ? 'selected' : ' ') ?>><?php echo $h['cdestinoh'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="">Código SUNAT:</label>
            <div class="col-sm-8">
                <input type="text" name="txtcodigosunat" id="txtcodigosunat" class="form-control" value="<?php echo ($modo == 'A' ?  $lista['ctasunat'] : '') ?>">
            </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-danger" id="cmdcerrar" onclick="cerrarmodal()" data-dismiss="modal">
            <i class="fa fa-window-close"></i> Cerrar
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