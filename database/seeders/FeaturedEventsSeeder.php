<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Organization;
use App\Models\Event;
use App\Models\EventImage;
use Illuminate\Support\Facades\Hash;

class FeaturedEventsSeeder extends Seeder
{
    public function run(): void
    {
        // Create organizations for the events
        $organizations = [
            [
                'org_name' => 'EcoHope PH',
                'org_type' => 'Environmental Organization',
                'email' => 'ecohopeph@gmail.com',
                'phone' => '0917-123-4567',
            ],
            [
                'org_name' => 'BrightFutures Org',
                'org_type' => 'Education & Literacy',
                'email' => 'brightfutures.ph@gmail.com',
                'phone' => '0917-654-3210',
            ],
            [
                'org_name' => 'Bayanihan Hands',
                'org_type' => 'Community Development',
                'email' => 'bayanihanhands@gmail.com',
                'phone' => '0918-765-4321',
            ],
            [
                'org_name' => 'Heal Together PH',
                'org_type' => 'Healthcare Support',
                'email' => 'healtogetherph@gmail.com',
                'phone' => '0917-456-7890',
            ],
        ];

        $createdOrgs = [];
        foreach ($organizations as $orgData) {
            // Create user for organization
            $user = User::create([
                'email' => $orgData['email'],
                'password_hash' => Hash::make('password123'),
                'user_type' => 'organization',
                'created_at' => now(),
                'is_active' => true,
            ]);
            
            // Set email_verified_at separately since it's not in fillable
            $user->email_verified_at = now();
            $user->save();

            // Create organization
            $org = Organization::create([
                'user_id' => $user->user_id,
                'org_name' => $orgData['org_name'],
                'org_type' => $orgData['org_type'],
                'contact_person' => 'Admin',
                'phone' => $orgData['phone'],
                'is_verified' => true,
                'verified_at' => now(),
            ]);

            $createdOrgs[$orgData['org_name']] = $org;
        }

        // Create events with full details
        $events = [
            [
                'organization' => 'EcoHope PH',
                'event_name' => 'Green Steps: Urban Restoration and Eco-Awareness Drive',
                'description' => "Join us in taking Green Steps toward a more sustainable Metro Manila! EcoHope PH, a youth-led environmental organization, is launching a weekend reforestation and eco-awareness campaign in Quezon City. This initiative aims to restore green spaces and educate communities about sustainable living — one tree and one conversation at a time.

We're calling on passionate volunteers to help us plant, teach, and inspire. Whether you're into hands-on work or community engagement, there's a role for you in this movement.

WHAT IS THE EVENT ABOUT?

Green Steps is a weekend volunteering initiative by EcoHope PH focused on urban reforestation and environmental education. Volunteers will help plant native trees and conduct eco-awareness activities with local residents and youth. The goal is to promote sustainability, restore biodiversity, and empower communities to take action for the environment — starting right here in Metro Manila.

VOLUNTEER RESPONSIBILITIES

As a Green Steps Volunteer, you'll be assigned to one of the following teams:
🌳 Tree Planting Team – Assist in digging, planting, and watering native tree seedlings
📚 Eco-Education Team – Facilitate short talks and games about sustainability for kids and residents
📦 Logistics Team – Help with materials setup, registration, and clean-up

You'll also participate in a short orientation and reflection session to deepen your understanding of environmental action.

WHAT TO PREPARE

To participate effectively, please:
• Wear comfortable clothes and closed shoes suitable for outdoor work
• Bring a refillable water bottle, hat, and eco-friendly snacks
• Bring gardening gloves if you have them (optional)
• Be ready to work with a team and engage with the community
• Arrive by 7:45 AM for registration and briefing

Contact EcoHope PH via ecohopeph@gmail.com or message 0917-123-4567 for updates

SDG TARGETS

• SDG 13 – Climate Action: Through reforestation and carbon sequestration
• SDG 15 – Life on Land: By restoring urban biodiversity and green spaces
• SDG 11 – Sustainable Cities and Communities: Promoting eco-awareness and resilience in urban areas",
                'category' => 'Environment',
                'event_date' => '2025-11-16',
                'start_time' => '08:00:00',
                'end_time' => '12:00:00',
                'location' => 'Barangay Escopa III, Project 4, Quezon City',
                'max_volunteers' => 50,
                'image' => 'green-steps.jpg',
            ],
            [
                'organization' => 'BrightFutures Org',
                'event_name' => 'Read & Rise: Weekend Literacy Program',
                'description' => "Be a changemaker in your own city! BrightFutures Org, a nonprofit committed to improving literacy among underprivileged children, invites you to join the Read & Rise weekend reading sessions in Metro Manila. This initiative provides free educational materials and guided reading support to help children build confidence, comprehension, and a lifelong love of learning.

Volunteers will help facilitate reading circles, distribute materials, and mentor young learners in a safe and nurturing environment.

WHAT IS THE EVENT ABOUT?

Read & Rise is a weekend literacy program by BrightFutures Org that aims to uplift underprivileged children through reading support and free educational resources. Volunteers will engage children in guided reading sessions, storytelling, and basic comprehension activities. The goal is to foster literacy, boost self-esteem, and create joyful learning experiences for kids who need it most.

VOLUNTEER RESPONSIBILITIES

As a Literacy Volunteer, you'll:
• Facilitate small-group reading sessions with children aged 5–10
• Assist in distributing storybooks and learning materials
• Encourage participation and help children with pronunciation and comprehension
• Support the team in setup and clean-up before and after the session

WHAT TO PREPARE

To participate effectively, please:
• Arrive by 8:45 AM for briefing and setup
• Wear comfortable clothes and bring a refillable water bottle
• Bring storybooks, visual aids, or simple worksheets if you have them
• Be patient, encouraging, and ready to engage with young learners

Contact BrightFutures Org via brightfutures.ph@gmail.com or message 0917-654-3210 for updates or cancellations

SDG TARGETS

• SDG 4 – Quality Education: Promotes inclusive and equitable literacy development
• SDG 10 – Reduced Inequalities: Provides learning access to underserved children
• SDG 1 – No Poverty: Equips children with foundational skills for long-term empowerment",
                'category' => 'Education',
                'event_date' => '2025-11-09',
                'start_time' => '09:00:00',
                'end_time' => '11:30:00',
                'location' => 'Barangay Addition Hills Community Center, Mandaluyong City',
                'max_volunteers' => 30,
                'image' => 'read-rise.jpg',
            ],
            [
                'organization' => 'Bayanihan Hands',
                'event_name' => 'Kusina at Kabuhayan: Community Outreach Program',
                'description' => "Be part of a movement that nourishes both body and future. Bayanihan Hands, a community-driven initiative, invites volunteers to join Kusina at Kabuhayan, a weekend outreach program in Metro Manila that combines feeding activities with hands-on livelihood workshops for low-income families. This is your chance to serve, share, and spark hope in local communities.

WHAT IS THE EVENT ABOUT?

Kusina at Kabuhayan is a weekend outreach program by Bayanihan Hands that provides nutritious meals and practical livelihood training to underserved families in Metro Manila. Volunteers will help prepare and distribute food, assist in workshop facilitation, and engage with participants to promote community empowerment and resilience.

VOLUNTEER RESPONSIBILITIES

As a Community Outreach Volunteer, you'll be assigned to one of the following teams:
🍽️ Feeding Team – Help prepare, pack, and distribute meals to families
🧵 Workshop Support Team – Assist facilitators in livelihood sessions (e.g., soap-making, basic crafts, budgeting)
📋 Logistics Team – Manage setup, registration, and clean-up

You'll also participate in a short orientation and reflection session to connect with the mission and community.

WHAT TO PREPARE

To participate effectively, please:
• Arrive by 7:45 AM for briefing and setup
• Wear comfortable clothes and closed shoes
• Bring a refillable water bottle, face towel, and personal snacks
• Be ready to assist with food handling and workshop materials

Contact Bayanihan Hands via bayanihanhands@gmail.com or message 0918-765-4321 for updates or cancellations

SDG TARGETS

• SDG 2 – Zero Hunger: Provides nutritious meals to food-insecure families
• SDG 8 – Decent Work and Economic Growth: Offers livelihood training for income generation
• SDG 10 – Reduced Inequalities: Empowers marginalized communities through inclusive outreach",
                'category' => 'Community',
                'event_date' => '2025-11-17',
                'start_time' => '08:00:00',
                'end_time' => '12:00:00',
                'location' => 'Barangay 201 Covered Court, Pasay City',
                'max_volunteers' => 40,
                'image' => 'kusina-kabuhayan.jpg',
            ],
            [
                'organization' => 'Heal Together PH',
                'event_name' => 'Care Companions: Hospital Volunteer Program',
                'description' => "Make healing more human. Heal Together PH, a nonprofit organization supporting hospitals and clinics, invites volunteers to join the Care Companions program — a weekend initiative in Metro Manila focused on patient care and assistance. Volunteers will help ease the hospital experience for patients by offering support, companionship, and practical help in clinical settings.

WHAT IS THE EVENT ABOUT?

Care Companions is a hospital-based volunteer program by Heal Together PH that aims to provide emotional and practical support to patients in public hospitals and clinics. Volunteers assist with non-medical tasks, offer companionship to patients, and help staff with basic administrative or logistical needs. The goal is to create a more compassionate and responsive healthcare environment for underserved communities.

VOLUNTEER RESPONSIBILITIES

As a Care Companion Volunteer, you'll:
• Assist patients with directions, forms, and basic needs
• Offer companionship and emotional support to those waiting or alone
• Help distribute snacks, water, or hygiene kits (if available)
• Support hospital staff with simple tasks like organizing queues or guiding patients

WHAT TO PREPARE

To participate effectively, please:
• Arrive by 8:45 AM for briefing and coordination
• Wear comfortable clothes and closed shoes (preferably white or neutral)
• Bring a valid ID and refillable water bottle
• Be respectful, calm, and ready to engage with patients and staff

Contact Heal Together PH via healtogetherph@gmail.com or message 0917-456-7890 for updates or cancellations

SDG TARGETS

• SDG 3 – Good Health and Well-being: Enhances patient experience and access to care
• SDG 10 – Reduced Inequalities: Supports vulnerable patients in public healthcare settings
• SDG 17 – Partnerships for the Goals: Strengthens collaboration between communities and health institutions",
                'category' => 'Health',
                'event_date' => '2025-11-08',
                'start_time' => '09:00:00',
                'end_time' => '12:00:00',
                'location' => 'Outpatient Wing, Pasay City General Hospital',
                'max_volunteers' => 25,
                'image' => 'care-companions.jpg',
            ],
            [
                'organization' => 'Bayanihan Hands',
                'event_name' => 'Tindahan ng Pag-asa: Pop-up Livelihood Fair',
                'description' => "Help families build sustainable futures! Bayanihan Hands is launching Tindahan ng Pag-asa, a weekend pop-up livelihood fair in Metro Manila that showcases community-made products and offers hands-on workshops for low-income families. Volunteers will assist in organizing booths, guiding participants, and supporting local entrepreneurs as they take steps toward financial independence.

WHAT IS THE EVENT ABOUT?

Tindahan ng Pag-asa is a community-based livelihood fair organized by Bayanihan Hands to empower low-income families through entrepreneurship and skill-building. The event features pop-up stalls selling handmade goods, food items, and crafts, alongside mini-workshops on budgeting, marketing, and product development. Volunteers help create a welcoming and organized space where families can learn, earn, and grow.

VOLUNTEER RESPONSIBILITIES

As a Livelihood Fair Volunteer, you'll be assigned to one of the following teams:
🛍️ Booth Support Team – Help vendors set up stalls and display products
📣 Workshop Assistance Team – Support facilitators during mini livelihood sessions
🧾 Logistics Team – Manage registration, crowd flow, and clean-up

You'll also join a short orientation and reflection session to connect with the mission and community.

WHAT TO PREPARE

To participate effectively, please:
• Arrive by 8:30 AM for briefing and setup
• Wear comfortable clothes and closed shoes
• Bring a refillable water bottle, face towel, and personal snacks
• Be ready to assist with booth setup and participant engagement

Contact Bayanihan Hands via bayanihanhands@gmail.com or message 0918-765-4321 for updates or cancellations

SDG TARGETS

• SDG 8 – Decent Work and Economic Growth: Promotes entrepreneurship and skill-building
• SDG 1 – No Poverty: Supports income generation for low-income families
• SDG 10 – Reduced Inequalities: Creates inclusive opportunities for economic participation",
                'category' => 'Community',
                'event_date' => '2025-11-10',
                'start_time' => '09:00:00',
                'end_time' => '13:00:00',
                'location' => 'Barangay Hall Grounds, Brgy. Bagong Silang, Caloocan City',
                'max_volunteers' => 35,
                'image' => 'tindahan-pag-asa.jpg',
            ],
        ];

        foreach ($events as $eventData) {
            $org = $createdOrgs[$eventData['organization']];

            $event = Event::create([
                'organization_id' => $org->organization_id,
                'event_name' => $eventData['event_name'],
                'description' => $eventData['description'],
                'category' => $eventData['category'],
                'event_date' => $eventData['event_date'],
                'start_time' => $eventData['start_time'],
                'end_time' => $eventData['end_time'],
                'location' => $eventData['location'],
                'max_volunteers' => $eventData['max_volunteers'],
                'status' => 'open',
            ]);

            // Create event image
            EventImage::create([
                'event_id' => $event->event_id,
                'image_url' => 'images/events/' . $eventData['image'],
                'is_primary' => true,
            ]);
        }
    }
}
