<style>
    /* Estilos exclusivos para el modal de combos */
    #modalCombo {
        color: #334155;
    }

    #modalCombo .combo-header {
        padding: 20px 24px;
        background: #fff;
        border-bottom: 1px solid #e8edf3;
    }

    #modalCombo .combo-header h4 {
        margin: 0;
        font-size: 1.25rem;
        font-weight: 700;
        color: #1e293b;
    }

    #modalCombo .combo-subtitle {
        margin: 5px 0 0;
        color: #64748b;
        font-size: .875rem;
    }

    #modalCombo .modal-body {
        padding: 22px;
        background: #f8fafc;
    }

    #modalCombo .combo-panel {
        height: 100%;
        padding: 18px;
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
    }

    #modalCombo .panel-title {
        display: flex;
        align-items: center;
        gap: 9px;
        margin-bottom: 16px;
        font-size: 1rem;
        font-weight: 700;
        color: #1e293b;
    }

    #modalCombo .panel-number {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 29px;
        height: 29px;
        border-radius: 8px;
        background: #eaf2ff;
        color: #2563eb;
        font-weight: 700;
        font-size: .85rem;
    }

    #modalCombo .form-control {
        min-height: 42px;
        border-color: #dbe3ed;
        border-radius: 7px;
    }

    #modalCombo .form-control:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 .2rem rgba(13, 110, 253, .10);
    }

    #modalCombo .btn {
        border-radius: 7px;
        font-weight: 600;
    }

    #modalCombo .search-help {
        margin-top: 8px;
        color: #64748b;
        font-size: .8rem;
    }

    #modalCombo #searchP {
        min-height: 180px;
    }

    #modalCombo .table-responsive {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
    }

    #modalCombo #detallecombo {
        margin-bottom: 0;
        vertical-align: middle;
    }

    #modalCombo #detallecombo thead th {
        padding: 11px 9px;
        background: #f1f5f9;
        color: #475569;
        font-size: .78rem;
        text-transform: uppercase;
        white-space: nowrap;
        border-bottom: 1px solid #e2e8f0;
    }

    #modalCombo #detallecombo tbody td {
        padding: 8px;
    }

    #modalCombo #detallecombo input {
        width: 100%;
        min-width: 65px;
        padding: 5px;
        border: 1px solid transparent;
        border-radius: 5px;
        background: transparent;
        color: #334155;
    }

    #modalCombo #detallecombo .nombre {
        min-width: 120px;
    }

    #modalCombo #detallecombo .costo {
        text-align: right;
        font-weight: 600;
    }

    #modalCombo #detallecombo tbody tr:hover {
        background: #f8fafc;
    }

    #modalCombo #detallecombo .borrar {
        width: 32px;
        height: 32px !important;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 0 !important;
        border-radius: 7px;
        background: #fee2e2 !important;
        color: #dc2626;
        cursor: pointer;
    }

    #modalCombo #detallecombo .borrar:hover {
        background: #fecaca !important;
    }

    #modalCombo .combo-summary {
        padding: 15px;
        margin-top: 16px;
        background: #eff6ff;
        border: 1px solid #dbeafe;
        border-radius: 10px;
    }

    #modalCombo .summary-label {
        display: block;
        margin-bottom: 5px;
        color: #475569;
        font-size: .8rem;
        font-weight: 600;
    }

    #modalCombo .total-input {
        padding: 0;
        border: 0;
        background: transparent;
        color: #1d4ed8;
        font-size: 1.65rem;
        font-weight: 750;
        box-shadow: none;
    }

    #modalCombo .stock-section {
        margin-top: 18px;
    }

    #modalCombo .stock-section label {
        margin-bottom: 7px;
        font-size: .88rem;
        font-weight: 600;
    }

    #modalCombo .stock-help {
        margin-top: 6px;
        color: #64748b;
        font-size: .78rem;
    }

    #modalCombo .modal-footer {
        padding: 16px 22px;
        background: #fff;
        border-top: 1px solid #e8edf3;
    }

    #modalCombo .btn-registrar {
        min-width: 145px;
    }

    @media (max-width: 767.98px) {
        #modalCombo .modal-body {
            padding: 12px;
        }

        #modalCombo .combo-panel {
            padding: 14px;
        }

        #modalCombo .combo-header {
            padding: 16px;
        }

        #modalCombo .modal-footer {
            padding: 12px;
        }

        #modalCombo .btn-registrar {
            min-width: 0;
            flex: 1;
        }

        #modalCombo .modal-footer .btn {
            flex: 1;
        }
    }

    /* Panel de resultados de búsqueda */
    #modalCombo #searchP {
        min-height: 0;
        max-height: 350px;
        overflow: auto;
        border: 1px solid #dfe6ee;
        border-radius: 8px;
        background: #fff;
    }

    /* La tabla ocupa el ancho disponible, no más */
    #modalCombo #searchP table {
        width: 100% !important;
        margin-bottom: 0;
        font-size: 12px;
        table-layout: fixed;
    }

    /* Encabezados compactos */
    #modalCombo #searchP table thead th {
        position: sticky;
        top: 0;
        z-index: 2;
        padding: 9px 7px;
        background: #eaf2f8;
        color: #334155;
        font-size: 11px;
        font-weight: 700;
        white-space: nowrap;
    }

    /* Filas más compactas */
    #modalCombo #searchP table tbody td {
        padding: 7px;
        vertical-align: middle;
        line-height: 1.35;
        overflow-wrap: anywhere;
    }

    /* Evita que nombres largos ensanchen la tabla */
    #modalCombo #searchP table tbody td:first-child {
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* Evita que botones o controles deformen las columnas */
    #modalCombo #searchP table th,
    #modalCombo #searchP table td {
        box-sizing: border-box;
    }

    /* Scroll más discreto */
    #modalCombo #searchP::-webkit-scrollbar {
        width: 7px;
        height: 7px;
    }

    #modalCombo #searchP::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }

    #modalCombo #searchP::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }

    /* Tabla de resultados: aprovechar mejor el espacio */
    #modalCombo #searchP table {
        width: 100% !important;
        table-layout: auto !important;
        font-size: 13px;
    }

    /* Nombre del producto más amplio y legible */
    #modalCombo #searchP table th:first-child,
    #modalCombo #searchP table td:first-child {
        width: 60%;
        min-width: 240px;
        font-size: 13px;
        padding: 10px;
    }

    /* Ocultar la columna Unidad */
    #modalCombo #searchP table th:nth-child(2),
    #modalCombo #searchP table td:nth-child(2) {
        display: none;
    }

    /* Filas más cómodas de leer */
    #modalCombo #searchP table tbody td {
        padding-top: 10px;
        padding-bottom: 10px;
    }

    /* Mantener el texto del producto en una sola línea */
    #modalCombo #searchP table tbody td:first-child {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>
<div id="modalCombo">
    <div class="modal-header combo-header">
        <div>
            <h4 id="lbltitulodetalle">
                <?php echo htmlspecialchars($titulo ?? '', ENT_QUOTES, 'UTF-8'); ?>
            </h4>
            <p class="combo-subtitle"> Busca productos, configura el combo y revisa su costo. </p>
        </div>
        <button type="button" class="btn-close" aria-label="Cerrar" onclick="closemodaldetalle()"></button>
    </div>
    <div class="modal-body">
        <div class="row g-3">
            <!-- PANEL DE BÚSQUEDA -->
            <div class="col-12 col-lg-7">
                <div class="combo-panel">
                    <div class="panel-title">
                        <span class="panel-number">1</span>
                        Buscar productos
                    </div>
                    <label for="txtbuscarProducto" class="form-label small fw-semibold">
                        Nombre del producto
                    </label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="txtbuscarProducto" name="txtbuscarProducto" placeholder="Escribe el nombre o código..." autocomplete="off">
                        <input type="hidden" id="txtidproducto" name="txtidproducto">
                        <button class="btn btn-primary" type="button" onclick="buscarProductoModal()">
                            Buscar
                        </button>
                    </div>
                    <div class="search-help">
                        Escribe el producto y pulsa Buscar o presiona Enter.
                    </div>
                    <hr class="my-3">
                    <div class="panel-title mb-3">
                        Resultados
                    </div>
                    <div class="table-responsive">
                        <div id="searchP">
                            <!-- Aquí se muestran los productos encontrados -->
                        </div>
                    </div>
                </div>
            </div>
            <!-- PANEL DE DETALLE -->
            <div class="col-12 col-lg-5">
                <div class="combo-panel">
                    <div class="panel-title">
                        <span class="panel-number">2</span>
                        Productos del combo
                    </div>
                    <p class="text-secondary small mb-3">
                        Revisa los productos agregados y elimina los que no necesites.
                    </p>
                    <div class="table-responsive">
                        <table id="detallecombo" class="table table-hover table-sm">
                            <thead>
                                <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">Producto</th>
                                    <th scope="col" class="text-end">Costo</th>
                                    <th scope="col" class="text-center">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($combo as $c): ?>
                                    <tr class="fila">
                                        <?php
                                        $stock = $c['uno'] + $c['dos'] + $c['tre'] + $c['cua'] + $c['cin'];
                                        $validarstock = (empty($_SESSION['config']['validarstock']) ? 'N' : $_SESSION['config']['validarstock']);
                                        if ($validarstock == 'S') {
                                            $stock = floatval($c[verificarenquesucursalestoy()]);
                                        }
                                        ?>
                                        <td>
                                            <input type="text" data-stock="<?php echo htmlspecialchars((string)$stock, ENT_QUOTES, 'UTF-8'); ?>" class="idart" value="<?php echo htmlspecialchars((string)$c['epta_idar'], ENT_QUOTES, 'UTF-8'); ?>" readonly>
                                        </td>
                                        <td>
                                            <input type="text" class="nombre" value="<?php echo htmlspecialchars((string)$c['descri'], ENT_QUOTES, 'UTF-8'); ?>" readonly>
                                        </td>
                                        <td>
                                            <input type="text" class="costo" value="<?php echo htmlspecialchars((string)$c['epta_cost'], ENT_QUOTES, 'UTF-8'); ?>" readonly>
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="borrar" title="Eliminar producto" aria-label="Eliminar producto">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- RESUMEN DEL COSTO -->
                    <div class="combo-summary">
                        <div class="d-flex align-items-center justify-content-between gap-3">
                            <div>
                                <span class="summary-label">Costo total del combo</span>
                                <small class="text-secondary">Suma de los productos agregados</small>
                            </div>
                            <div style="max-width: 155px;">
                                <input type="text" class="form-control total-input text-end" id="txtcostototal" value="0.00" readonlyaria-label="Costo total del combo">
                            </div>
                        </div>
                    </div>
                    <!-- STOCK -->
                    <div class="stock-section">
                        <label for="txtcantidadingresante">Cantidad de combos a ingresar al stock</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <span aria-hidden="true">&#128230;</span>
                            </span>
                            <input type="number" class="form-control" id="txtcantidadingresante" min="1" step="1" inputmode="numeric" placeholder="Ej. 10">
                            <span class="input-group-text">unidades</span>
                        </div>
                        <div class="stock-help">
                            Indica cuántas unidades del combo deseas ingresar.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ACCIONES -->
    <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" onclick="closemodaldetalle()">Cerrar</button>
        <button type="button" class="btn btn-success btn-registrar" onclick="registrarcombo()">
            <span class="me-1">&#10003;</span>
            Registrar combo
        </button>
    </div>
</div>
<script>
    // Mantiene el cálculo inicial del costo.
    calcularcostototal();

    // Permite realizar la búsqueda presionando Enter.
    document.getElementById('txtbuscarProducto')
        .addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                buscarProductoModal();
            }
        });
</script>