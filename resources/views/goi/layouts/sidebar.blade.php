<nav class="d-none d-md-block bg-dark sidebar">
    <div class="sidebar-sticky">
        <ul class="nav flex-column">
            <li class="nav-item" title="Mapa">
                <a class="nav-link" href="{{ route('goi.map') }}">
                    <span data-feather="map"></span>
                </a>
            </li>
            <li class="nav-item" title="Teatros de Operações">
                <a class="nav-link" href="{{ route('goi.list') }}">
                    <span data-feather="alert-circle"></span>
                </a>
            </li>
            <li class="nav-item" title="Fitas de Tempo">
                <a class="nav-link" href="{{ route('goi.timetape.index') }}">
                    <span data-feather="message-square"></span>
                </a>
            </li>
            <!--<li class="nav-item" title="Ocorrências">
                <a class="nav-link" href="{{ route('goi.index') }}">
                    <span data-feather="bell"></span>
                </a>
            </li>
            <li class="nav-item" title="Meios">
                <a class="nav-link" href="{{ route('goi.index') }}">
                    <span data-feather="users"></span>
                </a>
            </li>
            <li class="nav-item" title="Administração">
                <a class="nav-link" href="{{ route('goi.index') }}">
                    <span data-feather="terminal"></span>
                </a>
            </li>-->
            <li class="nav-item bottom" title="SALOP">
                <a class="nav-link" target="_blank" rel="noopener noreferrer" href="{{ route('salop.index') }}">
                    <span data-feather="arrow-left"></span>
                </a>
            </li>
        </ul>
    </div>
</nav>