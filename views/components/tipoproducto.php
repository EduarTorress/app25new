<label for="">Tipo de Prod:</label>
<div>
    <select class="form-control form-control-sm" id="cmbtipoproducto" name="cmbtipoproducto" aria-label=".form-select-lg example">
        <option <?php echo ($ctipp == 'K' ? 'selected' : '') ?> value="K">KARDEX</option>
        <option <?php echo ($ctipp == 'C' ? 'selected' : '') ?> value="C">COMBO</option>
    </select>
</div>