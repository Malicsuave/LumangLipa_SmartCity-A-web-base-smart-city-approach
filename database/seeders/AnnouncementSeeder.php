<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Announcement;
use Carbon\Carbon;

class AnnouncementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $announcements = [
            [
                'title' => 'Free COVID-19 Vaccination Drive',
                'content' => 'The Barangay Health Center will conduct a FREE COVID-19 Vaccination Drive for all residents. Please bring your valid ID and vaccination card (if available). Walk-ins are welcome!',
                'type' => 'health_related',
                'max_slots' => 100,
                'current_slots' => 67,
                'date' => Carbon::now()->addDays(5),
                'start_time' => '08:00:00',
                'end_time' => '16:00:00',
                'is_active' => true,
            ],
            [
                'title' => 'Community Clean-Up Day',
                'content' => 'Join us for a community-wide clean-up activity! Let\'s work together to keep our barangay clean and beautiful. Cleaning materials will be provided.',
                'type' => 'event',
                'max_slots' => 50,
                'current_slots' => 28,
                'date' => Carbon::now()->addDays(7),
                'start_time' => '06:00:00',
                'end_time' => '10:00:00',
                'is_active' => true,
            ],
            [
                'title' => 'Barangay Assembly Meeting - November 2025',
                'content' => 'All residents are invited to attend the monthly Barangay Assembly Meeting. We will discuss important community matters, ongoing projects, and upcoming events. Your presence and participation are highly encouraged.',
                'type' => 'general',
                'max_slots' => null,
                'current_slots' => 0,
                'date' => Carbon::now()->addDays(10),
                'start_time' => '18:00:00',
                'end_time' => '20:00:00',
                'is_active' => true,
            ],
            [
                'title' => 'Free Dental Check-Up for Senior Citizens',
                'content' => 'Senior citizens (60 years old and above) are invited to avail FREE dental check-up and cleaning services. Slots are limited. Please bring your Senior Citizen ID.',
                'type' => 'health_related',
                'max_slots' => 40,
                'current_slots' => 40,
                'date' => Carbon::now()->addDays(3),
                'start_time' => '09:00:00',
                'end_time' => '15:00:00',
                'is_active' => true,
            ],
            [
                'title' => 'Livelihood Training Program: Sewing and Tailoring',
                'content' => 'Free sewing and tailoring training for interested residents! This 2-week program will teach basic to intermediate skills. Limited slots available. Certificate of completion will be provided.',
                'type' => 'program',
                'max_slots' => 25,
                'current_slots' => 18,
                'date' => Carbon::now()->addDays(14),
                'start_time' => '13:00:00',
                'end_time' => '17:00:00',
                'is_active' => true,
            ],
            [
                'title' => 'Basketball Tournament Registration',
                'content' => 'Calling all basketball enthusiasts! Register your team now for the upcoming Barangay Basketball Tournament. Open to all residents aged 18-45. Registration is until November 15, 2025.',
                'type' => 'event',
                'max_slots' => 12,
                'current_slots' => 9,
                'date' => Carbon::now()->addDays(20),
                'start_time' => '08:00:00',
                'end_time' => '18:00:00',
                'is_active' => true,
            ],
            [
                'title' => 'Prenatal Check-Up and Nutrition Seminar',
                'content' => 'Pregnant women are invited for FREE prenatal check-up and nutrition seminar. Our midwife will provide health advice and vitamins. Please bring your prenatal record booklet.',
                'type' => 'health_related',
                'max_slots' => 30,
                'current_slots' => 22,
                'date' => Carbon::now()->addDays(6),
                'start_time' => '08:30:00',
                'end_time' => '12:00:00',
                'is_active' => true,
            ],
            [
                'title' => 'Suspension of Classes - Heavy Rainfall Warning',
                'content' => 'Due to the heavy rainfall warning issued by PAGASA, all barangay activities and classes at the Multi-Purpose Hall are SUSPENDED today, November 3, 2025. Please stay safe indoors.',
                'type' => 'general',
                'max_slots' => null,
                'current_slots' => 0,
                'date' => Carbon::now(),
                'start_time' => null,
                'end_time' => null,
                'is_active' => true,
            ],
            [
                'title' => 'Barangay ID Application and Renewal',
                'content' => 'The Barangay Office will accept applications and renewals for Barangay IDs. Please bring 2 valid IDs, 1 recent 1x1 photo, and residence certificate. Processing time: 3-5 working days.',
                'type' => 'service',
                'max_slots' => 50,
                'current_slots' => 31,
                'date' => Carbon::now()->addDays(2),
                'start_time' => '08:00:00',
                'end_time' => '16:00:00',
                'is_active' => true,
            ],
            [
                'title' => 'Blood Pressure Monitoring for Adults',
                'content' => 'FREE blood pressure monitoring for all adults (18 years old and above). Early detection of hypertension can save lives! Walk-ins welcome at the Barangay Health Center.',
                'type' => 'health_related',
                'max_slots' => 80,
                'current_slots' => 45,
                'date' => Carbon::now()->addDays(8),
                'start_time' => '07:00:00',
                'end_time' => '11:00:00',
                'is_active' => true,
            ],
            [
                'title' => 'Skills Training: Basic Computer Literacy',
                'content' => 'Learn essential computer skills including MS Office, email, and internet browsing. This FREE 3-day training is open to all residents. Computers will be provided.',
                'type' => 'program',
                'max_slots' => 20,
                'current_slots' => 20,
                'date' => Carbon::now()->addDays(12),
                'start_time' => '14:00:00',
                'end_time' => '18:00:00',
                'is_active' => true,
            ],
            [
                'title' => 'Garbage Collection Schedule Change',
                'content' => 'Important Notice: Starting November 10, 2025, garbage collection will be every TUESDAY and FRIDAY instead of Monday and Thursday. Please segregate your waste properly. Biodegradable, Non-biodegradable, and Recyclable.',
                'type' => 'general',
                'max_slots' => null,
                'current_slots' => 0,
                'date' => Carbon::now()->addDays(7),
                'start_time' => null,
                'end_time' => null,
                'is_active' => true,
            ],
            [
                'title' => 'Annual Fiesta Celebration 2025',
                'content' => 'Save the Date! Our Annual Barangay Fiesta will be held on November 30, 2025. Expect exciting activities, cultural performances, parlor games, and prizes! More details to follow.',
                'type' => 'event',
                'max_slots' => null,
                'current_slots' => 0,
                'date' => Carbon::now()->addDays(27),
                'start_time' => '08:00:00',
                'end_time' => '20:00:00',
                'is_active' => true,
            ],
            [
                'title' => 'Free Immunization for Infants and Children',
                'content' => 'Bring your babies and children (0-5 years old) for FREE immunization! Vaccines available: BCG, Hepatitis B, Polio, DPT, Measles, and more. Please bring the immunization record booklet.',
                'type' => 'health_related',
                'max_slots' => 60,
                'current_slots' => 38,
                'date' => Carbon::now()->addDays(4),
                'start_time' => '08:00:00',
                'end_time' => '14:00:00',
                'is_active' => true,
            ],
            [
                'title' => 'Senior Citizens Monthly Meeting and Cash Assistance',
                'content' => 'All registered senior citizens are invited to the monthly meeting. Cash assistance will be distributed. Please bring your Senior Citizen ID and Community Tax Certificate (Cedula).',
                'type' => 'service',
                'max_slots' => 150,
                'current_slots' => 89,
                'date' => Carbon::now()->addDays(9),
                'start_time' => '09:00:00',
                'end_time' => '13:00:00',
                'is_active' => true,
            ],
        ];

        foreach ($announcements as $announcement) {
            Announcement::create($announcement);
        }

        $this->command->info('15 sample announcements have been created successfully!');
    }
}
