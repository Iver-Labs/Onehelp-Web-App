<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure we have some organizations first
        $this->ensureOrganizations();
        
        // Clear existing event data
        DB::table('event_images')->delete();
        DB::table('events')->delete();
        
        // Seed the 5 events
        $this->seedGreenSteps();
        $this->seedReadAndRise();
        $this->seedKusinaKabuhayan();
        $this->seedCareCompanions();
        $this->seedTindahanPagAsa();
        
        $this->command->info('✅ Events seeded successfully!');
    }
    
    private function ensureOrganizations()
    {
        $organizations = [
            ['org_name' => 'EcoHope PH', 'email' => 'ecohope@test.com', 'org_type' => 'NGO', 'description' => 'Youth-led environmental organization'],
            ['org_name' => 'BrightFutures Org', 'email' => 'brightfutures@test.com', 'org_type' => 'Non-Profit', 'description' => 'Improving literacy among underprivileged children'],
            ['org_name' => 'Bayanihan Hands', 'email' => 'bayanihan@test.com', 'org_type' => 'Community', 'description' => 'Community-driven initiative'],
            ['org_name' => 'Heal Together PH', 'email' => 'healtogether@test.com', 'org_type' => 'Non-Profit', 'description' => 'Supporting hospitals and clinics'],
        ];
        
        foreach ($organizations as $org) {
            $existing = DB::table('organizations')->where('org_name', $org['org_name'])->first();
            if (!$existing) {
                // First create user
                $userId = DB::table('users')->insertGetId([
                    'email' => $org['email'],
                    'password_hash' => \Hash::make('password'),
                    'user_type' => 'organization',
                    'is_active' => true,
                    'email_verified_at' => now(),
                    'created_at' => now(),
                    'last_login' => now(),
                ]);
                
                // Then create organization
                DB::table('organizations')->insert([
                    'user_id' => $userId,
                    'org_name' => $org['org_name'],
                    'org_type' => $org['org_type'],
                    'founded_year' => 2020,
                    'registration_number' => 'REG-' . str_pad(rand(1, 999999), 6, '0', STR_PAD_LEFT),
                    'contact_person' => 'Admin',
                    'phone' => '0917' . rand(1000000, 9999999),
                    'address' => 'Metro Manila',
                    'description' => $org['description'],
                    'rating' => 4.5,
                    'is_verified' => true,
                    'verified_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
    
    private function seedGreenSteps()
    {
        $orgId = DB::table('organizations')->where('org_name', 'EcoHope PH')->value('organization_id');
        
        $eventId = DB::table('events')->insertGetId([
            'organization_id' => $orgId,
            'event_name' => 'Green Steps: Urban Restoration and Eco-Awareness Drive',
            'description' => 'Join us in taking Green Steps toward a more sustainable Metro Manila! EcoHope PH is launching a weekend reforestation and eco-awareness campaign in Quezon City.',
            'long_description' => "Green Steps is a weekend volunteering initiative by EcoHope PH focused on urban reforestation and environmental education. Volunteers will help plant native trees and conduct eco-awareness activities with local residents and youth. The goal is to promote sustainability, restore biodiversity, and empower communities to take action for the environment — starting right here in Metro Manila.\n\nWhat are my responsibilities as a volunteer?\n🌳 Tree Planting Team – Assist in digging, planting, and watering native tree seedlings\n📚 Eco-Education Team – Facilitate short talks and games about sustainability for kids and residents\n📦 Logistics Team – Help with materials setup, registration, and clean-up\n\nWhat are the things I need to prepare?\n• Wear comfortable clothes and closed shoes suitable for outdoor work\n• Bring a refillable water bottle, hat, and eco-friendly snacks\n• Bring gardening gloves if you have them (optional)\n• Be ready to work with a team and engage with the community\n• Arrive by 7:45 AM for registration and briefing\n• Contact EcoHope PH via ecohopeph@gmail.com or message 0917-123-4567 for updates\n\nSDGs Targeted:\nSDG 13 – Climate Action: Through reforestation and carbon sequestration\nSDG 15 – Life on Land: By restoring urban biodiversity and green spaces\nSDG 11 – Sustainable Cities and Communities: Promoting eco-awareness and resilience in urban areas",
            'category' => 'Environment',
            'event_date' => '2025-11-16',
            'start_time' => '08:00:00',
            'end_time' => '12:00:00',
            'location' => 'Barangay Escopa III, Project 4, Quezon City',
            'max_volunteers' => 50,
            'registered_count' => 12,
            'status' => 'open',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        DB::table('event_images')->insert([
            'event_id' => $eventId,
            'image_url' => 'images/events/green-steps.jpg',
            'is_primary' => true,
        ]);
    }
    
    private function seedReadAndRise()
    {
        $orgId = DB::table('organizations')->where('org_name', 'BrightFutures Org')->value('organization_id');
        
        $eventId = DB::table('events')->insertGetId([
            'organization_id' => $orgId,
            'event_name' => 'Read & Rise: Weekend Literacy Program',
            'description' => 'Be a changemaker in your own city! BrightFutures Org invites you to join the Read & Rise weekend reading sessions in Metro Manila.',
            'long_description' => "Read & Rise is a weekend literacy program by BrightFutures Org that aims to uplift underprivileged children through reading support and free educational resources. Volunteers will engage children in guided reading sessions, storytelling, and basic comprehension activities. The goal is to foster literacy, boost self-esteem, and create joyful learning experiences for kids who need it most.\n\nWhat are my responsibilities as a volunteer?\n• Facilitate small-group reading sessions with children aged 5–10\n• Assist in distributing storybooks and learning materials\n• Encourage participation and help children with pronunciation and comprehension\n• Support the team in setup and clean-up before and after the session\n\nWhat are the things I need to prepare?\n• Arrive by 8:45 AM for briefing and setup\n• Wear comfortable clothes and bring a refillable water bottle\n• Bring storybooks, visual aids, or simple worksheets if you have them\n• Be patient, encouraging, and ready to engage with young learners\n• Contact BrightFutures Org via brightfutures.ph@gmail.com or message 0917-654-3210 for updates\n\nSDGs Targeted:\nSDG 4 – Quality Education: Promotes inclusive and equitable literacy development\nSDG 10 – Reduced Inequalities: Provides learning access to underserved children\nSDG 1 – No Poverty: Equips children with foundational skills for long-term empowerment",
            'category' => 'Education',
            'event_date' => '2025-11-22',
            'start_time' => '09:00:00',
            'end_time' => '11:30:00',
            'location' => 'Barangay Addition Hills Community Center, Mandaluyong City',
            'max_volunteers' => 30,
            'registered_count' => 8,
            'status' => 'open',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        DB::table('event_images')->insert([
            'event_id' => $eventId,
            'image_url' => 'images/events/read-rise.jpg',
            'is_primary' => true,
        ]);
    }
    
    private function seedKusinaKabuhayan()
    {
        $orgId = DB::table('organizations')->where('org_name', 'Bayanihan Hands')->value('organization_id');
        
        $eventId = DB::table('events')->insertGetId([
            'organization_id' => $orgId,
            'event_name' => 'Kusina at Kabuhayan: Community Outreach Program',
            'description' => 'Be part of a movement that nourishes both body and future. Bayanihan Hands invites volunteers to join this weekend outreach program combining feeding activities with livelihood workshops.',
            'long_description' => "Kusina at Kabuhayan is a weekend outreach program by Bayanihan Hands that provides nutritious meals and practical livelihood training to underserved families in Metro Manila. Volunteers will help prepare and distribute food, assist in workshop facilitation, and engage with participants to promote community empowerment and resilience.\n\nWhat are my responsibilities as a volunteer?\n🍽️ Feeding Team – Help prepare, pack, and distribute meals to families\n🧵 Workshop Support Team – Assist facilitators in livelihood sessions (e.g., soap-making, basic crafts, budgeting)\n📋 Logistics Team – Manage setup, registration, and clean-up\n\nWhat are the things I need to prepare?\n• Arrive by 7:45 AM for briefing and setup\n• Wear comfortable clothes and closed shoes\n• Bring a refillable water bottle, face towel, and personal snacks\n• Be ready to assist with food handling and workshop materials\n• Contact Bayanihan Hands via bayanihanhands@gmail.com or message 0918-765-4321 for updates\n\nSDGs Targeted:\nSDG 2 – Zero Hunger: Provides nutritious meals to food-insecure families\nSDG 8 – Decent Work and Economic Growth: Offers livelihood training for income generation\nSDG 10 – Reduced Inequalities: Empowers marginalized communities through inclusive outreach",
            'category' => 'Community',
            'event_date' => '2025-11-17',
            'start_time' => '08:00:00',
            'end_time' => '12:00:00',
            'location' => 'Barangay 201 Covered Court, Pasay City',
            'max_volunteers' => 40,
            'registered_count' => 15,
            'status' => 'open',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        DB::table('event_images')->insert([
            'event_id' => $eventId,
            'image_url' => 'images/events/kusina-kabuhayan.jpg',
            'is_primary' => true,
        ]);
    }
    
    private function seedCareCompanions()
    {
        $orgId = DB::table('organizations')->where('org_name', 'Heal Together PH')->value('organization_id');
        
        $eventId = DB::table('events')->insertGetId([
            'organization_id' => $orgId,
            'event_name' => 'Care Companions: Hospital Volunteer Program',
            'description' => 'Make healing more human. Heal Together PH invites volunteers to join the Care Companions program — a weekend initiative focused on patient care and assistance.',
            'long_description' => "Care Companions is a hospital-based volunteer program by Heal Together PH that aims to provide emotional and practical support to patients in public hospitals and clinics. Volunteers assist with non-medical tasks, offer companionship to patients, and help staff with basic administrative or logistical needs. The goal is to create a more compassionate and responsive healthcare environment for underserved communities.\n\nWhat are my responsibilities as a volunteer?\n• Assist patients with directions, forms, and basic needs\n• Offer companionship and emotional support to those waiting or alone\n• Help distribute snacks, water, or hygiene kits (if available)\n• Support hospital staff with simple tasks like organizing queues or guiding patients\n\nWhat are the things I need to prepare?\n• Arrive by 8:45 AM for briefing and coordination\n• Wear comfortable clothes and closed shoes (preferably white or neutral)\n• Bring a valid ID and refillable water bottle\n• Be respectful, calm, and ready to engage with patients and staff\n• Contact Heal Together PH via healtogetherph@gmail.com or message 0917-456-7890 for updates\n\nSDGs Targeted:\nSDG 3 – Good Health and Well-being: Enhances patient experience and access to care\nSDG 10 – Reduced Inequalities: Supports vulnerable patients in public healthcare settings\nSDG 17 – Partnerships for the Goals: Strengthens collaboration between communities and health institutions",
            'category' => 'Health',
            'event_date' => '2025-11-23',
            'start_time' => '09:00:00',
            'end_time' => '12:00:00',
            'location' => 'Outpatient Wing, Pasay City General Hospital',
            'max_volunteers' => 25,
            'registered_count' => 10,
            'status' => 'open',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        DB::table('event_images')->insert([
            'event_id' => $eventId,
            'image_url' => 'images/events/care-companions.jpg',
            'is_primary' => true,
        ]);
    }
    
    private function seedTindahanPagAsa()
    {
        $orgId = DB::table('organizations')->where('org_name', 'Bayanihan Hands')->value('organization_id');
        
        $eventId = DB::table('events')->insertGetId([
            'organization_id' => $orgId,
            'event_name' => 'Tindahan ng Pag-asa: Pop-up Livelihood Fair',
            'description' => 'Help families build sustainable futures! Bayanihan Hands is launching Tindahan ng Pag-asa, a weekend pop-up livelihood fair in Metro Manila showcasing community-made products.',
            'long_description' => "Tindahan ng Pag-asa is a community-based livelihood fair organized by Bayanihan Hands to empower low-income families through entrepreneurship and skill-building. The event features pop-up stalls selling handmade goods, food items, and crafts, alongside mini-workshops on budgeting, marketing, and product development. Volunteers help create a welcoming and organized space where families can learn, earn, and grow.\n\nWhat are my responsibilities as a volunteer?\n🛍️ Booth Support Team – Help vendors set up stalls and display products\n📣 Workshop Assistance Team – Support facilitators during mini livelihood sessions\n🧾 Logistics Team – Manage registration, crowd flow, and clean-up\n\nWhat are the things I need to prepare?\n• Arrive by 8:30 AM for briefing and setup\n• Wear comfortable clothes and closed shoes\n• Bring a refillable water bottle, face towel, and personal snacks\n• Be ready to assist with booth setup and participant engagement\n• Contact Bayanihan Hands via bayanihanhands@gmail.com or message 0918-765-4321 for updates\n\nSDGs Targeted:\nSDG 8 – Decent Work and Economic Growth: Promotes entrepreneurship and skill-building\nSDG 1 – No Poverty: Supports income generation for low-income families\nSDG 10 – Reduced Inequalities: Creates inclusive opportunities for economic participation",
            'category' => 'Community',
            'event_date' => '2025-11-10',
            'start_time' => '09:00:00',
            'end_time' => '13:00:00',
            'location' => 'Barangay Hall Grounds, Brgy. Bagong Silang, Caloocan City',
            'max_volunteers' => 35,
            'registered_count' => 20,
            'status' => 'open',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        DB::table('event_images')->insert([
            'event_id' => $eventId,
            'image_url' => 'images/events/tindahan-pag-asa.jpg',
            'is_primary' => true,
        ]);
    }
}
