# Featured Events Setup

This guide explains how to populate the database with the 5 featured events displayed on the Events page.

## Featured Events

The application includes 5 real community events with complete details:

1. **Green Steps: Urban Restoration and Eco-Awareness Drive** (Environment)
   - Organization: EcoHope PH
   - Image: `public/images/events/green-steps.jpg`

2. **Read & Rise: Weekend Literacy Program** (Education)
   - Organization: BrightFutures Org
   - Image: `public/images/events/read-rise.jpg`

3. **Kusina at Kabuhayan: Community Outreach Program** (Community)
   - Organization: Bayanihan Hands
   - Image: `public/images/events/kusina-kabuhayan.jpg`

4. **Care Companions: Hospital Volunteer Program** (Health)
   - Organization: Heal Together PH
   - Image: `public/images/events/care-companions.jpg`

5. **Tindahan ng Pag-asa: Pop-up Livelihood Fair** (Community)
   - Organization: Bayanihan Hands
   - Image: `public/images/events/tindahan-pag-asa.jpg`

## How to Seed Featured Events

After setting up your database and running migrations, seed the featured events:

```bash
php artisan db:seed --class=FeaturedEventsSeeder
```

This will:
- Create 4 organizations (EcoHope PH, BrightFutures Org, Bayanihan Hands, Heal Together PH)
- Create user accounts for each organization
- Create 5 events with complete descriptions, responsibilities, and SDG targets
- Link event images from `public/images/events/` directory

## Viewing the Events

Once seeded, you can view:
- **Events listing**: Navigate to `/events`
- **Event details**: Click on any event card to view full details

All event images are already included in the repository at `public/images/events/`.
