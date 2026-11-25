<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return redirect(env('APP_WEB_URL'));
        //return view('home');
    }

    /*
QUERY para futuro reporte
    select
users.name as Nombre,
TRIM(CONCAT(phone_code, ' - ', phone_number)) as Telefono,
email as Email,
orders.total as Total,
packages.name as Paquete,
orders.school_name as Colegio,
order_packages.qty as Pax
from users
left join orders on orders.user_id = users.id
inner join order_packages ON order_packages.order_id = orders.id
inner join packages on packages.id = order_packages.package_id
left join model_has_roles on model_has_roles.model_id = users.id
where model_has_roles.model_id is null
     */
}
