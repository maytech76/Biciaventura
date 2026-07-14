<?php

namespace App\Observers;

use App\Models\Receivable;

class ReceivableObserver
{
    // Generar código antes de crear
    public function creating(Receivable $receivable)
    {
        if (!$receivable->code) {
            $receivable->code = $this->generateReceivableCode();
        }
    }

    // Después de crear
    public function created(Receivable $receivable)
    {
        // Puedes agregar lógica adicional aquí
        // como notificaciones o logs
    }

    // Antes de actualizar
    public function updating(Receivable $receivable)
    {
        // Verificar vencimiento
        if ($receivable->isOverdue() && $receivable->status !== 'pagado') {
            $receivable->status = 'retrazo';
        }
        
        // Actualizar estado según saldo
        $this->updateStatusByBalance($receivable);
    }

    // Después de actualizar
    public function updated(Receivable $receivable)
    {
        // Puedes agregar lógica adicional aquí
    }

    // Después de recuperar (cuando se consulta desde DB)
    public function retrieved(Receivable $receivable)
    {
        // Verificar si está vencido cada vez que se recupera
        if ($receivable->isOverdue() && $receivable->status !== 'pagado') {
            // Actualizar estado si es necesario
            if ($receivable->status !== 'retrazo') {
                $receivable->status = 'retrazo';
                $receivable->saveQuietly(); // Guardar sin disparar eventos
            }
        }
    }

    // Método para actualizar estado según balance
    private function updateStatusByBalance(Receivable $receivable): void
    {
        if ($receivable->pending_balance <= 0) {
            $receivable->status = 'pagado';
        } elseif ($receivable->pending_balance < $receivable->total_amount) {
            $receivable->status = 'parcial';
        } elseif ($receivable->pending_balance == $receivable->total_amount) {
            if (!$receivable->isOverdue()) {
                $receivable->status = 'pendiente';
            }
        }
    }

    // Generar código único para la cuenta
    private function generateReceivableCode(): string
    {
        $lastReceivable = Receivable::withTrashed()
            ->orderBy('id', 'desc')
            ->first();

        $nextNumber = $lastReceivable ? 
            (int) substr($lastReceivable->code, 4) + 1 : 1;

        return 'CXC-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }
}