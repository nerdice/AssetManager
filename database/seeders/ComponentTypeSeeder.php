<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ComponentType;

class ComponentTypeSeeder extends Seeder
{
    public function run(): void
    {
        $componentTypes = [
            [
                'name' => 'Processor',
                'slug' => 'processor',
                'description' => 'Central processing units.',
            ],
            [
                'name' => 'Graphics card',
                'slug' => 'graphics_card',
                'description' => 'Dedicated and integrated graphics processors.',
            ],
            [
                'name' => 'Motherboard',
                'slug' => 'motherboard',
                'description' => 'Computer motherboards.',
            ],
            [
                'name' => 'Memory',
                'slug' => 'memory',
                'description' => 'RAM modules.',
            ],
            [
                'name' => 'Storage drive',
                'slug' => 'storage_drive',
                'description' => 'Hard drives, solid-state drives, and other storage.',
            ],
            [
                'name' => 'Power supply',
                'slug' => 'power_supply',
                'description' => 'Computer power supply units.',
            ],
            [
                'name' => 'Cooling device',
                'slug' => 'cooling_device',
                'description' => 'Fans, heatsinks, and liquid cooling devices.',
            ],
            [
                'name' => 'Network adapter',
                'slug' => 'network_adapter',
                'description' => 'Ethernet, Wi-Fi, and Bluetooth adapters.',
            ],
            [
                'name' => 'Expansion card',
                'slug' => 'expansion_card',
                'description' => 'PCIe and other expansion cards.',
            ],
            [
                'name' => 'Case',
                'slug' => 'case',
                'description' => 'Computer cases and enclosures.',
            ],
            [
                'name' => 'Battery',
                'slug' => 'battery',
                'description' => 'Internal and replaceable device batteries.',
            ],
            [
                'name' => 'Other',
                'slug' => 'other',
                'description' => 'Components that do not fit another type.',
            ],
        ];

        foreach ($componentTypes as $componentType) {
            ComponentType::updateOrCreate(
                ['slug' => $componentType['slug']],
                $componentType
            );
        }
    }
}