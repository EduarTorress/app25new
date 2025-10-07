<div class="table-responsive">
    <table id="tblingresos" class="table table-bordered table-hover table-sm small">
        <thead>
            <tr>
                <th class="d-lg-none">NCONTROL</th>
                <th class="d-lg-none">ID</th>
                <th class="d-lg-none">Tipo</th>
                <th class="d-lg-none">NROU</th>
                <th class="d-lg-none">IDRC</th>
                <th>N° Doc</th>
                <th class="d-lg-none">Mon.</th>
                <th>Fecha Emi.</th>
                <th>Fecha Venc.</th>
                <th class="d-lg-none">ID Clie.</th>
                <th>Cliente</th>
                <th>Impo. Orig.</th>
                <th>Saldo</th>
                <th style="text-align: center;">Cancelación</th>
                <!-- <th class="text-center">Opción</th> -->
            </tr>
        </thead>
        <tbody>
            <?php foreach ($ingresosxcancelar as $ic) : ?>
                <tr>
                    <td class="ncontrol"><?php echo $ic['ncontrol'] ?></td>
                    <td class="idauto"><?php echo $ic['Idauto'] ?></td>
                    <td class="tdoc"><?php echo $ic['tdoc'] ?></td>
                    <td class="nrou"><?php echo $ic['nrou'] ?></td>
                    <td class="idrc"><?php echo $ic['idrc'] ?></td>
                    <td><?php echo $ic['ndoc'] ?></td>
                    <td class="mon"><?php echo $ic['moneda'] ?></td>
                    <td style="text-align: center;" class="fech"><?php echo $ic['fech'] ?></td>
                    <td style="text-align: center;" class="fechvto"><?php echo $ic['fevto'] ?></td>
                    <td style="text-align: center;" class="idrazo"><?php echo $ic['idclie'] ?></td>
                    <td style="text-align: center;" class="razo"><?php echo $ic['razo'] ?></td>
                    <td style="text-align: end;" class="impo"><?php echo $ic['importec'] ?></td>
                    <td style="text-align: end;" class="saldo"><?php echo $ic['saldo'] ?></td>
                    <td style="text-align: end;" class="inputcancelacion"><input type="text" onclick="this.select()" class="form-control form-control-sm text-end" value="0"></td>
                    <!-- <td class="text-center"><button onclick="agregaringreso(<?php echo $ic['Idauto'] ?>)" class="btn btn-primary"><i class="fas fa-plus"></i></button></td> -->
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<script>
    reportetablebt("#tblingresos");
    $("#btnExportexcel").css("display", "none");
    $("#btnExportpdf").css("display", "none");

    $(".ncontrol").css("display", "none");
    $(".idauto").css("display", "none");
    $(".tdoc").css("display", "none");
    $(".idrazo").css("display", "none");
    $(".mon").css("display", "none");
    $(".nrou").css("display", "none");
    $(".idrc").css("display", "none");
</script>