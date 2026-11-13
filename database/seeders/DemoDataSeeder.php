<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AssetType;
use App\Models\ComponentInstallation;
use App\Models\ComponentType;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        /*
        $user = User::updateOrCreate(
            ['email' => 'admin@admin.admin'],
            [
                'name' => 'Demo User',
                'username' => 'admin1',
                'password' => Hash::make(''),
                'email_verified_at' => now(),
            ]
        );
        */

        $universityTag = $user->tags()->updateOrCreate(
            ['name' => 'University'],
            ['color' => '#2563EB']
        );

        $codingTag = $user->tags()->updateOrCreate(
            ['name' => 'Coding'],
            ['color' => '#7C3AED']
        );

        $dailyDeviceTag = $user->tags()->updateOrCreate(
            ['name' => 'Daily device'],
            ['color' => '#16A34A']
        );

        $gamingTag = $user->tags()->updateOrCreate(
            ['name' => 'Gaming'],
            ['color' => '#DC2626']
        );

        /*
        $laptopType = AssetType::where('slug', 'laptop')->firstOrFail();

        $laptop = $user->assets()->updateOrCreate(
            ['name' => 'Development Laptop'],
            [
                'asset_type_id' => $laptopType->id,
                'manufacturer' => 'Apple',
                'model' => 'MacBook Pro',
                'status' => 'active',
                'condition' => 'good',
                'acquisition_condition' => 'used',
                'acquisition_method' => 'purchased',
                'purchase_date' => now()->subYears(2)->toDateString(),
                'purchase_price' => 350.00,
                'currency' => 'EUR',
                'location' => 'Home',
                'description' => 'Primary portable development computer.',
                'specifications' => [
                    'display_size_inches' => 13.3,
                    'memory_gb' => 8,
                    'storage_gb' => 256,
                ],
            ]
        );

        $laptop->tags()->syncWithoutDetaching([
            $universityTag->id,
            $codingTag->id,
            $dailyDeviceTag->id,
        ]);

        $computerType = AssetType::where('slug', 'computer')->firstOrFail();

        $computer = $user->assets()->updateOrCreate(
            ['name' => 'Gaming PC'],
            [
                'asset_type_id' => $computerType->id,
                'manufacturer' => 'Custom',
                'status' => 'active',
                'condition' => 'good',
                'acquisition_condition' => 'used',
                'acquisition_method' => 'purchased',
                'currency' => 'EUR',
                'location' => 'Home',
                'description' => 'Custom-built desktop computer.',
            ]
        );

        $computer->tags()->syncWithoutDetaching([
            $codingTag->id,
            $gamingTag->id,
        ]);

        $processorType = ComponentType::where('slug', 'processor')
            ->firstOrFail();

        $processor = $user->components()->updateOrCreate(
            ['name' => 'Ryzen 5 2400G'],
            [
                'component_type_id' => $processorType->id,
                'manufacturer' => 'AMD',
                'model' => 'Ryzen 5 2400G',
                'status' => 'available',
                'condition' => 'good',
                'acquisition_condition' => 'new',
                'acquisition_method' => 'purchased',
                'currency' => 'EUR',
                'description' => 'Processor installed in the gaming PC.',
                'specifications' => [
                    'cores' => 4,
                    'threads' => 8,
                    'base_clock_ghz' => 3.6,
                    'socket' => 'AM4',
                ],
            ]
        );

        */

        ComponentInstallation::updateOrCreate(
            [
                'asset_id' => $computer->id,
                'component_id' => $processor->id,
                'removed_at' => null,
            ],
            [
                'installed_at' => now()->subYears(2),
                'slot' => 'CPU socket',
                'notes' => 'Initial processor installation.',
            ]
        );
    }
}