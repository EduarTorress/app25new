<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <title>Pantalla Cliente</title>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <style>

        body{
            margin:0;
            padding:0;
            background:#111;
            color:white;
            font-family:Arial;
        }

        .contenedor{
            padding:30px;
        }

        .titulo{
            font-size:40px;
            margin-bottom:20px;
        }

        .producto{
            font-size:30px;
            margin-bottom:10px;
        }

        .total{
            font-size:60px;
            color:#00ff90;
            margin-top:40px;
        }

        .vuelto{
            font-size:40px;
            color:yellow;
            margin-top:20px;
        }

    </style>

</head>

<body>

<div class="contenedor">

    <div class="titulo">
        YAQUAMARKET
    </div>

    <div id="productos"></div>

    <div class="total">
        TOTAL: S/ <span id="total">0.00</span>
    </div>

    <div class="vuelto">
        VUELTO: S/ <span id="vuelto">0.00</span>
    </div>

</div>

<script>

window.addEventListener("message", function(event){

    let data = event.data;

    $("#total").html(data.total);

    $("#vuelto").html(data.vuelto);

    let html = "";

    data.productos.forEach(function(p){

        html += `
            <div class="producto">
                ${p.descripcion}
                x${p.cantidad}
            </div>
        `;
    });

    $("#productos").html(html);

});

</script>

</body>

</html>
