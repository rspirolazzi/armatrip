<?php

return [
    'table' => [
        /**
         * Nombre de la tabla para las categorias
         */
        'companies' => 'companies',
    ],
    'models' => [
        /*
         * We need to know which Eloquent model should be used to retrieve your categories.
         * Of course, it is often just the "Category" model but you may use whatever you like.
         *
         * The model you want to use as a Category model needs to implement the
         * `Rspirolazzi\MultiCompany\Contracts\Company` contract.
         */
        'company' => Rspirolazzi\MultiCompany\Models\Company::class,
    ],
];