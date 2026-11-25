@can('admin.packages.index')
    <li class="treeview {{ (Request::is('admin/packages*')||Request::is('admin/categories*')||Request::is('admin/products*')) ? 'menu-open active':''}}">
        <a href="#">
            <i class="fa fa-suitcase"></i> <span>Paquetes</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            @can('admin.packageGroups.index')
                <li class="{{ Request::is('admin/packageGroups*') ? 'active' : '' }}">
                    <a href="{!! route('admin.packageGroups.index') !!}"><i
                                class="fa fa-object-group"></i><span>Grupos</span></a>
                </li>
            @endcan
            @can('admin.packages.index')
                <li class="{{ Request::is('admin/packages*') ? 'active' : '' }}">
                    <a href="{!! route('admin.packages.index') !!}"><i class="fa fa-suitcase"></i><span>Paquetes</span></a>
                </li>
            @endcan
            @can('admin.categories.index')
                <li class="{{ Request::is('admin/categories*') ? 'active' : '' }}">
                    <a href="{!! route('admin.categories.index') !!}"><i class="fa fa-tags"></i><span>Categorías</span></a>
                </li>
            @endcan
            @can('admin.products.index')
                <li class="{{ Request::is('admin/products*') ? 'active' : '' }}">
                    <a href="{!! route('admin.products.index') !!}"><i
                                class="fa fa-train"></i><span>Productos</span></a>
                </li>
            @endcan
        </ul>
    </li>
@endcan
@can('idg.media_invoices.index')
    <li class="treeview {{ (Request::is('admin/idg*') ? 'menu-open active':'')}}">
        <a href="#">
            <i class="fa fa-suitcase"></i> <span>Armatrip IN</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            @can('idg.categories.index')
                <li class="{{ Request::is('admin/idg/categories*') ? 'active' : '' }}">
                    <a href="{!! route('idg.categories.index') !!}"><i
                                class="fa fa-tags"></i><span>Categorías</span></a>
                </li>
            @endcan
            @can('idg.products.index')
                <li class="{{ Request::is('admin/idg/products*') ? 'active' : '' }}">
                    <a href="{!! route('idg.products.index') !!}"><i
                                class="fa fa-train"></i><span>Productos</span></a>
                </li>
            @endcan
            @can('idg.media_invoices.index')
                <li class="{{ Request::is('admin/idg/media_invoices*') ? 'active' : '' }}">
                    <a href="{!! route('idg.media_invoices.index') !!}"><i class="fa fa-shopping-bag"></i>&nbsp;<span>Pedidos graficos</span></a>
                </li>
            @endcan
        </ul>
    </li>
@endcan
@can('admin.stores.index')
    <li class="{{ Request::is('admin/stores*') ? 'active' : '' }}">
        <a href="{!! route('admin.stores.index') !!}"><i class="fa fa-building"></i>&nbsp;<span>Empresas</span></a>
    </li>
@endcan

@can('admin.administrators.index')
    <li class="{{ Request::is('admin/administrators*') ? 'active' : '' }}">
        <a href="{!! route('admin.administrators.index') !!}"><i
                    class="fa fa-users"></i>&nbsp;<span>Usuarios (admin)</span></a>
    </li>
@endcan

@can('admin.users.index')
    <li class="{{ Request::is('admin/users*') ? 'active' : '' }}">
        <a href="{!! route('admin.users.index') !!}"><i class="fa fa-user"></i>&nbsp;<span>Usuarios (web)</span></a>
    </li>
@endcan

@can('admin.orders.index')
    <li class="{{ Request::is('admin/orders*') ? 'active' : '' }}">
        <a href="{!! route('admin.orders.index') !!}"><i class="fa fa-table"></i>&nbsp;<span>Reservas</span></a>
    </li>
@endcan
@can('admin.fam_tours.index')
    <li class="{{ Request::is('admin/fam_tours*') ? 'active' : '' }}">
        <a href="{!! route('admin.fam_tours.index') !!}"><i class="fa fa-envelope-o"></i>&nbsp;<span>Fam Tour (Emails)</span></a>
    </li>
@endcan
@can('admin.coupons.index')
    <li class="treeview {{ Request::is('admin/coupons*') ? 'menu-open active':''}}">
        <a href="#">
            <i class="fa fa-tags"></i> <span>Cupones</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            @can('admin.coupons.index')
                <li class="{{ Request::is('admin/coupons*') ? 'active' : '' }}">
                    @can('admin.coupons.index')<a href="{!! route('admin.coupons.index') !!}"><i class="fa fa-tags"></i><span>Cupónes</span></a>@endcan
                </li>
            @endcan
            @can('admin.coupons.usersIndex')
                <li class="{{ Request::is('admin/couponsusers*') ? 'active' : '' }}">
                    @can('admin.coupons.usersIndex')<a href="{!! route('admin.coupons.usersIndex') !!}"><i class="fa fa-users"></i><span>Usuarios beneficiados</span></a>@endcan
                </li>
            @endcan
        </ul>
    </li>
@endcan

@can('admin.mercadoPagos.index')
    <li class="{{ Request::is('mercadoPagos*') ? 'active' : '' }}">
        @can('admin.mercadoPagos.index')<a href="{!! route('admin.mercadoPagos.index') !!}?role=role_admin"><i class="fa fa-edit"></i><span>Mercado Pago</span></a>@endcan
    </li>
@endcan

<li class="treeview {{ (Request::is('admin/provinces*')||Request::is('admin/contacts*')||Request::is('admin/notifications*')||Request::is('admin/settings*')) ? 'menu-open active':''}}">
    <a href="#">
        <i class="fa fa-cog"></i> <span>Administración</span>
        <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
    </a>
    <ul class="treeview-menu">
        @can('admin.provinces.index')
            <li class="{{ Request::is('admin/provinces*') ? 'active' : '' }}">
                <a href="{!! route('admin.provinces.index') !!}"><i class="fa fa-globe"></i><span>Provincias</span></a>
            </li>
        @endcan
        @can('admin.contacts.index')
            <li class="{{ Request::is('admin/contacts*') ? 'active' : '' }}">
                <a href="{!! route('admin.contacts.index') !!}"><i
                            class="fa fa-paper-plane"></i><span>Contacto</span></a>
            </li>
        @endcan
        @can('admin.notifications.index')
            <li class="{{ Request::is('admin/notifications*') ? 'active' : '' }}">
                <a href="{!! route('admin.notifications.index') !!}"><i
                            class="fa fa-comments-o"></i><span>Mensajes (Pop up)</span></a>
            </li>
        @endcan
        @can('admin.medias.index')
            <li class="{{ Request::is('admin/medias*') ? 'active' : '' }}">
                <a href="{!! route('admin.medias.index') !!}"><i
                            class="fa fa-picture-o"></i><span>Imagenes</span></a>
            </li>
        @endcan
        @can('admin.brands.index')
            <li class="{{ Request::is('admin/brands*') ? 'active' : '' }}">
                <a href="{!! route('admin.brands.index') !!}"><i
                            class="fa fa-vcard"></i><span>Marcas</span></a>
            </li>
        @endcan
            @can('admin.schools.index')
            <li class="{{ Request::is('admin/schools*') ? 'active' : '' }}">
                <a href="{!! route('admin.schools.index') !!}"><i
                            class="fa fa-graduation-cap"></i><span>Escuelas</span></a>
            </li>
        @endcan
        @can('admin.settings.index')
            <li class="{{ Request::is('admin/settings*') ? 'active' : '' }}">
                <a href="{!! route('admin.settings.index') !!}"><i
                            class="fa fa-gears"></i><span>Configuraciones</span></a>
            </li>
        @endcan
    </ul>
</li>

