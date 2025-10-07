<table class="table table-sm small table table-hover" id="griddetalle">
    <thead>
        <tr>
            <th scope="col">Detalle</th>
            <th scope="col">N° Documento</th>
            <th scope="col" data-footer-formatter="formatTotal">Efectivo</th>
            <th scope="col" data-footer-formatter="formatTotal">Crédito</th>
            <th scope="col" data-footer-formatter="formatTotal">Depósito</th>
            <th scope="col" data-footer-formatter="formatTotal">Tarjeta</th>
            <th scope="col" data-footer-formatter="formatTotal">YAPE</th>
            <th scope="col" data-footer-formatter="formatTotal">PLIN</th>
            <th scope="col" data-footer-formatter="formatTotal">Contra Ent.</th>
            <th scope="col" data-footer-formatter="formatTotal">Egresos</th>
            <th scope="col">Usuario</th>
            <th scope="col">Moneda</th>
            <th scope="col">Fecha/Hora</th>
        </tr>
    </thead>
    <tbody id="">
        <?php foreach ($lista as $l) : ?>
            <tr>
                <td><?php echo $l['deta']; ?></td>
                <td><?php echo $l['ndoc']; ?></td>
                <td><?php echo evaluarvalortdoccaja($l['ndoc'], $l['efectivo']); ?></td>
                <td><?php echo evaluarvalortdoccaja($l['ndoc'], $l['credito']); ?></td>
                <td><?php echo evaluarvalortdoccaja($l['ndoc'], $l['deposito']); ?></td>
                <td><?php echo evaluarvalortdoccaja($l['ndoc'], $l['tarjeta']); ?></td>
                <td><?php echo evaluarvalortdoccaja($l['ndoc'], $l['yape']); ?></td>
                <td><?php echo evaluarvalortdoccaja($l['ndoc'], $l['plin']); ?></td>
                <td><?php echo evaluarvalortdoccaja($l['ndoc'], $l['Centrega']); ?></td>
                <td><?php echo evaluarvalortdoccaja($l['ndoc'], $l['egresos']); ?></td>
                <td><?php echo $l['usua']; ?></td>
                <td><?php echo $l['mone']; ?></td>
                <td><?php echo $l['fechao']; ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</div>
<div class="card ">
    <div class="card-body">
        <div class="mb-3 input-group float-right ">
            <div class="col-6">
                <input type="text" class="form-control" name="txtreferencia" id="txtreferencia" placeholder="Ingrese una referencia" value="">
            </div>
            <label for="txtvtascred" class="col-sm-0.5 col-form-label col-form-label-sm">Total de Ventas: </label>
            <div class="col">
                <input readonly type="text" style="text-align:right;" class="form-control form-control-sm" id="txttotalvtas" value="<?php echo round($total, 2); ?>">
                <input type="hidden" style="text-align:right;" class="form-control form-control-sm" id="txtsobrante" value="<?php echo round($sobrante, 2); ?>">
            </div>
               <label for="" class="col-sm-0.5 col-form-label col-form-label-sm">Total Liquidez: </label>
            <div class="col">
                <input type="text" readonly style="text-align:right;" class="form-control form-control-sm" id="" value="<?php echo round($totalliquidez, 2); ?>">
            </div>
            <div class="col">
                <button class="btn btn-success btn-sm" onclick="enviarcorreo();">Enviar Correo</button>
            </div>
            <div class="col">
                <button class="btn btn-primary btn-sm" onclick='generarticketcaja(<?php echo json_encode($lista) ?>)'>Imprimir Ticket</button>
            </div>
        </div>
    </div>
</div>
<script>
    $("#txtingresos").val('<?php echo $saldo['ingresoss']; ?>');
    $("#txtegresos").val('<?php echo $saldo['egresoss']; ?>');
    $("#txtsaldoanterior").val('<?php echo $saldo['saldoanterior']; ?>');
    reportetablebt("#griddetalle");
</script>
<!-- <label for="txtvtascred" class="col-sm-0.5 col-form-label col-form-label-sm">Crédit: </label>
<div class="col-sm-1">
    <input readonly type="text" class="form-control form-control-sm" id="txtvtascred" value="">
</div>
<label for="txtvtasefec" class="col-sm-0.5 col-form-label col-form-label-sm">Efect: </label>
<div class="col-sm-1">
    <input readonly type="text" class="form-control form-control-sm" id="txtvtasefec" value="">
</div>
<label for="txtotrosing" class="col-sm-0.5 col-form-label col-form-label-sm">Otros Ing: </label>
<div class="col-sm-1">
    <input readonly type="text" class="form-control form-control-sm" id="txtotrosing" value="">
</div>
<label for="txtpagosact" class="col-sm-0.5 col-form-label col-form-label-sm">Pagos Act: </label>
<div class="col-sm-1">
    <input readonly type="text" class="form-control form-control-sm" id="txtpagosact" value="">
</div>
<label for="txtingsincaja" class="col-sm-0.5 col-form-label col-form-label-sm">Ing sin Caj: </label>
<div class="col-sm-1">
    <input readonly type="text" class="form-control form-control-sm" id="txtingsincaja" value="">
</div>
<label for="txtegresos" class="col-sm-0.5 col-form-label col-form-label-sm">Egresos: </label>
<div class="col-sm-1">
    <input readonly type="text" class="form-control form-control-sm" id="txtegresos" value="">
</div>
<label for="txtliquidacion" class="col-sm-0.5 col-form-label col-form-label-sm">Liqui: </label>
<div class="col-sm-1">
    <input readonly type="text" class="form-control form-control-sm" id="txtliquidacion" value="">
</div> -->