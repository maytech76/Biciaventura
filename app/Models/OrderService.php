<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderService extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'order_services';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        
        'order_number',
        'user_id',
        'vehicle_id',
        'mechanic_id',
        'order_date',
        'status',
        'priority',
        'subtotal',
        'discount_porc',
        'discount_total',
        'total',
        'payment_status',
        'payment_method',
        'started_at',
        'completed_at',
        'delivered_at',
        'notes',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'order_date' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'delivered_at' => 'datetime',
        'subtotal' => 'decimal:2',
        'discount_porc' => 'decimal:2',
        'discount_total' => 'decimal:2',
        'total' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'deleted_at',
    ];

    /**
     * Get the user (customer) that owns the order.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the vehicle associated with the order.
     */
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    /**
     * Get the mechanic assigned to the order.
     */
    public function mechanic()
    {
        return $this->belongsTo(Mechanic::class);
    }

    /**
     * Get the user who created the order.
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated the order.
     */
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get the details for the order.
     */
    public function details()
    {
        return $this->hasMany(DetailOrder::class, 'order_id');
    }

    /**
     * Scope a query to only include pending orders.
     */
    public function scopePending($query)
    {
        return $query->where('payment_status', 'pendiente');
    }

    /**
     * Scope a query to only include paid orders.
     */
    public function scopePaid($query)
    {
        return $query->where('payment_status', 'pagado');
    }

    /**
     * Scope a query to only include active orders (reception or in_service).
     */
    public function scopeActive($query)
    {
        return $query->whereIn('status', ['recepcion', 'en_servicio']);
    }

    /**
     * Scope a query to only include completed orders.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completado');
    }

    /**
     * Scope a query to only include canceled orders.
     */
    public function scopeCanceled($query)
    {
        return $query->where('status', 'cancelado');
    }

    /**
     * Scope a query to filter by status.
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query to filter by priority.
     */
    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    /**
     * Scope a query to search by order number or customer name.
     */
    public function scopeSearch($query, $search){

        return $query->where('order_number', 'LIKE', "%{$search}%")
                     ->orWhereHas('user', function($q) use ($search) {
                         $q->where('name', 'LIKE', "%{$search}%")
                           ->orWhere('email', 'LIKE', "%{$search}%");
                     })
                     ->orWhereHas('vehicle', function($q) use ($search) {
                         $q->where('model', 'LIKE', "%{$search}%")
                           ->orWhere('plate', 'LIKE', "%{$search}%");
                     });
    }

    /**
     * Get the formatted total amount.
     */
    public function getFormattedTotalAttribute()
    {
        return '$ ' . number_format($this->total, 2, ',', '.');
    }

    /**
     * Get the formatted subtotal amount.
     */
    public function getFormattedSubtotalAttribute()
    {
        return '$ ' . number_format($this->subtotal, 2, ',', '.');
    }

    /**
     * Get the formatted discount amount.
     */
    public function getFormattedDiscountAttribute()
    {
        return '$ ' . number_format($this->discount_total, 2, ',', '.');
    }

    /**
     * Get the status label with color.
     */
    public function getStatusBadgeAttribute(){

        $badges = [
            'recepcion' => 'bg-info',
            'en_servicio' => 'bg-warning',
            'completado' => 'bg-success',
            'entregado' => 'bg-primary',
            'cancelado' => 'bg-danger',
        ];

        $labels = [
            'recepcion' => 'Recepción',
            'en_servicio' => 'En Servicio',
            'completado' => 'Completado',
            'entregado' => 'Entregado',
            'cancelado' => 'Cancelado',
        ];

        return '<span class="badge ' . ($badges[$this->status] ?? 'bg-secondary') . '">' 
               . ($labels[$this->status] ?? $this->status) . '</span>';
    }

    /**
     * Get the payment status label with color.
     */
    public function getPaymentStatusBadgeAttribute(){

        $badges = [
            'pendiente' => 'bg-warning',
            'parcial' => 'bg-info',
            'pagado' => 'bg-success',
        ];

        $labels = [
            'pendiente' => 'Pendiente',
            'parcial' => 'Parcial',
            'pagado' => 'Pagado',
        ];

        return '<span class="badge ' . ($badges[$this->payment_status] ?? 'bg-secondary') . '">' 
               . ($labels[$this->payment_status] ?? $this->payment_status) . '</span>';
    }

    /**
     * Calculate the total based on subtotal, discount, and tax.
     */
    public function calculateTotal(){

        $discountAmount = $this->discount_total ?? 0;
        $this->total = $this->subtotal - $discountAmount;
        $this->save();
        
        return $this->total;
    }

    /**
     * Boot method for the model.
     */
    protected static function boot(){

        parent::boot();

        // Auto-generate order number before creating
        static::creating(function ($order) {
            if (empty($order->order_number)) {
                $order->order_number = 'ORD-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
            }
        });
    }
}