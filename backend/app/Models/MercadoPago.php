<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class MercadoPago
 * @package App\Models
 * @version May 20, 2018, 1:53 pm UTC
 *
 * @property integer order_id
 * @property decimal transaction_amount
 * @property string token
 * @property string description
 * @property integer installments
 * @property string payment_method_id
 * @property mixed status
 * @property mixed body
 */
class MercadoPago extends Model
{

    public static $STATUS_APPROVED = 'approved';
    public static $STATUS_IN_PROCESS = 'in_process';
    public static $STATUS_REJECTED = 'rejected';
    public static $STATUS_EXCEPTION = 'EXCEPTION';

    public $table = 'mercado_pagos';


    public $fillable = [
        'order_id',
        'transaction_amount',
        'token',
        'description',
        'installments',
        'payment_method_id',
        'status',
        'body',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'order_id' => 'integer',
        'transaction_amount' => 'float',
        'token' => 'string',
        'description' => 'string',
        'installments' => 'string',
        'payment_method_id' => 'string',
        'status' => 'string',
        'body' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'order_id' => 'required',
        'transaction_amount' => 'required',
        'token' => 'required',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }


    public function toPaymentData()
    {
        return [
            "transaction_amount" => $this->transaction_amount,
            "token" => $this->token,
            "description" => $this->description,
            "installments" => 1,
            "payment_method_id" => $this->payment_method_id,
            "payer" => [
                'email' => $this->order->user->email,
            ],
        ];
    }

    public function getBodyToArrayAttribute()
    {
        if ($this->body != '') {
            return unserialize($this->body);
        }
        return [];
    }
}
