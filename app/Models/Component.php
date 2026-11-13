<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Component extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'component_type_id',
        'name',
        'manufacturer',
        'model',
        'model_number',
        'serial_number',
        'status',
        'condition',
        'acquisition_condition',
        'acquisition_method',
        'purchase_date',
        'purchase_price',
        'currency',
        'seller',
        'warranty_expires_at',
        'location',
        'description',
        'specifications',
    ];

    protected function casts(): array
    {
        return [
            'purchase_date' => 'date',
            'purchase_price' => 'decimal:2',
            'warranty_expires_at' => 'date',
            'specifications' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function componentType(): BelongsTo
    {
        return $this->belongsTo(ComponentType::class);
    }

    public function installations(): HasMany
    {
        return $this->hasMany(ComponentInstallation::class);
    }

    public function currentInstallations(): HasMany
    {
        return $this->installations()
            ->whereNull('removed_at');
    }

    public function assets(): BelongsToMany
    {
        return $this->belongsToMany(
            Asset::class,
            'component_installations'
        )
            ->withPivot([
                'id',
                'installed_at',
                'removed_at',
                'slot',
                'notes',
            ])
            ->withTimestamps();
    }

    public function currentAssets(): BelongsToMany
    {
        return $this->assets()
            ->wherePivotNull('removed_at');
    }
}