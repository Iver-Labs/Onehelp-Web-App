<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIAssistantController extends Controller
{
    /**
     * Generate an event description using AI.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function generateEventDescription(Request $request)
    {
        $request->validate([
            'event_name' => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'location' => 'nullable|string|max:255',
        ]);

        $eventName = $request->input('event_name');
        $category = $request->input('category', 'Community Service');
        $location = $request->input('location', '');

        try {
            // Use a simple AI-like generation with GPT-style prompting
            // In production, this would connect to OpenAI, Gemini, or similar API
            $description = $this->generateDescription($eventName, $category, $location);

            return response()->json([
                'success' => true,
                'description' => $description,
            ]);
        } catch (\Exception $e) {
            Log::error('AI Description Generation Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to generate description. Please try again.',
            ], 500);
        }
    }

    /**
     * Generate a description based on event details.
     * This is a template-based approach. In production, replace with actual AI API calls.
     *
     * @param string $eventName
     * @param string $category
     * @param string $location
     * @return string
     */
    private function generateDescription($eventName, $category, $location)
    {
        // Check if AI API is configured
        $apiKey = config('services.openai.api_key');
        
        if ($apiKey && !empty($apiKey)) {
            // Use OpenAI API for real AI generation
            return $this->generateWithOpenAI($eventName, $category, $location, $apiKey);
        }

        // Fallback to template-based generation
        return $this->generateWithTemplate($eventName, $category, $location);
    }

    /**
     * Generate description using OpenAI API.
     *
     * @param string $eventName
     * @param string $category
     * @param string $location
     * @param string $apiKey
     * @return string
     */
    private function generateWithOpenAI($eventName, $category, $location, $apiKey)
    {
        $prompt = "Write a compelling and engaging volunteer event description for an event called '{$eventName}' in the {$category} category";
        
        if (!empty($location)) {
            $prompt .= " located in {$location}";
        }
        
        $prompt .= ". The description should:\n";
        $prompt .= "1. Explain what volunteers will do\n";
        $prompt .= "2. Highlight the impact and importance of the work\n";
        $prompt .= "3. Be motivating and encouraging\n";
        $prompt .= "4. Be 3-4 paragraphs long\n";
        $prompt .= "5. Include specific activities volunteers will participate in\n";
        $prompt .= "\nWrite in a warm, welcoming tone that inspires people to volunteer.";

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(30)->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are a helpful assistant that writes compelling volunteer event descriptions for a volunteer management platform.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'max_tokens' => 500,
                'temperature' => 0.7,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return trim($data['choices'][0]['message']['content']);
            }

            // Fallback if API call fails
            Log::warning('OpenAI API call failed: ' . $response->body());
            return $this->generateWithTemplate($eventName, $category, $location);
        } catch (\Exception $e) {
            Log::error('OpenAI API Error: ' . $e->getMessage());
            return $this->generateWithTemplate($eventName, $category, $location);
        }
    }

    /**
     * Generate description using template (fallback method).
     *
     * @param string $eventName
     * @param string $category
     * @param string $location
     * @return string
     */
    private function generateWithTemplate($eventName, $category, $location)
    {
        $templates = [
            'Environment' => [
                'intro' => "Join us for {$eventName}, an exciting opportunity to make a tangible difference in our environment! This hands-on event brings together passionate individuals committed to preserving our natural spaces.",
                'activities' => "Volunteers will participate in activities such as litter collection, habitat restoration, planting native species, and environmental education. Whether you're picking up trash, removing invasive plants, or helping to create awareness, every action counts.",
                'impact' => "Your participation directly contributes to a healthier ecosystem and cleaner community. Studies show that volunteer-led environmental initiatives have a lasting positive impact on both wildlife habitats and community engagement with nature.",
                'closing' => "No prior experience is necessary – just bring your enthusiasm and willingness to help! We'll provide all necessary equipment, training, and refreshments. Together, we can create a cleaner, greener future for our community.",
            ],
            'Education' => [
                'intro' => "Be part of {$eventName} and help shape the future through education! This rewarding opportunity allows you to mentor, teach, and inspire learners in our community.",
                'activities' => "Volunteers will engage in activities such as tutoring students, assisting with educational workshops, organizing learning materials, and providing one-on-one support. Your guidance can help unlock potential and build confidence in learners of all ages.",
                'impact' => "Education volunteers make a profound difference in people's lives. Research shows that mentorship and educational support significantly improve academic performance, self-esteem, and future opportunities for learners.",
                'closing' => "Whether you're a experienced educator or simply passionate about learning, your contribution matters! We welcome volunteers with diverse backgrounds and skills. Join us in empowering our community through education.",
            ],
            'Health' => [
                'intro' => "Make a difference in people's lives at {$eventName}! This impactful event focuses on promoting health and wellness in our community through compassionate volunteer service.",
                'activities' => "Volunteers will assist with health screenings, organize wellness activities, provide companionship to patients, help with administrative tasks, or support healthcare professionals. Every role is vital in delivering quality care and support.",
                'impact' => "Your involvement directly improves the health outcomes and quality of life for community members. Healthcare volunteers report high levels of personal fulfillment and make meaningful connections while serving others.",
                'closing' => "No medical background required – we need caring individuals willing to help! Comprehensive training and supervision will be provided. Come be part of a team that's making health and wellness accessible to all.",
            ],
            'Community' => [
                'intro' => "Join {$eventName} and become part of something bigger! This community-focused event brings neighbors together to strengthen bonds and create positive change where we live.",
                'activities' => "Volunteers will participate in activities such as organizing community gatherings, assisting local residents, coordinating resources, beautifying public spaces, or supporting community programs. Your helping hands will create ripples of positive impact.",
                'impact' => "Community volunteering strengthens social connections, reduces isolation, and creates a stronger, more resilient neighborhood. When we work together, we build the kind of community where everyone thrives.",
                'closing' => "Everyone is welcome – no special skills required, just a desire to help! We'll provide orientation, guidance, and all necessary supplies. Together, we can make our community a better place for all.",
            ],
            'Animals' => [
                'intro' => "Animal lovers, unite! {$eventName} is your chance to make a real difference in the lives of animals in need. Join us for this rewarding hands-on experience with our furry, feathered, or scaled friends.",
                'activities' => "Volunteers will help with tasks such as feeding and caring for animals, cleaning habitats, socializing with shelter animals, assisting with adoption events, or supporting wildlife rehabilitation efforts. Every act of kindness helps an animal in need.",
                'impact' => "Your volunteer work directly improves animal welfare and increases adoption rates. Socialized, well-cared-for animals have better chances of finding forever homes, and your compassion makes all the difference in their lives.",
                'closing' => "Whether you're experienced with animals or just getting started, we welcome your help! All training will be provided, and you'll be supervised throughout. Come share your love for animals and help give them the care they deserve.",
            ],
        ];

        $template = $templates[$category] ?? $templates['Community'];
        
        $locationText = !empty($location) ? " in {$location}" : " in our community";
        
        $description = str_replace('{$eventName}', $eventName, $template['intro']);
        $description = str_replace(' in our', $locationText . ' and our', $description);
        
        $description .= "\n\n" . $template['activities'];
        $description .= "\n\n" . $template['impact'];
        $description .= "\n\n" . $template['closing'];

        return $description;
    }
}
