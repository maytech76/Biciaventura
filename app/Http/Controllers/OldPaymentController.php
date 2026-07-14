<?php

namespace App\Http\Controllers;

use App\Models\OldPayment;
use App\Models\Receivable;
use Illuminate\Http\Request;

class OldPaymentController extends Controller
{
    // Registrar un pago
    public function store(Request $request, Receivable $receivable)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01|max:' . $receivable->pending_balance,
            'payment_method' => 'required|in:efectivo,debito,credito,transferencia,pmovil',
            'payment_date' => 'required|date',
            'reference' => 'nullable|string|max:100',
            'comments' => 'nullable|string',
        ]);

        // Crear el pago (el Observer hará el resto)
        OldPayment::create([
            'receivable_id' => $receivable->id,
            'amount' => $validated['amount'],
            'payment_date' => $validated['payment_date'],
            'payment_method' => $validated['payment_method'],
            'reference' => $validated['reference'],
            'comments' => $validated['comments'],
        ]);

        return redirect()->route('receivables.show', $receivable)
            ->with('success', 'Pago registrado exitosamente.');
    }

    // Mostrar recibo de pago
    public function showReceipt(OldPayment $payment)
    {
        $payment->load(['receivable', 'receivable.user']);
        return view('payments.receiptweb', compact('payment'));
    }

    // Eliminar pago
    public function destroy(OldPayment $payment)
    {
        $receivable = $payment->receivable;
        $payment->delete();
        
        return redirect()->route('receivables.show', $receivable)
            ->with('success', 'Pago eliminado correctamente.');
    }
}