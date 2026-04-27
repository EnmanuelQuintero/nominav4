<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
body {
    font-family: DejaVu Sans;
    font-size: 7px;
    margin: 5px;
    color: #111;
}

/* 🔥 CONTENEDOR */
.contenedor {
    width: 100%;
}

/* 🔹 CADA SOLVENCIA */
.solvencia {
    display: inline-block;
    width: 32%; /* 3 columnas */
    margin: 0.5%;
    padding: 4px;
    border: 1px solid #bbb;
    vertical-align: top;
    box-sizing: border-box;
    page-break-inside: avoid;
}

/* 🔹 HEADER */
.header {
    text-align: center;
    margin-bottom: 2px;
}

.titulo {
    font-size: 8px;
    font-weight: bold;
}

.subtitulo {
    font-size: 6px;
    color: #666;
}

/* 🔹 INFO */
.info {
    margin-bottom: 2px;
    line-height: 1.1;
}

/* 🔹 TABLAS */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 2px;
}

th, td {
    border: 1px solid #ccc;
    padding: 2px;
    font-size: 7px;
}

th {
    background: #eee;
}

.total {
    font-weight: bold;
}

/* 🔥 SALTO DE PAGINA */
.page-break {
    page-break-after: always;
}
    </style>
</head>
<body>

@foreach($detalles->chunk(9) as $grupo)

<div class="contenedor">

    @foreach($grupo as $d)

    <div class="solvencia">

        <div class="header">
            <div class="titulo">COMPROBANTE DE PAGO</div>
            <div class="subtitulo">Nómina</div>
        </div>

        <div class="info">
            <div><strong>{{ $d->nombre }}</strong></div>
            <div>{{ $d->cargo }}</div>
            <div>No: {{ $d->numero_empleado }}</div>
        </div>

        <table>
            <tr><th colspan="2">Ing.</th></tr>
            <tr><td>Sal</td><td>{{ number_format($d->salario_quincenal,2) }}</td></tr>
            <tr><td>Ext</td><td>{{ number_format($d->horas_extra_monto,2) }}</td></tr>
            <tr><td>Sub</td><td>{{ number_format($d->subsidio_monto,2) }}</td></tr>
            <tr class="total">
                <td>Tot</td>
                <td>{{ number_format($d->total_devengado,2) }}</td>
            </tr>
        </table>

        <table>
            <tr><th colspan="2">Ded.</th></tr>
            <tr><td>INSS</td><td>{{ number_format($d->detalle_inss,2) }}</td></tr>
            <tr><td>IR</td><td>{{ number_format($d->detalle_ir,2) }}</td></tr>
            <tr class="total">
                <td>Tot</td>
                <td>{{ number_format($d->total_deduccion,2) }}</td>
            </tr>
        </table>

        <table>
            <tr>
                <th>Neto</th>
                <th>{{ number_format($d->neto_pagar,2) }}</th>
            </tr>
        </table>

    </div>

    @endforeach

</div>

<div class="page-break"></div>

@endforeach
</body>
</html>