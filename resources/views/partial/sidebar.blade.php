<div class="sidebar">
    <nav class="sidebar-nav">
        <ul class="nav">
            <li class="nav-item">
                {{-- <a class="nav-link active" href="main.html"><i class="icon-speedometer"></i> Escritorio</a> --}}
                <router-link tag="a" to="/" class="nav-link active">
                    <i class="icon-speedometer"></i> Escritorio
                </router-link>
            </li>
            <li class="nav-title">
                Mantenimiento
            </li>
            @if (auth::user()->hasrole(['Administrador', 'Almacenero']))
            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-bag"></i> Almacén</a>
                <ul class="nav-dropdown-items">
                    <li class="nav-item">
                        {{-- <a class="nav-link" href="#"><i class="icon-bag"></i> Categorías</a> --}}
                        <router-link to="/categoria" class="nav-link">
                            <i class="icon-bag"></i> Categorías
                        </router-link>

                    </li>
                    <li class="nav-item">
                        {{-- <a class="nav-link" href="#"><i class="icon-bag"></i> Artículos</a> --}}
                        <router-link tag="a" to="/articulo" class="nav-link">
                            <i class="icon-bag"></i> Artículos
                        </router-link>
                    </li>
                </ul>
            </li>
            @endif
            @if (auth::user()->hasrole(['Administrador', 'Almacenero']))
            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-wallet"></i> Compras</a>
                <ul class="nav-dropdown-items">
                    <li class="nav-item">
                        {{-- <a class="nav-link" href="i#"><i class="icon-wallet"></i> Ingresos</a> --}}
                        <router-link tag="a" to="/ingreso" class="nav-link">
                            <i class="icon-wallet"></i> Ingresos
                        </router-link>
                    </li>
                    <li class="nav-item">
                        {{-- <a class="nav-link" href="#"><i class="icon-notebook"></i> Proveedores</a> --}}
                        <router-link tag="a" to="/proveedor" class="nav-link">
                            <i class="icon-notebook"></i> Proveedores
                        </router-link>
                    </li>
                </ul>
            </li>
            @endif
            @if (auth::user()->hasrole(['Administrador', 'Vendedor']))
            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-basket"></i> Ventas</a>
                <ul class="nav-dropdown-items">
                    <li class="nav-item">
                        <router-link to="/venta" class="nav-link">
                            <i class="icon-basket-loaded"></i> Ventas
                        </router-link>
                        {{-- <a class="nav-link" href="i#"><i class="icon-basket-loaded"></i> Ventas</a> --}}
                    </li>
                    <li class="nav-item">
                        <router-link to="/cliente" class="nav-link">
                            <i class="icon-notebook"></i> Clientes
                        </router-link>
                        {{-- <a class="nav-link" href="#"><i class="icon-notebook"></i> Clientes</a> --}}
                    </li>
                </ul>
            </li>
            @endif
            @if (auth::user()->hasrole(['Administrador']))
            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-people"></i> Acceso</a>
                <ul class="nav-dropdown-items">
                    <li class="nav-item">
                        {{-- <a class="nav-link" href="i#"><i class="icon-user"></i> Usuarios</a> --}}
                        <router-link to="/user" class="nav-link">
                            <i class="icon-user"></i> Usuarios
                        </router-link>
                    </li>
                    <li class="nav-item">
                        {{-- <a class="nav-link" href="#"><i class="icon-user-following"></i> Roles</a> --}}
                        <router-link to="/rol" class="nav-link">
                            <i class="icon-user-following"></i> Roles
                        </router-link>
                    </li>
                </ul>
            </li>
            @endif

            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-pie-chart"></i> Reportes</a>
                <ul class="nav-dropdown-items">
                    @if (auth::user()->hasrole(['Administrador', 'Almacenero']))
                    <li class="nav-item">
                        {{-- <a class="nav-link" href="i#"><i class="icon-chart"></i> Reporte Ingresos</a> --}}
                        <router-link to="/consulta/ingreso" class="nav-link">
                            <i class="icon-chart"></i> Reporte Ingresos
                        </router-link>
                    </li>
                    @endif
                    @if (auth::user()->hasrole(['Administrador', 'Vendedor']))
                    <li class="nav-item">
                        {{-- <a class="nav-link" href="#"><i class="icon-chart"></i> Reporte Ventas</a> --}}
                        <router-link to="/consulta/venta" class="nav-link">
                            <i class="icon-chart"></i> Reporte Ventas
                        </router-link>
                    </li>
                    @endif
                </ul>
            </li>
            @if (auth::user()->hasrole(['Administrador', 'Vendedor']))
            <li class="nav-item">
                <a class="nav-link" href="main.html"><i class="icon-book-open"></i> Ayuda <span class="badge badge-danger">PDF</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="main.html"><i class="icon-info"></i> Acerca de...<span class="badge badge-info">IT</span></a>
            </li>
            @endif
        </ul>
    </nav>
    <button class="sidebar-minimizer brand-minimizer" type="button"></button>
</div>
