<nav class="col-md-2 d-none d-md-block bg-light sidebar">
    <div class="sidebar-sticky">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" target="_blank" rel="noopener noreferrer" href="{{ route('salop.fop2') }}">
                    <span data-feather="sliders"></span>
                    Painel
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
            
            <li class="nav-item">
                <a class="nav-link" target="_blank" rel="noopener noreferrer" href="{{ route('salop.change_password') }}">
                    <span data-feather="settings"></span>
                    Mudar Password
                </a>
            </li>
        </ul>

        @if (Auth::user()->permissions->contains('permission',4))
        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
            <span>Gestão Operacional Integrada</span>
        </h6>
        <ul class="nav flex-column mb-2">
            <li class="nav-item">
                <a class="nav-link" target="_blank" rel="noopener noreferrer" href="{{ route('theaters_of_operations.index') }}">
                    <span data-feather="map"></span>
                    Abrir
                </a>
            </li>
        </ul>
        @endif

        @if (Auth::user()->permissions->contains('permission',1))
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
        @endif

        @if (Auth::user()->permissions->contains('permission',2))
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
        @endif
    </div>
</nav>