<?php

namespace App\Observers;

use App\Models\OldPayment;
use App\Models\Receivable;
use Illuminate\Support\Facades\DB;

class OldPaymentObserver
{
    /**
     * Manejar el evento "creating" - Antes de crear
     */
    public function creating(OldPayment $payment)
    {
        // Generar número de recibo
        if (!$payment->receipt_number) {
            $payment->receipt_number = $this->generateReceiptNumber();
        }

        // Validar que el pago no exceda el saldo pendiente
        $receivable = Receivable::find($payment->receivable_id);
        
        if ($receivable && $payment->amount > $receivable->pending_balance) {
            throw new \Exception('El monto del pago excede el saldo pendiente.');
        }
    }

    /**
     * Manejar el evento "created" - Después de crear
     */
    public function created(OldPayment $payment)
    {
        // Actualizar el saldo de la cuenta por cobrar
        $this->updateReceivableBalance($payment);
    }

    /**
     * Manejar el evento "deleted" - Después de eliminar
     */
    public function deleted(OldPayment $payment)
    {
        // Si es soft delete, restaurar el saldo
        if (!$payment->isForceDeleting()) {
            $this->restoreReceivableBalance($payment);
        }
    }

    /**
     * Actualizar saldo de la cuenta por cobrar
     */
    private function updateReceivableBalance(OldPayment $payment): void
    {
        DB::transaction(function () use ($payment) {
            $receivable = Receivable::find($payment->receivable_id);
            
            if ($receivable) {
                $receivable->pending_balance -= $payment->amount;
                $receivable->save();
            }
        });
    }

    /**
     * Restaurar saldo de la cuenta por cobrar
     */
    private function restoreReceivableBalance(OldPayment $payment): void
    {
        DB::transaction(function () use ($payment) {
            $receivable = Receivable::find($payment->receivable_id);
            
            if ($receivable) {
                $receivable->pending_balance += $payment->amount;
                $receivable->save();
            }
        });
    }

    /**
     * Generar número de recibo único - CORREGIDO
     */
    private function generateReceiptNumber(): string
    {
        // Opción 1: Si quieres mantener soft deletes
        // $lastPayment = OldPayment::withTrashed()
        //     ->orderBy('id', 'desc')
        //     ->first();
        
        // Opción 2: Si NO usas soft deletes en pagos (más simple)
        $lastPayment = OldPayment::orderBy('id', 'desc')->first();

        if ($lastPayment && $lastPayment->receipt_number) {
            // Extraer número del último recibo (PAG-001 → 001)
            $lastNumber = substr($lastPayment->receipt_number, 4);
            $nextNumber = intval($lastNumber) + 1;
        } else {
            $nextNumber = 1;
        }

        return 'PAG-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }
}