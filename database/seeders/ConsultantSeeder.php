<?php

namespace Database\Seeders;

use App\Models\Consultant;
use App\Models\Schedule;
use Illuminate\Database\Seeder;

class ConsultantSeeder extends Seeder
{
    /**
     * Seed 3 active consultants with Mon–Fri 9am–6pm schedules.
     * Adjust timezones and hours to match your actual team locations.
     */
    public function run(): void
    {
        $consultants = [
            [
                'name'              => 'Maria Santos',
                'email'             => 'maria.santos@g4ce.com',
                'title'             => 'Senior BPO Solutions Consultant',
                'timezone'          => 'Asia/Manila',
                'max_daily_bookings'=> 8,
                'meeting_link'      => env('GOOGLE_MEET_DEFAULT_LINK', 'https://meet.google.com/xxx-yyyy-zzz'),
            ],
            [
                'name'              => 'James Reyes',
                'email'             => 'james.reyes@g4ce.com',
                'title'             => 'Enterprise Solutions Consultant',
                'timezone'          => 'Asia/Manila',
                'max_daily_bookings'=> 6,
                'meeting_link'      => env('GOOGLE_MEET_DEFAULT_LINK', 'https://meet.google.com/xxx-yyyy-zzz'),
            ],
            [
                'name'              => 'Ava Lin',
                'email'             => 'ava.lin@g4ce.com',
                'title'             => 'BPO Solutions Consultant',
                'timezone'          => 'America/Los_Angeles',
                'max_daily_bookings'=> 6,
                'meeting_link'      => env('GOOGLE_MEET_DEFAULT_LINK', 'https://meet.google.com/xxx-yyyy-zzz'),
            ],
        ];

        // Weekdays: Mon=1, Tue=2, Wed=3, Thu=4, Fri=5
        $weekdays = [1, 2, 3, 4, 5];

        foreach ($consultants as $data) {
            $consultant = Consultant::updateOrCreate(
                ['email' => $data['email']],
                $data + ['is_active' => true]
            );

            foreach ($weekdays as $day) {
                Schedule::updateOrCreate(
                    [
                        'consultant_id' => $consultant->id,
                        'day_of_week'   => $day,
                        'start_time'    => '09:00:00',
                    ],
                    [
                        'end_time'  => '18:00:00',
                        'is_active' => true,
                    ]
                );
            }
        }

        $this->command->info('✓ Consultants seeded (' . count($consultants) . ' consultants with Mon–Fri schedules)');
    }
}
