<div class="row">
    <div class="col-md-12">
        <table class="table table-striped">
            <thead>
            <tr>
                <th>Email</th>
                <th>Celular</th>
                <th>Fuente de registro</th>
                <th>Token</th>
                <th>Fecha</th>
            </tr>
            </thead>
            <tbody>
            @foreach($userVerifications as $userVerification)
                <tr>
                    <td class="col-md-3">{!!$userVerification->user->email!!}</td>
                    <td class="col-md-3">{!!$userVerification->user->phone_code!!}{!!$userVerification->user->phone_number!!}</td>
                    <td class="col-md-2">{!!$userVerification->source!!}</td>
                    <td class="col-md-2">{!!$userVerification->token!!}</td>
                    <td class="col-md-2">{!!$userVerification->created_at->format('d-m-Y H:i')!!}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
