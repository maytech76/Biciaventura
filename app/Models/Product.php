<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Traits\HasPriceInDollars;

class Product extends Model
{

    use HasPriceInDollars;

    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        
        'category_id',
        'type',
        'brand_id',
        'name',
        'codebar',
        'slug',
        'description',
        'price',
        'price2',
        'cost',
        'utility_percentage',
        'profit',
        'stock',
        'main_image',
        /* 'status', */
        'interchangeable',
        'refundable',
        'video_provider',
        'video_link',
        'unit'
    ];

    const UNITS = [
        
        'UND' => 'UND',
        'CAJA' => 'CAJA',
        'PAR' => 'PAR', 
        'PIEZA' => 'PIEZA',
        'KIT' => 'KIT',
        'SET' => 'SET',
        'JUEGO' => 'JUEGO'
    ];

    protected $casts = [
        'interchangeable' => 'boolean',
        'refundable' => 'boolean',
        'status' => 'boolean',
        'price' => 'decimal:2',
        'price2' => 'decimal:2',
        'cost' => 'decimal:2',
        'profit' => 'decimal:2',
        'utility_percentage' => 'decimal:2',
    ];

    protected $appends = [
        'main_image_url',
        'all_images'
    ];

    /**
     * Boot del modelo
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($product) {
            $product->calculateProfit();
            
            // Generar slug automáticamente si no existe
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });

        // Eliminar imágenes asociadas al eliminar el producto
        static::deleting(function ($product) {
            $product->images()->delete();
        });
    }

    /**
     * Relación con la categoría
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relación con la marca
     */
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * Relación con las imágenes adicionales
     */
    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('order');
    }

    /**
     * Relación con la imagen principal (si quieres un acceso directo)
     */
    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    /**
     * Relación con items de orden
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Relación con reviews
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Calcular utilidad automáticamente
     */
    public function calculateProfit()
    {
        if ($this->cost && $this->utility_percentage) {
            $this->profit = ($this->cost * $this->utility_percentage) / 100;
            $this->price = $this->cost + $this->profit;
        }
    }

    /**
     * Accessor para URL de imagen principal
     */
    public function getMainImageUrlAttribute()
    {
        return $this->main_image ? asset('storage/' . $this->main_image) : asset('images/default-product.png');
    }

    /**
     * Accessor para obtener todas las imágenes (principal + adicionales)
     **/
    public function getAllImagesAttribute()
    {
        $images = collect();

        // Imagen principal (desde products.main_image)
        if ($this->main_image) {
            $images->push([
                'type' => 'main',
                'path' => $this->main_image,
                'url' => $this->main_image ? asset('storage/' . $this->main_image) : asset('images/default-product.png'), // URL directa
                'is_primary' => true,
                'id' => null
            ]);
        }

        // Imágenes adicionales (desde product_images)
        foreach ($this->images as $image) {
            $images->push([
                'type' => 'additional',
                'path' => $image->path,
                'url' => $image->path ? asset('storage/' . $image->path) : null, // URL directa
                'is_primary' => $image->is_primary,
                'id' => $image->id,
                'order' => $image->order
            ]);
        }

        return $images;
    }
    /**
     * Obtener solo las imágenes adicionales (sin la principal)
     */
    public function getAdditionalImagesAttribute()
    {
        return $this->images;
    }

    /**
     * Scope para productos activos
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * Scope para productos con stock
     */
    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    /**
     * Scope para búsqueda
     */
    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%")
                    ->orWhere('codebar', 'LIKE', "%{$search}%");
    }

    /**
     * Verificar si tiene imágenes adicionales
     */
    public function hasAdditionalImages()
    {
        return $this->images->count() > 0;
    }

    /**
     * Obtener el total de imágenes (principal + adicionales)
     */
    public function getTotalImagesAttribute()
    {
        $count = $this->main_image ? 1 : 0;
        return $count + $this->images->count();
    }

    /**
     * Obtener la primera imagen disponible (principal o primera adicional)
     */
    public function getFirstImageUrlAttribute()
    {
        if ($this->main_image) {
            return $this->main_image_url;
        }

        if ($this->images->count() > 0) {
            return $this->images->first()->url;
        }

        return asset('images/default-product.png');
    }
}