<ul class="nav-custom nav nav-pills">
    <li role="presentation" class="{{ Request::is('admin/packages*') ? 'active' : '' }}"><a href="{{route('admin.packages.index')}}"><i class="fa fa-plane"></i> Paquetes</a></li>
    <li role="presentation" class="{{ Request::is('categories') ? 'active' : '' }}"><a href="{{route('admin.categories.index')}}"><i class="fa fa-list"></i> Categorias</a></li>
    <li role="presentation" class="{{ Request::is('products') ? 'active' : '' }}"><a href="{{route('admin.products.index')}}"><i class="fa fa-list"></i> Productos</a></li>
</ul>