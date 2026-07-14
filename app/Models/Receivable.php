<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Receivable extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'user_id',
        'concept',
        'description',
        'total_amount',
        'pending_balance',
        'issue_date',
        'due_date',
        'status'
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'pending_balance' => 'decimal:2',
        'issue_date' => 'date',
        'due_date' => 'date',
    ];

    // Relación con usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relación con pagos
    public function payments()
    {
        return $this->hasMany(OldPayment::class);
    }

    // Verificar si está vencido
    public function isOverdue(): bool
    {
        if (!$this->due_date) return false;
        
        $now = now();
        $dueDate = \Carbon\Carbon::parse($this->due_date);
        
        return $now->greaterThan($dueDate) && 
               $this->pending_balance > 0 && 
               $this->status !== 'pagado';
    }

    // Calcular porcentaje pagado
    public function getPaymentPercentageAttribute(): float
    {
        if ($this->total_amount == 0) return 0;
        
        $paid = $this->total_amount - $this->pending_balance;
        return ($paid / $this->total_amount) * 100;
    }

    // Obtener monto pagado
    public function getPaidAmountAttribute(): float
    {
        return $this->total_amount - $this->pending_balance;
    }

    // Obtener días de retraso
    public function getDaysOverdueAttribute(): ?int
    {
        if (!$this->isOverdue()) return null;
        
        $now = now();
        $dueDate = \Carbon\Carbon::parse($this->due_date);
        return $dueDate->diffInDays($now);
    }

    // Scope para cuentas vencidas
    public function scopeOverdue($query)
    {
        return $query->where('status', 'retrazo')
            ->orWhere(function($q) {
                $q->where('due_date', '<', now())
                  ->where('pending_balance', '>', 0)
                  ->where('status', '!=', 'pagado');
            });
    }

    // Scope para cuentas activas
    public function scopeActive($query)
    {
        return $query->where('pending_balance', '>', 0);
    }
}