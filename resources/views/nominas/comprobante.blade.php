<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Comprobante de Pago</title>

    <style>
        body{
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #2c3e50;
        }

        .container{
            padding: 20px;
        }

        .header{
            text-align: center;
            margin-bottom: 20px;
        }

        .title{
            font-size: 18px;
            font-weight: bold;
        }

        .subtitle{
            font-size: 12px;
            color: #7f8c8d;
        }

        .box{
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 10px;
            margin-bottom: 12px;
        }

        .box-title{
            font-weight: bold;
            margin-bottom: 5px;
            font-size: 13px;
        }

        table{
            width: 100%;
            border-collapse: collapse;
        }

        td{
            padding: 4px 0;
        }

        .right{
            text-align: right;
        }

        .total{
            font-weight: bold;
            border-top: 1px solid #ccc;
            padding-top: 5px;
        }

        .neto{
            font-size: 16px;
            font-weight: bold;
            color: #27ae60;
            text-align: right;
        }

        .footer{
            margin-top: 25px;
            text-align: center;
            font-size: 10px;
            color: #999;
        }

    </style>
</head>
<body>

<div class="container">

    {{-- 🔷 HEADER --}}
    <div class="header">
        <div class="title">Comprobante de Pago</div>
        <div class="subtitle">
            {{ \Carbon\Carbon::parse($detalle->nomina->fecha_inicio)->format('d/m/Y') }}
            -
            {{ \Carbon\Carbon::parse($detalle->nomina->fecha_fin)->format('d/m/Y') }}
        </div>
    </div>

    {{-- 👤 DATOS EMPLEADO --}}
    <div class="box">
        <div class="box-title">Datos del Empleado</div>
        <table>
            <tr>
                <td><strong>Nombre:</strong></td>
                <td>{{ $detalle->nombre }}</td>
            </tr>
            <tr>
                <td><strong>Cargo:</strong></td>
                <td>{{ $detalle->cargo }}</td>
            </tr>
            <tr>
                <td><strong>N° Empleado:</strong></td>
                <td>{{ $detalle->numero_empleado }}</td>
            </tr>
        </table>
    </div>

    {{-- 💰 INGRESOS --}}
    <div class="box">
        <div class="box-title">Ingresos</div>
        <table>
            <tr>
                <td>Salario Quincenal</td>
                <td class="right">C$ {{ number_format($detalle->salario_quincenal,2) }}</td>
            </tr>
            <tr>
                <td>Horas Extra</td>
                <td class="right">C$ {{ number_format($detalle->horas_extra_monto,2) }}</td>
            </tr>
            <tr>
                <td>Subsidio</td>
                <td class="right">C$ {{ number_format($detalle->subsidio_monto,2) }}</td>
            </tr>
            <tr>
                <td>Feriado</td>
                <td class="right">C$ {{ number_format($detalle->feriado,2) }}</td>
            </tr>
            <tr class="total">
                <td>Total Devengado</td>
                <td class="right">C$ {{ number_format($detalle->total_devengado,2) }}</td>
            </tr>
        </table>
    </div>

    {{-- ❌ DEDUCCIONES --}}
    <div class="box">
        <div class="box-title">Deducciones</div>
        <table>
            <tr>
                <td>INSS</td>
                <td class="right">C$ {{ number_format($detalle->detalle_inss,2) }}</td>
            </tr>
            <tr>
                <td>IR</td>
                <td class="right">C$ {{ number_format($detalle->detalle_ir,2) }}</td>
            </tr>
            <tr class="total">
                <td>Total Deducciones</td>
                <td class="right">C$ {{ number_format($detalle->total_deduccion,2) }}</td>
            </tr>
        </table>
    </div>

    {{-- 💵 NETO --}}
    <div class="box">
        <div class="box-title">Resultado</div>
        <div class="neto">
            Neto a Pagar: C$ {{ number_format($detalle->neto_pagar,2) }}
        </div>
    </div>

    {{-- FOOTER --}}
    <div class="footer">
        Prueba banano
        
    </div>

</div>

</body>
</html>