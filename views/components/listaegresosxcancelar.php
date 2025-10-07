<div class="table-responsive">
    <table id="tblegresos" class="table table-bordered table-hover table-sm small">
        <thead>
            <tr>
                <th class="d-lg-none">NCONTROL</th>
                <th class="d-lg-none"> ID</th>
                <th class="d-lg-none">Tipo</th>
                <th class="d-lg-none">NROU</th>
                <th class="d-lg-none">IDRC</th>
                <th>N° Doc</th>
                <th class="d-lg-none">Mon.</th>
                <th>Fecha Emi.</th>
                <th>Fecha Venc.</th>
                <th class="d-lg-none">ID Prov.</th>
                <th>Proveedor</th>
                <th>Impo. Orig.</th>
                <th>Saldo</th>
                <th style="text-align: center;">Cancelación</th>
                <!-- <th class="text-center">Opción</th> -->
            </tr>
        </thead>
        <tbody>
            <?php foreach ($egresosxcancelar as $ec) : ?>
                <tr>
                    <td class="ncontrol"><?php echo $ec['ncontrol'] ?></td>
                    <td class="idauto"><?php echo $ec['Idauto'] ?></td>
                    <td class="tdoc"><?php echo $ec['tdoc'] ?></td>
                    <td class="nrou"><?php echo $ec['nrou'] ?></td>
                    <td class="idrc"><?php echo $ec['Idrd'] ?></td>
                    <td class="ndoc"><?php echo $ec['ndoc'] ?></td>
                    <td class="mon"><?php echo $ec['Moneda'] ?></td>
                    <td style="text-align: center;" class="fech"><?php echo $ec['fech'] ?></td>
                    <td style="text-align: center;" class="fechvto"><?php echo $ec['fechv'] ?></td>
                    <td style="text-align: center;" class="idrazo"><?php echo $ec['idprov'] ?></td>
                    <td style="text-align: center;" class="razo"><?php echo $ec['razo'] ?></td>
                    <td style="text-align: end;" class="impo"><?php echo $ec['impo'] ?></td>
                    <td style="text-align: end;" class="saldo"><?php echo $ec['saldo'] ?></td>
                    <td style="text-align: end;" class="inputcancelacion"><input type="text" onkeypress="return isNumber(event);" onclick="this.select()" class="form-control form-control-sm text-end" value="0"></td>
                    <!-- <td class="text-center"><button onclick="agregaregreso(<?php echo $ec['idauto'] ?>)" class="btn btn-primary"><i class="fas fa-plus"></i></button></td> -->
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<script>
    reportetablebt("#tblegresos");
    $("#btnExportexcel").css("display", "none");
    $("#btnExportpdf").css("display", "none");
    $(".ncontrol").css("display", "none");
    $(".idauto").css("display", "none");
    $(".tdoc").css("display", "none");
    $(".idrazo").css("display", "none");
    $(".mon").css("display", "none");
    $(".nrou").css("display", "none");
    $(".idrc").css("display", "none");
    // $("thead").find(`[data-slide="0"]`).css("display", "none")
</script>