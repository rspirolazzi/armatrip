<?php

namespace App\Exports\Admin;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class OrderExport implements FromCollection, WithMapping, WithHeadings, WithColumnFormatting
{
    public function headings(): array
    {
        return [
            '#',
            'Usuario',
            'Teléfono',
            'Empresa',
            'Provincia',
            'Paquete',
            'Número',
            'Sub total',
            'Descuento',
            'Total',
            'Año',
            'Mes',
            'Semana',
            'Escuela',
            'Localidad',
            'Estado',
            'Creado',
            'Reservado',
            'Comentarios',
        ];
    }
    /**
    * @var Order $order
    */
    public function map($order): array
    {
        return [
            $order->id,
            !empty($order->user) ? $order->user->email : '',
            !empty($order->user) ? $order->user->phone_code.' '. $order->user->phone_number : '',
            !empty($order->store) ? $order->store->name : '',
            !empty($order->province) ? $order->province->name : '',
            !empty($order->package) ? $order->package->name : '',
            $order->number,
            $order->sub_total,
            $order->discount,
            $order->total,
            $order->year,
            $order->month,
            $order->biweekly,
            $this->formatSchoolName($order),
            $this->formatSchoolCity($order),
            $this->formatStatus($order->status),
            $order->created_at->format('d/m/y H:i'),
            $order->reserved_at ? $order->reserved_at->format('d/m/y H:i') : '',
            $order->comments,
        ];
    }
    protected function formatSchoolName($order){
        if($order->school){
            return $order->school->name;
        }else{
            return $order->school_name;
        }
    }
    protected function formatSchoolCity($order){
        if($order->school){
            return $order->school->city;
        }else{
            return $order->school_city;
        }
    }
    protected function formatStatus($value){
        if($value == \App\Models\Order::$STATUS_PENDING){
            return 'Pendiente';
        }elseif($value == \App\Models\Order::$STATUS_FINISHED){
            return 'Finalizado';
        }elseif($value == \App\Models\Order::$STATUS_CANCELLED){
            return 'Cancelado';
        }elseif($value == \App\Models\Order::$STATUS_SOLD){
            return 'Vendido';
        }elseif($value == \App\Models\Order::$STATUS_CONTACTED){
            return 'Contactado';
        }else{
            return 'N/A';
        }
    }
    public function columnFormats(): array
    {
        return [
            'H' => '$#,##0_-',
            'I' => '$#,##0_-',
            'J' => '$#,##0_-',
        ];
    }

    protected $orders;

    public function __construct($orders)
    {
        $this->orders = $orders;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->orders;
    }
}
