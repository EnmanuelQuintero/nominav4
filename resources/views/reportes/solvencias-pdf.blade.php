<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

<style>

body {

    font-family: DejaVu Sans, sans-serif;
    font-size: 9px;
    color:#222;
    margin:10px;

}


/* PAGINA */

.pagina {

    width:100%;
    border-collapse:separate;
    border-spacing:8px;

}



/* CELDA */

.celda {

    width:33%;
    vertical-align:top;

}


/* COMPROBANTE */

.solvencia {

    border:1px solid #777;
    border-radius:5px;
    padding:8px;
    background:#fff;

}


/* HEADER */

.header {

    text-align:center;
    border-bottom:1px solid #999;
    padding-bottom:5px;
    margin-bottom:8px;

}


.titulo {

    font-size:12px;
    font-weight:bold;
    color:#1f2937;

}


.subtitulo {

    font-size:9px;
    color:#555;

}



/* DATOS EMPLEADO */

.info {

    background:#f5f5f5;
    border:1px solid #ddd;
    padding:5px;
    margin-bottom:8px;
    line-height:14px;

}



.info strong {

    font-size:10px;

}



/* TABLAS */

.detalle {

    width:100%;
    border-collapse:collapse;
    margin-bottom:7px;

}



.detalle th {

    background:#374151;
    color:white;
    text-align:center;
    padding:4px;
    font-size:9px;

}



.detalle td {

    border:1px solid #ccc;
    padding:4px;
    font-size:8px;

}



.detalle td:last-child {

    text-align:right;

}



/* TOTALES */

.total td {

    font-weight:bold;
    background:#f3f4f6;

}



/* NETO */

.neto th {

    background:#16a34a;
    color:white;
    font-size:10px;

}


.neto td {

    font-size:11px;
    font-weight:bold;

}



/* SALTO PAGINA */

.page-break {

    page-break-after:always;

}


</style>


</head>


<body>


@foreach($detalles->chunk(6) as $grupo)


<table class="pagina">


@foreach($grupo->chunk(3) as $fila)

<tr>


@foreach($fila as $d)


<td class="celda">


<div class="solvencia">



<div class="header">

<div class="titulo">
COMPROBANTE DE PAGO
</div>

<div class="subtitulo">
Nómina
</div>

</div>




<div class="info">

<strong>
{{ $d->nombre }}
</strong>

<br>

{{ $d->cargo }}

<br>

No: {{ $d->numero_empleado }}

</div>




<table class="detalle">


<tr>

<th colspan="2">
INGRESOS
</th>

</tr>


<tr>

<td>
Salario
</td>

<td>
{{ number_format($d->salario_quincenal,2) }}
</td>

</tr>



<tr>

<td>
Horas Extra
</td>

<td>
{{ number_format($d->horas_extra_monto,2) }}
</td>

</tr>



<tr>

<td>
Subsidio
</td>

<td>
{{ number_format($d->subsidio_monto,2) }}
</td>

</tr>



<tr class="total">

<td>
Total Devengado
</td>

<td>
{{ number_format($d->total_devengado,2) }}
</td>

</tr>


</table>





<table class="detalle">


<tr>

<th colspan="2">
DEDUCCIONES
</th>

</tr>



<tr>

<td>
INSS
</td>

<td>
{{ number_format($d->detalle_inss,2) }}
</td>

</tr>



<tr>

<td>
IR
</td>

<td>
{{ number_format($d->detalle_ir,2) }}
</td>

</tr>



@foreach($d->deducciones as $ded)


<tr>

<td>
{{ $ded->nombre }}
</td>

<td>
{{ number_format($ded->monto_aplicado,2) }}
</td>

</tr>


@endforeach




<tr class="total">

<td>
Total Deducciones
</td>

<td>
{{ number_format($d->total_deduccion,2) }}
</td>

</tr>



</table>





<table class="detalle neto">


<tr>

<th>
NETO A PAGAR
</th>


<th>
{{ number_format($d->neto_pagar,2) }}
</th>


</tr>


</table>



</div>


</td>



@endforeach




@if($fila->count() < 3)

@for($i=$fila->count(); $i < 3; $i++)

<td></td>

@endfor

@endif



</tr>


@endforeach


</table>



<div class="page-break"></div>



@endforeach


</body>
</html>