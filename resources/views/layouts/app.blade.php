<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Sistema Nómina')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">


<style>
    
/* FOTO */
.foto-tabla {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    object-fit: cover;
}

/* FILA ÁREA */
.fila-area {
    background: #f8f9fa;
    font-weight: bold;
}

/* FILA EMPLEADO */
.fila-empleado {
    transition: all 0.2s ease;
}

.fila-empleado:hover {
    background: #f5faff;
}

/* ESTADO */
.estado-pill {
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 12px;
}

.estado-pill.activo {
    background: #e6f9ed;
    color: #28a745;
}

.estado-pill.inactivo {
    background: #fdecea;
    color: #dc3545;
}

/* PROGRESS */
.barra-asistencia {
    height: 6px;
    border-radius: 10px;
}

/* BOTONES */
.accion-btn {
    border-radius: 50%;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.accion-btn:hover {
    transform: scale(1.1);
}
</style>


    <style>
        .dia:hover{
            background-color: #e9ecef;
        }


        /* ================================
        CARD BASE
        ================================ */
        .card-empleado-pro {
            height: 340px;
            border-radius: 20px;
            transition: all 0.25s ease;
            overflow: hidden;
        }

        /* HOVER */
        .card-empleado-pro:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 15px 35px rgba(0,0,0,0.15);
        }

        /* ================================
        IMAGEN
        ================================ */
        /* CONTENEDOR FIJO Y PERFECTO */
        .contenedor-foto {
            width: 90px;
            height: 90px;
            position: relative;
        }

        /* IMAGEN 100% REDONDA REAL */
        .img-empleado {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50% !important; /* 🔥 clave */
            display: block;
        }

        /* INDICADOR BIEN POSICIONADO */
        .estado-dot {
            width: 14px;
            height: 14px;
            border-radius: 50%;
            position: absolute;
            bottom: 2px;
            right: 2px;
            border: 2px solid #fff;
        }

        .estado-dot.activo {
            background: #28a745;
            box-shadow: 0 0 6px #28a745;
        }

        .estado-dot.inactivo {
            background: #dc3545;
        }
        /* ================================
        ESTADO DOT (tipo WhatsApp)
        ================================ */
        .estado-dot {
            width: 14px;
            height: 14px;
            border-radius: 50%;
            position: absolute;
            bottom: 5px;
            right: 35%;
            border: 2px solid #fff;
        }

        .estado-dot.activo {
            background: #28a745;
            box-shadow: 0 0 8px #28a745;
        }

        .estado-dot.inactivo {
            background: #dc3545;
        }

        /* ================================
        TEXTO TRUNCADO
        ================================ */
        .text-truncate {
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
        }

        /* ================================
        PROGRESS BAR SUAVE
        ================================ */
        .progress {
            background: #f1f1f1;
            border-radius: 10px;
        }

        /* ================================
        BOTONES
        ================================ */
        .btn-light {
            background: #f8f9fa;
            border: none;
        }

        .btn-light:hover {
            background: #e2e6ea;
        }

    </style>



<style>

/* Contenedor principal */
#modalDias .modal-content {
    border-radius: 20px;
    border: none;
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    overflow: hidden;
}

/* Header */
#modalDias .modal-header {
    border-bottom: none;
    padding: 20px 25px;
    background: linear-gradient(135deg, #0d6efd, #6ea8fe);
    color: white;
    border-top-left-radius: 20px;
    border-top-right-radius: 20px;
}

/* Título */
#modalDias .modal-title {
    font-weight: 600;
}

/* Botón cerrar */
#modalDias .btn-close {
    filter: invert(1);
    opacity: 0.8;
}

/* Body */
#modalDias .modal-body {
    padding: 25px;
    background-color: #f8f9fa;
}

/* Footer */
#modalDias .modal-footer {
    border-top: none;
    padding: 15px 25px;
    background-color: #f8f9fa;
    border-bottom-left-radius: 20px;
    border-bottom-right-radius: 20px;
}

/* Animación suave */
#modalDias .modal-content {
    animation: modalFade 0.25s ease;
}

@keyframes modalFade {
    from {
        transform: translateY(20px) scale(0.98);
        opacity: 0;
    }
    to {
        transform: translateY(0) scale(1);
        opacity: 1;
    }
}

</style>


<style>

/* Botones de navegación */
.btn-nav-calendario {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    border: none;
    background: white;
    box-shadow: 0 4px 10px rgba(0,0,0,0.08);
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.25s ease;
    font-size: 16px;
    color: #0d6efd;
}

/* Hover */
.btn-nav-calendario:hover {
    background: #0d6efd;
    color: white;
    transform: scale(1.1);
}

/* Click */
.btn-nav-calendario:active {
    transform: scale(0.95);
}

/* Título */
.titulo-calendario {
    font-size: 16px;
    font-weight: 600;
    color: #343a40;
}

</style>

<style>

.horas {
    font-size: 11px;
    background: #000;
    color: #fff;
    border-radius: 6px;
    padding: 2px 6px;
    position: absolute;
    bottom: 4px;
    left: 4px;
    opacity: 0.85;
}



.dia {
    position: relative;
    height: 70px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    padding: 6px;
}

/* número arriba */
.numero {
    font-size: 14px;
    font-weight: 600;
    text-align: left;
}

/* icono abajo */
.icono {
    font-size: 14px;
    text-align: right;
}




.calendario-grid {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 2px;
    background-color: #dee2e6;
    border-radius: 12px;
    overflow: hidden;
}

/* Día */
.calendario-grid .dia {
    background-color: #fff;
    padding: 14px 0;
    font-weight: 500;
    transition: all 0.2s ease;
    position: relative;
}

/* Hover suave */
.calendario-grid .dia:hover {
    background-color: #f1f3f5;
}

/* Seleccionado */
.calendario-grid .dia.seleccionado {
    background-color: #e7f1ff;
    box-shadow: inset 0 0 0 2px #0d6efd;
}

/* Colores SIN romper el grid */
.calendario-grid .trabajado {
    background-color: #d1f7dc;
}

.calendario-grid .vacaciones {
    background-color: #fff3cd;
}

.calendario-grid .compensado {
    background-color: #d6e4ff;
}

.calendario-grid .no_trabajado {
    background-color: #f8d7da;
}

/* Número del día */
.calendario-grid .dia span {
    position: relative;
    z-index: 2;
}

/* Animación suave */
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.calendario-grid .dia {
    animation: fadeIn 0.2s ease;
}

.calendario-grid {
    box-shadow: 0 8px 20px rgba(0,0,0,0.08);
}
</style>



</head>
<body>

<div class="d-flex">

    {{-- SIDEBAR --}}
    @include("layouts.components.sidebar")

    {{-- CONTENIDO --}}
    <div class="flex-grow-1 p-4 bg-light">
        @yield('content')
    </div>

</div>

</body>
</html>