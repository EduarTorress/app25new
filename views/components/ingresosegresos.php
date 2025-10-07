<div class="mb-3 row">
    <label for="" class="col-sm-2 col-form-label">Nro Documento</label>
    <div class="col-sm-3">
        <?php if ($tipo == 'I') { ?>
            <input type="text" class="form-control" id="txtnumerodocumentoi">
        <?php } else { ?>
            <input type="text" class="form-control" id="txtnumerodocumentoe">
        <?php } ?>
    </div>
</div>
<div class="mb-3 row">
    <label for="" class="col-sm-2 col-form-label">Fecha</label>
    <div class="col-sm-3">
        <?php if ($tipo == 'I') { ?>
            <input type="date" class="form-control fecha" id="txtfechai" value="<?php echo date('Y-m-d') ?>" readonly>
        <?php } else { ?>
            <input type="date" class="form-control fecha" id="txtfechae" value="<?php echo date('Y-m-d') ?>" readonly>
        <?php } ?>
    </div>
</div>
<!-- <div class="mb-3 row">
    <label for="" class="col-sm-2 col-form-label">Concepto</label>
    <div class="col-sm-3">
        <?php if ($tipo == 'I') { ?>
            <select name="cmbconcepto" id="cmbconcepto" class="form-control">
                <option value="1">BOLETAS EN EFECTIVO</option>
                <option value="2">FACTURAS EN EFECTIVO</option>
                <option value="3">N/VENTAS AL CONTADO</option>
                <option value="4">OTROS INGRESOS</option>
                <option value="5">PAGO CTAS POR COBRAR</option>
                <option value="6">SALDO INICIAL</option>
            </select>
        <?php } else { ?>
            <select name="cmbconcepto" id="cmbconcepto" class="form-control">
                <option value="1">ADELANTO A PERSONAL</option>
                <option value="2">COMISIONES</option>
                <option value="3">COMPRAS AL CONTADO</option>
                <option value="4">FLETE</option>
                <option value="5">GAS CAMIONETA</option>
                <option value="6">GASOLINA CARRO </option>
                <option value="7">GASOLINA MOTO </option>
                <option value="8">GASTOS DIVERSOS </option>
                <option value="9">MANTENIMIENTO MOTO </option>
                <option value="10">PAGO A PROVEEDORES </option>
                <option value="11">PASAJES </option>
                <option value="12">REFRIGERIO </option>
                <option value="13">SALIDA DE DINERO </option>
                <option value="14">TRICICLOS </option>
            </select>
        <?php } ?>
    </div>
</div> -->
<div class="mb-3 row">
    <label for="" class="col-sm-2 col-form-label">Importe</label>
    <div class="col-sm-3">
        <?php if ($tipo == 'I') { ?>
            <input type="number" class="form-control" id="txtimportei" placeholder="0.00">
        <?php } else { ?>
            <input type="number" class="form-control" id="txtimportee" placeholder="0.00">
        <?php } ?>
    </div>
</div>
<div class="mb-3 row">
    <label for="" class="col-sm-2 col-form-label">Detalle</label>
    <div class="col">
        <?php if ($tipo == 'I') { ?>
            <input type="text" class="form-control" id="txtdetallei">
        <?php } else { ?>
            <input type="text" class="form-control" id="txtdetallee">
        <?php } ?>
    </div>
</div>
<div class="mb-3 row">
    <div class="col-sm">
        <button type="button" onclick="limpiar();" class="btn btn-danger float-right"><i class="fas fa-refresh"></i> Limpiar</button>
        <button type="button" id="btnregistrar" onclick="registrar('<?php echo $tipo; ?>');" class="btn btn-success float-right"><i class="fas fa-plus-circle"></i> Registrar</button>
    </div>
</div>