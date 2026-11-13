<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Asset extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'asset_type_id',
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
        'is_public',
    ];

    protected function casts(): array
    {
        return [
            'purchase_date' => 'date',
            'purchase_price' => 'decimal:2',
            'warranty_expires_at' => 'date',
            'specifications' => 'array',
            'is_public' => 'boolean',
            'views_count' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function assetType(): BelongsTo
    {
        return $this->belongsTo(AssetType::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class)
            ->withTimestamps();
    }

    public function componentInstallations(): HasMany
    {
        return $this->hasMany(ComponentInstallation::class);
    }

    public function currentComponentInstallations(): HasMany
    {
        return $this->componentInstallations()
            ->whereNull('removed_at');
    }

    public function components(): BelongsToMany
    {
        return $this->belongsToMany(
            Component::class,
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

    public function currentComponents(): BelongsToMany
    {
        return $this->components()
            ->wherePivotNull('removed_at');
    }
}