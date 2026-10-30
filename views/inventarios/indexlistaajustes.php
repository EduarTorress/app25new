<?php

use App\View\Components\EmpresaComponent;

$this->setLayout('layouts/admin');
?>
<?php
$this->startSection('contenido');
?>
<div class="content-wrapper">
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-primary card-outline">
                        <div class="card-body">
                            <form class="form-inline" id="form-search">
                                <?php
                                $ec = new EmpresaComponent('');
                                echo $ec->render();
                                ?> &nbsp;
                                <div id="fechas">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12" id="search">
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="mddetalleajustes" tabindex="-1" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="lblndoc"></h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col"><input type="text" id="txtusuario" class="form-control form-control-sm" disabled></div>
                    <div class="col"><input type="text" id="txtfecha" class="form-control form-control-sm" disabled></div>
                </div>
                <div class="p-2"></div>
                <div class="table-responsive">
                    <table id="tbldetalleajuste" class="table table-bordered table-hover table-sm small">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th class="text-end">Stock Actual</th>
                                <th class="text-end">Stock Anterior</th>
                                <th class="text-end">Cantidad Física</th>
                                <th class="text-end">Diferencia</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><?php echo $item['descri'] ?></td>
                                <td><?php echo $item['stock_actual'] ?></td>
                                <td><?php echo $item['stock_anterior'] ?></td>
                                <td><?php echo $item['cantidad_fisica'] ?></td>
                                <td><?php echo $item['diferencia'] ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" onclick="exportarStockExcel()">
                    <i class="fas fa-file-excel"></i> Exportar a Excel
                </button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<?php
$this->endSection('contenido');
$this->startSection("javascript")
?>
<script>
    document.getElementById('form-search').addEventListener('submit', function(evento) {
        evento.preventDefault();
        search();
    });

    window.onload = function() {
        titulo("<?php echo $titulo ?>");
        $("#cmbAlmacen").attr("disabled", false);
    }

    $(document).ready(function() {
        obtenerFechas();
    });

    function search() {
        dfechai = document.getElementById("txtfechai").value;
        dfechaf = document.getElementById("txtfechaf").value;
        $("#btnconsultar").attr('disabled', true);
        axios.get('/inventarios/listaajustes', {
            "params": {
                "txtfechai": dfechai,
                "txtfechaf": dfechaf,
                "cmbAlmacen": $("#cmbAlmacen").val()
            }
        }).then(function(respuesta) {
            $("#btnconsultar").attr('disabled', false);
            $("#search").html(respuesta.data);
        }).catch(function(error) {
            $("#btnconsultar").attr('disabled', false);
            toastr.error("Error al cargar el listado" + error, 'Mensaje del sistema')
        });
    }

    function verdetalle(datos) {
        $("#tbldetalleajuste tbody tr").remove();
        $("#mddetalleajustes").modal('show');
        $("#lblndoc").text("Detalle Ajuste Inventario N° " + datos.ndoc);
        $("#txtusuario").val("USUARIO: " + datos.nomb);
        $("#txtfecha").val("FECHA: " + datos.fech);
        axios.get('/inventarios/verdetalleajuste', {
            "params": {
                'idauto': datos.idauto
            }
        }).then(function(respuesta) {
            listado = respuesta.data.listado
            if (listado.length > 0) {
                for (var i = 0; i < listado.length; i++) {
                    var tr = `<tr class="fila">
                        <td>` + listado[i].descri + `</td>
                        <td class="text-end">` + listado[i].stock_actual + `</td>
                        <td class="text-end">` + listado[i].stock_anterior + `</td>
                        <td class="text-end">` + listado[i].cantidad_fisica + `</td>
                        <td class="text-end">` + listado[i].diferencia + `</td>
                        </tr>`;
                    $('#tbldetalleajuste tbody').append(tr);
                }
            } else {
                $('#tbldetalleajuste tbody').append("<tr><td class='text-center' colspan='2'>No se encontraron resultados</td></tr>");
            }
        }).catch(function(error) {
            console.log(error)
        });
    }
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script>
    function exportarStockExcel() {
        const filas = document.querySelectorAll('#tbldetalleajuste tbody tr');

        if (filas.length === 0 || !filas[0].classList.contains('fila')) {
            toastr.error("No hay productos para exportar", "Mensaje del Sistema");
            return;
        }

        // Encabezados del archivo Excel
        const datosExcel = [
            ["Producto", "Stock Actual", "Stock Anterior", "Cantidad Física", "Diferencia"]
        ];

        // Recorrer las filas de la tabla
        filas.forEach(function(fila) {
            const columnas = fila.querySelectorAll('td');

            if (columnas.length === 5) {
                datosExcel.push([
                    columnas[0].textContent.trim(),
                    Number(columnas[1].textContent.trim()) || 0,
                    Number(columnas[2].textContent.trim()) || 0,
                    Number(columnas[3].textContent.trim()) || 0,
                    Number(columnas[4].textContent.trim()) || 0
                ]);
            }
        });

        if (datosExcel.length === 1) {
            toastr.error("No hay productos para exportar", "Mensaje del Sistema");
            return;
        }

        // Crear el libro y la hoja de Excel
        const hoja = XLSX.utils.aoa_to_sheet(datosExcel);
        const libro = XLSX.utils.book_new();

        XLSX.utils.book_append_sheet(libro, hoja, "Detalle Ajustes");

        // Ajustar el ancho de las columnas
        hoja['!cols'] = [{
                wch: 35
            },
            {
                wch: 15
            },
            {
                wch: 15
            },
            {
                wch: 18
            },
            {
                wch: 15
            }
        ];

        // Generar y descargar el archivo
        const fecha = new Date().toISOString().slice(0, 10);
        XLSX.writeFile(libro, `Detalle_Ajuste_Inventario_${fecha}.xlsx`);

        toastr.success("Archivo Excel generado correctamente", "Mensaje del Sistema");
    }
</script>
<?php
$this->endSection("javascript");
?>