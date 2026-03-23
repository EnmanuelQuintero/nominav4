{{-- SIDEBAR --}}
<div class="bg-dark text-white p-3 d-flex flex-column sidebar" style="width:250px; min-height:100vh;">

    {{-- Usuario --}}
    <div class="text-center mb-4">

        <div class="d-flex justify-content-center">
            <img src="https://static.vecteezy.com/system/resources/thumbnails/008/442/086/small/illustration-of-human-icon-user-symbol-icon-modern-design-on-blank-background-free-vector.jpg"
                 class="rounded-circle shadow"
                 width="90"
                 height="90"
                 style="object-fit: cover;">
        </div>

        <h6 class="mt-3 mb-0">
            {{ auth()->user()->nombre ?? 'Usuario' }}
        </h6>

        <small class="text-secondary">
            Usuario Activo
        </small>

    </div>

    <hr class="text-secondary">

    {{-- MENÚ PRINCIPAL --}}
    <ul class="nav nav-pills flex-column flex-grow-1 sidebar-menu">

        <li class="nav-item">
            <a href="{{ route('dashboard') }}"
               class="nav-link text-white {{ request()->routeIs('dashboard') ? 'active' : '' }}">
               <i class="bi bi-speedometer2 me-2"></i>
               Dashboard
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('empleados.index') }}"
               class="nav-link text-white {{ request()->routeIs('empleados.*') ? 'active' : '' }}">
               <i class="bi bi-people me-2"></i>
               Empleados
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('nominas.index') }}" class="nav-link text-white">
               <i class="bi bi-cash-stack me-2"></i>
               Nóminas
            </a>
        </li>

        <li class="nav-item">
            <a href="#" class="nav-link text-white">
               <i class="bi bi-bar-chart me-2"></i>
               Reportes
            </a>
        </li>

    </ul>

    {{-- CONFIGURACIONES --}}
    <div class="mt-3">

        <hr class="text-secondary">

        <small class="text-secondary d-block mb-2">
            Configuración
        </small>

        <ul class="nav nav-pills flex-column sidebar-menu">

            <li class="nav-item">
                <a href="{{ route('areas.index') }}"
                class="nav-link text-white {{ request()->routeIs('areas.*') ? 'active' : '' }}">
                    <i class="bi bi-diagram-3 me-2"></i>
                    Áreas
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('cargos.index') }}"
                class="nav-link text-white {{ request()->routeIs('cargos.*') ? 'active' : '' }}">
                    <i class="bi bi-briefcase me-2"></i>
                    Cargos
                </a>
            </li>

            <li class="nav-item">
                <a href="#" class="nav-link text-white">
                    <i class="bi bi-person-gear me-2"></i>
                    Usuarios
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('config.index') }}" class="nav-link text-white">
                    <i class="bi bi-person-gear me-2"></i>
                    Configuracion
                </a>
            </li>

        </ul>

    </div>

    {{-- Cerrar sesión --}}
    <div class="mt-3">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="btn btn-danger w-100">
                <i class="bi bi-box-arrow-right me-2"></i>
                Cerrar sesión
            </button>
        </form>
    </div>

</div>