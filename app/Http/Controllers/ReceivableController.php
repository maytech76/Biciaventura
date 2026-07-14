<?php

namespace App\Http\Controllers;

use App\Models\Receivable;
use App\Models\User;
use Illuminate\Http\Request;

class ReceivableController extends Controller
{
    // Mostrar todas las cuentas
    public function index(){
        
        $receivables = Receivable::with('user')
            ->latest()
            ->paginate(10);
            
        return view('receivables.index', compact('receivables'));
    }

    // Mostrar formulario de creación
    public function create()
    {
        $customers = User::where('role', 'customer')->get();
        return view('receivables.create', compact('customers'));
    }

    // Guardar nueva cuenta
    public function store(Request $request){

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'concept' => 'required|string|max:255',
            'description' => 'nullable|string',
            'total_amount' => 'required|numeric|min:0.01',
            'due_date' => 'nullable|date|after_or_equal:today',
        ]);

        Receivable::create([
            'user_id' => $validated['user_id'],
            'concept' => $validated['concept'],
            'description' => $validated['description'],
            'total_amount' => $validated['total_amount'],
            'pending_balance' => $validated['total_amount'],
            'issue_date' => now(),
            'due_date' => $validated['due_date'],
            // El Observer generará el código y estado automáticamente
        ]);

        return redirect()->route('receivables.index')
            ->with('success', 'Cuenta por cobrar creada exitosamente.');
    }

    // Mostrar detalles de una cuenta
    public function show(Receivable $receivable)
    {
        $receivable->load(['user', 'payments']);
        return view('receivables.show', compact('receivable'));
    }

    // Mostrar formulario de edición
    public function edit(Receivable $receivable)
    {
        $customers = User::where('role', 'customer')->get();
        return view('receivables.edit', compact('receivable', 'customers'));
    }

    // Actualizar cuenta
    public function update(Request $request, Receivable $receivable){

        $validated = $request->validate([
            'concept' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
        ]);

        $receivable->update($validated);

        return redirect()->route('receivables.show', $receivable)
            ->with('success', 'Cuenta actualizada correctamente.');
    }

    // Eliminar cuenta
    public function destroy(Receivable $receivable){

        if ($receivable->pending_balance > 0) {
            return back()->with('error', 'No se puede eliminar una cuenta con saldo pendiente.');
        }

        $receivable->delete();
        
        return redirect()->route('receivables.index')
            ->with('success', 'Cuenta eliminada correctamente.');
    }

    // Mostrar formulario para registrar pago
    public function createPayment(Receivable $receivable)
    {
        return view('receivables.payment', compact('receivable'));
    }
}