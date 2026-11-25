<table class="table table-responsive" id="coupons-table">
    <thead>
    <tr>
        <th>Código</th>
        <th>Nombre</th>
        <th>Description</th>
        <th>Descuento (%)</th>
        <th>Vencimiento</th>
        <th>Activo</th>
        <th colspan="3"></th>
    </tr>
    </thead>
    <tbody>
    @foreach($coupons as $coupon)
        <tr class="{!! $coupon->enabled === false ?'warning':'' !!}">
            <td>{!! $coupon->code !!}</td>
            <td>{!! $coupon->name !!}</td>
            <td>{!! $coupon->description !!}</td>
            <td>{!! $coupon->reward !!}%</td>
            <td>
                @if(is_null($coupon->expires_at))
                    No expira
                @else
                    {!! $coupon->expires_at->format('d-m-Y') !!}
                @endif
            </td>
            <td>{!! $coupon->enabled ? 'Si' :'No' !!}</td>
            <td>
                {!! Form::open(['route' => ['admin.coupons.destroy', $coupon->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    @can('admin.coupons.show')<a href="{!! route('admin.coupons.show', [$coupon->id]) !!}" class='btn btn-default btn-xs'><i class="fa fa-eye"></i></a>@endcan
                    @can('admin.coupons.edit')<a href="{!! route('admin.coupons.edit', [$coupon->id]) !!}" class='btn btn-default btn-xs'><i class="fa fa-edit"></i></a>@endcan
                    @can('admin.coupons.destroy'){!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Estas seguro?')"]) !!}@endcan
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>