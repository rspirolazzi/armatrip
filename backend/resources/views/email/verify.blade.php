@extends('layouts.email')
@section('subject')

@endsection
@section('content')
    <div class="content">
        <table>
            <tr>
                <td>
                    <h3>¡Hola, {{$name}}!</h3>
                    <p class="lead">Gracias por registrarte en Armatrip.</p>
                    <p class="lead">Por favor hace click en el link de abajo para completar tu registro.</p>
                    <!-- Callout Panel -->
                    <p class="callout">
                        <a href="{{ url('api/verify', $verification_code)}}">Confirmar casilla de email </a>
                    </p><!-- /Callout Panel -->
                </td>
            </tr>
        </table>
    </div><!-- /content -->
@endsection