@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Paquete: {{$package->name}}
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       @include('flash::message')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   <div class="col-sm-12 text-right">
                       <div class="dropdown">
                           <button class="btn btn-info dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                               Acciones
                               <span class="caret"></span>
                           </button>
                           <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                               <li><a href="{!! route('admin.packages.products', [$package->id]) !!}"><i class="fa fa-shopping-cart"></i>Agregar productos</a></li>
                               {{--@if($package->store->)--}}
                               <li><a href="{!! route('admin.packages.dateRange', [$package->id]) !!}"><i class="fa fa-calendar"></i>Modificar Fechas</a></li>
                               {{--@else
                               <li><a href="{!! route('admin.packages.biweeklyRange', [$package->id]) !!}"><i class="fa fa-calendar"></i>Modificar Fechas</a></li>
                               @endif--}}
                               <li><a href="{!! route('admin.packages.photos', [$package->id]) !!}"><i class="fa fa-picture-o"></i>Agregar Photos</a></li>
                               <li><a href="{!! route('admin.packages.videos', [$package->id]) !!}"><i class="fa fa-picture-o"></i>Agregar Videos</a></li>
                           </ul>
                       </div>
                   </div>
                   {!! Form::model($package, ['route' => ['admin.packages.update', $package->id], 'method' => 'patch', 'files'=>'true']) !!}
                        @include('admin.packages.fields')
                       <!-- Submit Field -->
                       <div class="btn-group col-sm-4">
                           {!! Form::submit('Guardar cambios', ['class' => 'btn btn-primary']) !!}
                           <a href="{!! route('admin.packages.index') !!}" class="btn btn-default">Cancelar</a>
                       </div>
                       {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection