<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'name'        => 'Inbound / Outbound Customer Care',
                'slug'        => 'customer-care',
                'description' => '24/7 omnichannel support, customer retention, and CX excellence at scale.',
                'icon'        => 'phone',
                'sort_order'  => 1,
            ],
            [
                'name'        => 'Technical Support & Helpdesk L1/L2',
                'slug'        => 'technical-support',
                'description' => 'Tier 1 & 2 technical resolution, escalation management, and SLA adherence.',
                'icon'        => 'cpu-chip',
                'sort_order'  => 2,
            ],
            [
                'name'        => 'Back-Office & Data Processing',
                'slug'        => 'back-office',
                'description' => 'Accurate, secure data entry, processing, verification, and quality control.',
                'icon'        => 'clipboard-document-list',
                'sort_order'  => 3,
            ],
            [
                'name'        => 'Virtual Assistance & Executive Operations',
                'slug'        => 'virtual-assistance',
                'description' => 'Remote EA support, scheduling, research, inbox management, and admin operations.',
                'icon'        => 'user-circle',
                'sort_order'  => 4,
            ],
            [
                'name'        => 'Dedicated Team / Staff Augmentation',
                'slug'        => 'staff-augmentation',
                'description' => 'Scalable dedicated teams integrated directly into your operational workflow.',
                'icon'        => 'users',
                'sort_order'  => 5,
            ],
        ];

        foreach ($services as $svc) {
            Service::updateOrCreate(['slug' => $svc['slug']], $svc + ['is_active' => true]);
        }

        $this->command->info('✓ Services seeded (' . count($services) . ' records)');
    }
}
