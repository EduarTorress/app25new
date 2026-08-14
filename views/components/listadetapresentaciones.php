<div class="table-responsive">
    <?php $proyecto = (empty($_SESSION['config']['proyecto']) ? '' : $_SESSION['config']['proyecto']); ?>

    <table id="tblpresentaciones" class="table table-bordered table-hover table-sm small">
        <thead>
            <tr>
                <th>U. M.</th>
                <th class="text-end">Cantidad</th>
                <th class="text-end">Costo</th>
                <th class="text-end">Margen</th>
                <th class="text-end">Precio</th>
                <th class="text-end" <?php echo ($proyecto != 'xsys5' ? 'style="display:none;"' : ' ') ?>>Marg. Corp.</th>
                <th class="text-end" <?php echo ($proyecto != 'xsys5' ? 'style="display:none;"' : ' ') ?>>Prec. Corp.</th>
                <th class="text-center">Eliminar</th>
                <th style="display: none;">idep</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($listadetapresxproducto as $p) : ?>
                <tr ondblclick="generarpdfnombreypresentacion('<?php echo $p['pres_desc'] ?>' ,'<?php echo $p['epta_prec'] ?>')">
                    <td><?php echo $p['pres_desc'] ?></td>
                    <td class="text-end"><input type="text" onkeypress="return isNumber(event);" onclick="this.select()" onkeyup="enterceldacantidad(this,event)" style="width: 70px; text-align:right;" class="epta_cant" value="<?php echo $p['epta_cant'] ?>"></td>
                    <td class="text-end"><input type="text" onkeypress="return isNumber(event);" onclick="this.select()" onkeyup="enterceldacosto(this,event)" style="width: 70px; text-align:right;" class="epta_cost" value="<?php echo $p['epta_cost'] ?>"></td>
                    <td class="text-end"><input type="text" onkeypress="return isNumber(event);" onclick="this.select()" onkeyup="enterceldamargen(this,event)" style="width: 70px; text-align:right;" class="epta_marg" value="<?php echo $p['epta_marg'] ?>"></td>
                    <td class="text-end"><input type="text" onkeypress="return isNumber(event);" onclick="this.select()" onkeyup="enterceldaprecio(this,event)" style="width: 70px; text-align:right;" class="epta_prec" value="<?php echo $p['epta_prec'] ?>"></td>
                    <td class="text-end" <?php echo ($proyecto != 'xsys5' ? 'style="display:none;"' : ' ') ?>><input type="text" onkeypress="return isNumber(event);" onclick="this.select()" onkeyup="enterceldamargencorp(this,event)" style="width: 70px; text-align:right;" class="epta_mcor" value="<?php echo (empty($p['epta_mcor']) ? 0 : $p['epta_mcor']) ?>"></td>
                    <td class="text-end" <?php echo ($proyecto != 'xsys5' ? 'style="display:none;"' : ' ') ?>><input type="text" onkeypress="return isNumber(event);" onclick="this.select()" onkeyup="enterceldapreciocorp(this,event)" style="width: 70px; text-align:right;" class="epta_pcor" value="<?php echo (empty($p['epta_pcor']) ? 0 : $p['epta_pcor']) ?>"></td>
                    <td class="text-center">
                        <button onclick="eliminardetallepres(<?php echo $p['epta_idep'] ?>)" class="btn btn-danger">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                    <td style="display: none;"><input type="text" class="epta_idep" value="<?php echo $p['epta_idep'] ?>"></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<script>
    function enterceldacantidad(celda, event) {
        if (event.key === "Enter") {
            fila = $(celda).parent().parent();
            fila.find("td:eq(2)").find("input").click();
            fila.find("td:eq(2)").find("input").select();
        }
    }

    function enterceldacosto(celda, event) {
        if (event.key === "Enter") {
            fila = $(celda).parent().parent();
            fila.find("td:eq(3)").find("input").click();
            fila.find("td:eq(3)").find("input").select();
        }
    }

    function enterceldamargen(celda, event) {
        if (event.key === "Enter") {
            fila = $(celda).parent().parent();
            fila.find("td:eq(4)").find("input").click();
            fila.find("td:eq(4)").find("input").select();
        } else {
            txtgananciapres = $(celda).val();
            if (txtgananciapres != '') {
                fila = $(celda).parent().parent();
                celdacosto = fila.find("td:eq(2)").find("input").val();
                nuevoprecio = (celdacosto * (1 + (txtgananciapres / 100))).toFixed(2);
                fila.find("td:eq(4)").find("input").val(round(nuevoprecio, 0.1))
                fila.find("td:eq(4)").find("input").css("color", "blue");
            } else {
                fila.find("td:eq(4)").find("input").val(round(0, 0.1))
                fila.find("td:eq(4)").find("input").css("color", "blue");
            }
        }
    }

    function enterceldaprecio(celda, event) {
        if (event.key === "Enter") {
            fila = $(celda).parent().parent();
            fila.find("td:eq(5)").find("input").click();
            fila.find("td:eq(5)").find("input").select();
        } else {
            fila = $(celda).parent().parent();
            celdaprecio = $(celda).val();
            celdacosto = fila.find("td:eq(2)").find("input").val();
            if (Number(celdacosto) == 0) {
                fila.find("td:eq(3)").find("input").val("0")
                fila.find("td:eq(3)").find("input").css("color", "blue");
            } else {
                if (celdaprecio != '') {
                    txtgananciapres = (((celdaprecio - celdacosto) / celdacosto) * 100).toFixed(2);
                    fila.find("td:eq(3)").find("input").val(round(txtgananciapres, 0.1))
                    fila.find("td:eq(3)").find("input").css("color", "blue");
                } else {
                    fila.find("td:eq(3)").find("input").val(round(0, 0.1))
                    fila.find("td:eq(3)").find("input").css("color", "blue");
                }
            }
        }
    }

    function enterceldamargencorp(celda, event) {
        if (event.key === "Enter") {
            fila = $(celda).parent().parent();
            fila.find("td:eq(6)").find("input").click();
            fila.find("td:eq(6)").find("input").select();
        } else {
            txtgananciapres = $(celda).val();
            if (txtgananciapres != '') {
                fila = $(celda).parent().parent();
                celdacosto = fila.find("td:eq(2)").find("input").val();
                nuevoprecio = (celdacosto * (1 + (txtgananciapres / 100))).toFixed(2);
                fila.find("td:eq(6)").find("input").val(round(nuevoprecio, 0.1))
                fila.find("td:eq(6)").find("input").css("color", "blue");
            } else {
                fila.find("td:eq(6)").find("input").val(round(0, 0.1))
                fila.find("td:eq(6)").find("input").css("color", "blue");
            }
        }
    }

    function enterceldapreciocorp(celda, event) {
        fila = $(celda).parent().parent();
        celdaprecio = $(celda).val();
        celdacosto = fila.find("td:eq(2)").find("input").val();
        if (Number(celdacosto) == 0) {
            fila.find("td:eq(5)").find("input").val("0")
            fila.find("td:eq(5)").find("input").css("color", "blue");
        } else {
            if (celdaprecio != '') {
                txtgananciapres = (((celdaprecio - celdacosto) / celdacosto) * 100).toFixed(2);
                if (Number(txtgananciapres) > 0) {
                    fila.find("td:eq(5)").find("input").val(round(txtgananciapres, 0.1))
                } else {
                    fila.find("td:eq(5)").find("input").val(round(0, 0.1))
                }
                fila.find("td:eq(5)").find("input").css("color", "blue");
            } else {
                fila.find("td:eq(5)").find("input").val(round(0, 0.1))
                fila.find("td:eq(5)").find("input").css("color", "blue");
            }
        }
    }
</script>