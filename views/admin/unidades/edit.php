<div class="modal-header">
    <h4 class="modal-title">Actualizar categoría</h4>
</div>
<form action="" id="formulario-editar" autocomplete="off">
    <div class="modal-body">
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="nombre">Nombre</label>
            <div class="col-sm-8">
                <input value="<?php echo $categoria->nombre ?>" type="text" name="nombre" id="nombre" class="form-control" />
            </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-window-close"></i> Cerrar
        </button>
        <button id="btn-submit" type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Actualizar</button>
    </div>
</form>
<script>
    document.getElementById('formulario-editar').addEventListener('submit', function(evento) {
        evento.preventDefault();
        update(<?php echo $categoria->id ?>);
    });
</script>