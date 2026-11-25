@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Producto: {{$product->name}}
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($product, ['route' => ['idg.products.update', $product->id], 'method' => 'patch', 'files'=>'true']) !!}
                        @include('admin.idg_products.fields')
                       <!-- Submit Field -->
                       <div class="form-group col-sm-12">
                           {!! Form::submit('Guardar cambios', ['class' => 'btn btn-primary btn-gradient']) !!}
                           {{--<a href="{!! route('idg.products.items', [$product->id]) !!}" class="btn btn-warning btn-gradient">Agregar sabores</a>--}}
                           <a href="{!! route('idg.products.index') !!}" class="btn btn-default btn-gradient">Cancelar</a>
                       </div>
                       {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection