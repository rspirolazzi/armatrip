@extends('layouts.email_order')
@section('subject')

@endsection
@section('content')
    <div class="content">
        <table>
            <tr>
                <td>
                    <h3>¡{{$contact->name}} se quiere contactar!</h3>
                    <p class="lead">{{$contact->name}} con email {{$contact->email}} se esta intentando contactar con : </p>
                    <!-- Callout Panel -->
                    <p class="callout">
                        {{$contact->store->name}}
                    </p><!-- /Callout Panel -->
                </td>
            </tr>
        </table>
    </div><!-- /content -->
@endsection