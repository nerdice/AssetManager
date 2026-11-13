<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AssetType;

class AssetTypeSeeder extends Seeder
{
    public function run(): void
    {
        $assetTypes = [
            [
                'name' => 'Computer',
                'slug' => 'computer',
                'icon' => 'desktop',
                'description' => 'Desktop and custom-built computers.',
                'supports_agent' => true,
                'can_contain_components' => true,
            ],
            [
                'name' => 'Laptop',
                'slug' => 'laptop',
                'icon' => 'laptop',
                'description' => 'Portable computers and notebooks.',
                'supports_agent' => true,
                'can_contain_components' => true,
            ],
            [
                'name' => 'Phone',
                'slug' => 'phone',
                'icon' => 'phone',
                'description' => 'Smartphones and mobile phones.',
                'supports_agent' => false,
                'can_contain_components' => false,
            ],
            [
                'name' => 'Tablet',
                'slug' => 'tablet',
                'icon' => 'tablet',
                'description' => 'Tablets and similar portable devices.',
                'supports_agent' => false,
                'can_contain_components' => false,
            ],
            [
                'name' => 'Monitor',
                'slug' => 'monitor',
                'icon' => 'display',
                'description' => 'Computer monitors and displays.',
                'supports_agent' => false,
                'can_contain_components' => false,
            ],
            [
                'name' => 'Audio device',
                'slug' => 'audio_device',
                'icon' => 'headphones',
                'description' => 'Headphones, earphones, speakers, and microphones.',
                'supports_agent' => false,
                'can_contain_components' => false,
            ],
            [
                'name' => 'Peripheral',
                'slug' => 'peripheral',
                'icon' => 'mouse',
                'description' => 'Keyboards, mice, webcams, and other peripherals.',
                'supports_agent' => false,
                'can_contain_components' => false,
            ],
            [
                'name' => 'Development board',
                'slug' => 'development_board',
                'icon' => 'cpu',
                'description' => 'Arduino, ESP32, Raspberry Pi, and similar boards.',
                'supports_agent' => false,
                'can_contain_components' => false,
            ],
            [
                'name' => 'Accessory',
                'slug' => 'accessory',
                'icon' => 'plug',
                'description' => 'Chargers, adapters, docks, styluses, and accessories.',
                'supports_agent' => false,
                'can_contain_components' => false,
            ],
            [
                'name' => 'Network device',
                'slug' => 'network_device',
                'icon' => 'router',
                'description' => 'Routers, switches, and network equipment.',
                'supports_agent' => false,
                'can_contain_components' => false,
            ],
            [
                'name' => 'Other',
                'slug' => 'other',
                'icon' => 'device',
                'description' => 'Technology that does not fit another type.',
                'supports_agent' => false,
                'can_contain_components' => false,
            ],
        ];

        foreach ($assetTypes as $assetType) {
            AssetType::updateOrCreate(
                ['slug' => $assetType['slug']],
                $assetType
            );
        }
    }
}