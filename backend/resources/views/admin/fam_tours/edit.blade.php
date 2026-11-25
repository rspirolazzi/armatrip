@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Fam Tour
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($fam_tour, ['route' => ['admin.fam_tours.update'], 'method' => 'patch']) !!}
                        {!! Form::hidden('id', null) !!}   
                        @include('admin.fam_tours.fields')
                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection
@section('scripts')
<script type="text/javascript">
    $(function(){
        console.log('tags',{!! json_encode($tags) !!})
        $('#category').tagsInput({
            'autocomplete': {
                source: {!! json_encode($tags) !!}
            },
            'placeholder':'Escriba el nombre de la categoría y presione , (coma) para seguir agregando'
        });
    })
</script>
@endsection