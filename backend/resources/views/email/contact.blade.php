@extends('layouts.email_order')
@section('subject')

@endsection
@section('content')
    <div class="content">
        <table>
            <tr>
                <td>
                    <h3>¡Gracias, {{$contact->name}}!</h3>
                    <p class="lead">A la brevedad nos estaremos contactando con vos</p>
                    <!-- Callout Panel -->
                    <p class="callout">
                        Podés comunicarte con {{$contact->store->name}} al {{$contact->store->telephone}}.
                    </p><!-- /Callout Panel -->
                </td>
            </tr>
        </table>
    </div><!-- /content -->
@endsection