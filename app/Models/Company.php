<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'email',
        'phone',
        'logo_url',
        'plan',
        'status',
    ];

    /**
     * Relación con los usuarios de la empresa
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Relación con las categorías de productos
     */
    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    /**
     * Relación con los productos del catálogo
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Generar URL del QR único del catálogo
     */
    public function getQrUrlAttribute(): string
    {
        return url('/q/' . $this->slug);
    }
}
