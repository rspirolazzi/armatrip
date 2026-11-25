@extends('layouts.email_order')
@section('subject')

@endsection
@section('content')
    <div class="content">
        <table>
            <tr>
                <td>
                    <h3>¡Hola!</h3>
                    @foreach($introLines as $line)
                    <p class="lead">{{$line}}</p>
                    @endforeach
                </td>
            </tr>
            <tr>
                <td style="padding: 0 40px; font-family: 'Figtree', Arial, sans-serif; font-size: 15px; line-height: 140%; color: #555555;">
                    <!-- Button : BEGIN -->
                    <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" style="margin: auto;">
                        <tr>
                            <td style="border-radius: 3px; background: #0A5560; text-align: center;" class="button-td">
                                <a href="{{$actionUrl}}" style="background: #0A5560; border: 15px solid #0A5560; font-family: 'Figtree', Arial, sans-serif; font-size: 13px; line-height: 110%; text-align: center; text-decoration: none; display: block; border-radius: 3px; font-weight: bold;" class="button-a">
                                    <span style="color:#ffffff;" class="button-link">{{$actionText}}</span>
                                </a>
                            </td>
                        </tr>
                    </table>
                    <!-- Button : END -->
                </td>
            </tr>
        </table>
    </div><!-- /content -->
@endsection