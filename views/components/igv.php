<div class="form-check form-check-inline">
    <input class="form-check-input igv" type="radio" name="igv" onclick="grabarCabecera(); calcularIGV(); changeoptigv();" <?php echo ($optigv == 'I' ? 'checked' : '') ?> value="I" onchange="obtenerTipoIGV();">
    <label class="form-check-label" for="incluido">IGV Incluido</label>
</div>
<div class="form-check form-check-inline">
    <input class="form-check-input igv" type="radio" name="igv" onclick="grabarCabecera(); calcularIGV(); changeoptigv();" <?php echo ($optigv == 'N' ? 'checked' : '') ?> value="N" onchange="obtenerTipoIGV();">
    <label class="form-check-label" for="noincluido">NO Incluido</label>
</div>