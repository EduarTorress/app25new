<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta de Precios</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css">
</head>
<style>
    /* ===========================
    RESET
    =========================== */

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    /* ===========================
    BODY
    =========================== */

    body {
        background: #F5F7FA;
        font-family: 'Segoe UI', sans-serif;
        color: #333;
        height: 100vh;
    }

    /* ===========================
    CONTENEDOR
    =========================== */

    .contenedor {
        width: 94%;
        max-width: 1600px;
        height: 100vh;
        margin: 0 auto;
        padding: 10px 0;
    }

    /* ===========================
    HEADER
    =========================== */

    header {
        text-align: center;
        margin-bottom: 15px;
    }

    .logo {
        width: 220px;
        margin-bottom: 8px;
    }

    header h1 {
        color: #1E88C8;
        font-size: 40px;
        font-weight: 700;
    }

    header p {
        color: #666;
        font-size: 18px;
        margin-top: 4px;
    }

    /* ===========================
    BÚSQUEDA
    =========================== */

    .busqueda {
        display: flex;
        align-items: center;
        background: white;
        border-radius: 12px;
        border: 3px solid #1E88C8;
        box-shadow: 0 6px 18px rgba(0, 0, 0, .12);
        margin-bottom: 18px;
        overflow: hidden;
    }

    .icono {
        font-size: 26px;
        padding: 0 18px;
    }

    .busqueda input {
        width: 100%;
        height: 55px;
        border: none;
        outline: none;
        font-size: 24px;
        padding-right: 20px;
    }

    .busqueda:focus-within {
        border-color: #D50000;
        box-shadow: 0 0 18px rgba(213, 0, 0, .25);
    }

    /* ===========================
    PRODUCTO
    =========================== */

    .producto {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 8px 20px rgba(0, 0, 0, .15);
    }

    /* ===========================
    CABECERA PRODUCTO
    =========================== */

    .cabecera-producto {
        background: #1E88C8;
        color: white;
        padding: 16px 30px;
        border-bottom: 4px solid #D50000;
    }

    .cabecera-producto h2 {
        font-size: 32px;
        margin-bottom: 4px;
    }

    .cabecera-producto span {
        font-size: 17px;
    }

    /* ===========================
    TABLA
    =========================== */

    table {
        width: 100%;
        border-collapse: collapse;
    }

    thead {
        background: #EAF4FB;
    }

    thead th {
        padding: 10px 15px;
        color: #1E88C8;
        font-size: 20px;
        font-weight: bold;
        text-align: center;
    }

    tbody tr {
        height: 42px;
    }

    tbody td {
        padding: 8px 15px;
        text-align: center;
        font-size: 20px;
        line-height: 1.2;
        border-top: 1px solid #E5E5E5;
    }

    tbody tr:nth-child(even) {
        background: #FAFCFE;
    }

    tbody tr:hover {
        background: #F2F8FD;
    }

    tbody td:last-child {
        color: #D50000;
        font-size: 22px;
        font-weight: bold;
    }

    /* ===========================
    RESPONSIVE
    =========================== */

    @media(max-width:900px) {
        body {
            height: auto;
        }

        .contenedor {
            width: 96%;
            height: auto;
            padding: 15px 0;
        }

        .logo {
            width: 170px;
        }

        header h1 {
            font-size: 30px;
        }

        header p {
            font-size: 16px;
        }

        .busqueda {
            margin-bottom: 15px;
        }

        .icono {
            font-size: 20px;
            padding: 0 12px;
        }

        .busqueda input {
            height: 50px;
            font-size: 18px;
        }

        .cabecera-producto {
            padding: 14px 18px;
        }

        .cabecera-producto h2 {
            font-size: 24px;
        }

        .cabecera-producto span {
            font-size: 15px;
        }

        thead th {
            font-size: 17px;
            padding: 8px;
        }

        tbody td {
            font-size: 17px;
            padding: 8px;
        }

        tbody td:last-child {
            font-size: 18px;
        }
    }
</style>

<body>
    <div class="contenedor">
        <header>
            <img src="https://companiasysven.com/logos/20605431870/logo.jpg" class="logo">
            <h1>Consulta de Precios</h1>
            <p>Escanee el código de barras del producto</p>
        </header>
        <section class="busqueda">
            <span class="icono">🔍</span>
            <input
                type="text"
                placeholder="Escanee el código de barras..."
                autofocus id="txtbuscar">
        </section>
        <section class="producto">
            <div class="cabecera-producto">
                <h2 id="lblproducto">PRODUCTO: </h2>
                <span id="lblcodigobarras">CÓDIGO DE BARRAS: </span>
            </div>
            <table id="tbldetalle">
                <thead>
                    <tr>
                        <th>Presentación</th>
                        <th>Cantidad Equivalente</th>
                        <th>Precio</th>
                    </tr>
                </thead>
                <tbody id="tbodydetalle">

                </tbody>
            </table>
        </section>
    </div>
</body>

</html>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="/plugins/jquery/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
    const inputCodigo = document.getElementById("txtbuscar");
    inputCodigo.addEventListener("keydown", function(e) {
        if (e.key === "Enter") {
            e.preventDefault();
            const codigo = this.value.trim();
            if (codigo !== "") {
                buscarproducto();
            }
            this.value = "";
        }
    });

    function buscarproducto() {
        var abuscar = document.getElementById("txtbuscar").value;
        if (abuscar.length == 0) {
            toastr.info("Ingrese parametro a buscar", 'Mensaje del Sistema')
            return;
        }
        axios.get('/productos/listapreciosdecliente', {
            "params": {
                "cbuscar": abuscar,
            }
        }).then(function(respuesta) {
            $("#tbldetalle tbody").empty();
            datosproducto = respuesta.data.listado.lista.items;
            // console.log(datosproducto)
            datosproducto.forEach(function(presentacion) {
                agregarfilaatabla(presentacion);
                $("#lblproducto").text('PRODUCTO: ' + presentacion.descri);
                $("#lblcodigobarras").text('CÓDIGO DE BARRAS: ' + presentacion.prod_cod1)

            });
            toastr.success('Se cargó satisfactoriamente ', 'Mensaje del sistema')
        }).catch(function(error) {
            console.log(error);
            toastr.error('Error al cargar el listado ' + error, 'Mensaje del sistema')
        });
    }

    function agregarfilaatabla(producto) {
        const tbody = document.getElementById("tbodydetalle");
        const fila = document.createElement("tr");
        fila.innerHTML = `
                <td>` + producto.pres_desc + `</td>
                <td>` + producto.epta_cant + `</td>
                <td>S/ ` + producto.epta_prec + `</td>
                `;
        tbody.appendChild(fila);
    }
</script>