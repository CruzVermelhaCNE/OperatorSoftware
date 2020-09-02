<nav class="col-md-2 d-none d-md-block bg-light sidebar">
    <div class="sidebar-sticky">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" target="_blank" rel="noopener noreferrer" href="{{ route('salop.index') }}">
                    <span data-feather="sliders"></span>
                    Inicio
                </a>
            </li>
            @can('accessSALOP')
            <li class="nav-item">
                <a class="nav-link" target="_blank" rel="noopener noreferrer" href="{{ route('salop.fop2') }}">
                    <span data-feather="sliders"></span>
                    Sistema de Telefones
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" target="_blank" rel="noopener noreferrer" href="{{ route('salop.missed_calls') }}">
                    <span data-feather="phone-missed"></span>
                    Chamadas Perdidas
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" target="_blank" rel="noopener noreferrer" href="{{ route('salop.callbacks') }}">
                    <span data-feather="phone-call"></span>
                    Chamadas Por Devolver
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" target="_blank" rel="noopener noreferrer" href="{{ route('salop.door_opener') }}">
                    <span data-feather="video"></span>
                    Video Porteiro
                </a>
            </li>
            @endcan
        </ul>

        @can('accessGOI')
        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
            <span>Gestão Operacional Integrada</span>
        </h6>
        <ul class="nav flex-column mb-2">
            <li class="nav-item">
                <a class="nav-link" target="_blank" rel="noopener noreferrer" href="{{ route('goi.index') }}">
                    <span data-feather="map"></span>
                    Abrir
                </a>
            </li>
        </ul>
        @endcan

        @can('isManager')
        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
            <span>Gestão</span>
        </h6>
        <ul class="nav flex-column mb-2">
            <li class="nav-item">
                <a class="nav-link" target="_blank" rel="noopener noreferrer" href="{{ route('salop.users') }}">
                    <span data-feather="user"></span>
                    Utilizadores
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" target="_blank" rel="noopener noreferrer" href="{{ route('salop.reports') }}">
                    <span data-feather="bar-chart-2"></span>
                    Relatórios
                </a>
            </li>
        </ul>
        @endcan

        @can('isAdmin')
        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
            <span>Administração</span>
        </h6>
        <ul class="nav flex-column mb-2">
            <li class="nav-item">
                <a class="nav-link" target="_blank" rel="noopener noreferrer" href="{{ route('salop.extensions') }}">
                    <span data-feather="phone"></span>
                    Extensões
                </a>
            </li>
        </ul>
        @endcan
    </div>
</nav>