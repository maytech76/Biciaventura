<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OldPayment extends Model
{
    use HasFactory, SoftDeletes; // AÑADIR SoftDeletes

    protected $fillable = [
        'receipt_number',
        'receivable_id',
        'amount',
        'payment_date',
        'payment_method',
        'reference',
        'comments'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'date',
    ];

    protected $dates = ['deleted_at']; // OPCIONAL: Para versiones antiguas de Laravel

    // Relación con la cuenta por cobrar
    public function receivable()
    {
        return $this->belongsTo(Receivable::class);
    }
}