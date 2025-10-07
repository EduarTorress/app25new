<?php
use App\View\Components\UbigeosComponent;
?>
<div class="modal-header">
    <h4 class="modal-title"><?php echo $titulo ?></h4>
</div>
<form action="" id="formulario-crear" autocomplete="off">
    <div class="modal-body">
        <!-- razon,ructr,nombr,placa,placa1,breve,marca, -->
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="">Razon Social:</label>
            <div class="col-sm-8">
                <input type="text" name="txtrazon" id="txtrazon" placeholder="" class="form-control" onkeyup="mayusculas(this)"  value="<?php echo ($modo == 'A' ?  $lista['razon'] : '') ?>">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="">RUC:</label>
            <div class="col-sm-8">
                <input type="text" name="txtruc" maxlength="11" id="txtruc" placeholder="" class="form-control" onkeyup="mayusculas(this)" onkeypress="return validarNumeros(event);" value="<?php echo ($modo == 'A' ?  $lista['ructr'] : '') ?>">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="">Transportista:</label>
            <div class="col-sm-8">
                <input type="text" name="txttransportista" id="txttransportista" class="form-control" onkeyup="mayusculas(this)"  value="<?php echo ($modo == 'A' ?  $lista['nombr'] : '') ?>">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="nombre">Placa 01:</label>
            <div class="col-sm-8">
                <input type="text" name="txtplaca" id="txtplaca" class="form-control" onkeyup="mayusculas(this)" value="<?php echo ($modo == 'A' ?  $lista['placa'] : '') ?>">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="">Placa 02:</label>
            <div class="col-sm-8">
                <input type="text" name="txtplaca1" id="txtplaca1" class="form-control" onkeyup="mayusculas(this)" value="<?php echo ($modo == 'A' ?  $lista['placa1'] : '') ?>">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="">Brevete:</label>
            <div class="col-sm-8">
                <input type="text" name="txtbrevete" id="txtbrevete" class="form-control" onkeyup="mayusculas(this)" value="<?php echo ($modo == 'A' ?  $lista['breve'] : '') ?>">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="">Marca:</label>
            <div class="col-sm-8">
                <input type="text" name="txtmarca" id="txtmarca" class="form-control" onkeyup="mayusculas(this)" value="<?php echo ($modo == 'A' ?  $lista['marca'] : '') ?>">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="">Contancia:</label>
            <div class="col-sm-8">
                <input type="text" name="txtconstancia" id="txtconstancia" class="form-control" onkeyup="mayusculas(this)" value="<?php echo ($modo == 'A' ?  $lista['constancia'] : '') ?>">
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