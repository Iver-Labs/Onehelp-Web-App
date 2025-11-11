# AI Feature Documentation - Event Description Generator

## Overview

The **AI-Powered Event Description Generator** is a new feature in OneHelp that helps organizations create compelling, well-structured event descriptions automatically. This feature saves time and improves the quality of event listings, making it easier to attract volunteers.

## Location in the Application

**User Interface:**
- Available in: **Organization Dashboard → Create Event** page
- Route: `/organization/events/create`
- Visual Element: Purple gradient button labeled "Generate with AI" ⚡

**Backend:**
- Controller: `app/Http/Controllers/AIAssistantController.php`
- API Endpoint: `POST /api/ai/generate-event-description`
- Route File: `routes/api.php`

## How to Use

### For Organizations

1. **Navigate to Create Event** page from your organization dashboard
2. **Fill in basic event information**:
   - Event Name (required)
   - Category (optional, but recommended)
   - Location (optional, but recommended)
3. **Click the "Generate with AI" button** next to the Description field
4. **Wait for generation** (typically 1-3 seconds)
5. **Review the generated description** in the text area
6. **Edit if needed** - the generated text is fully editable
7. **Regenerate** if you want a different version by clicking the button again

### User Experience Features

- ⚡ **Smart Button**: Purple gradient with lightning bolt icon
- 🔄 **Loading State**: Spinner animation during generation
- ✅ **Success Feedback**: Green border flash + notification
- ❌ **Error Handling**: Clear error messages if something goes wrong
- 💡 **Helpful Tips**: Guidance text below the description field

## Technical Implementation

### Two Modes of Operation

#### 1. **AI Mode** (with OpenAI API Key)
When `OPENAI_API_KEY` is configured in `.env`:
- Uses OpenAI's GPT-3.5-turbo model
- Generates dynamic, creative descriptions
- Tailored to specific event details
- More natural language variation

#### 2. **Template Mode** (without API Key)
When no API key is configured:
- Uses intelligent category-specific templates
- Still produces high-quality descriptions
- Predictable, professional output
- Works completely offline

### API Specification

**Endpoint:** `POST /api/ai/generate-event-description`

**Authentication:** Required (api.auth middleware)

**Request Body:**
```json
{
  "event_name": "Beach Cleanup Drive",
  "category": "Environment",
  "location": "Santa Monica Beach"
}
```

**Response (Success):**
```json
{
  "success": true,
  "description": "Join us for Beach Cleanup Drive, an exciting opportunity to make a tangible difference in our environment!..."
}
```

**Response (Error):**
```json
{
  "success": false,
  "message": "Failed to generate description. Please try again."
}
```

### Supported Categories

The AI generates specialized content for these categories:
- **Environment**: Focus on conservation, cleanup, habitat restoration
- **Education**: Emphasis on learning, mentorship, tutoring
- **Health**: Healthcare support, wellness activities, patient care
- **Community**: Neighborhood building, social programs, local support
- **Animals**: Animal care, shelter work, wildlife rehabilitation

## Setup & Configuration

### Basic Setup (Template Mode)
No configuration needed! The feature works out of the box using intelligent templates.

### Advanced Setup (AI Mode with OpenAI)

1. **Get an OpenAI API Key**
   - Visit https://platform.openai.com/api-keys
   - Create an account or sign in
   - Generate a new API key

2. **Add the key to your environment**
   ```bash
   # In your .env file
   OPENAI_API_KEY=sk-your-api-key-here
   ```

3. **Clear the config cache**
   ```bash
   php artisan config:clear
   ```

4. **Test the feature**
   - Create a new event
   - Click "Generate with AI"
   - Verify it generates descriptions

### Cost Considerations (AI Mode)

- Using OpenAI API incurs costs based on usage
- Typical cost: $0.002 per description (very low)
- Set up billing limits in OpenAI dashboard
- Template mode is completely free

## Testing

### Running Tests

```bash
# Run only AI feature tests
php artisan test --filter=AIAssistantTest

# Run all tests
php artisan test
```

### Test Coverage

The feature includes 6 comprehensive tests:
- ✅ Authentication requirement verification
- ✅ Input validation testing
- ✅ Minimal data generation
- ✅ Full data generation
- ✅ Category-specific content validation
- ✅ User role access control

## Security Features

- ✅ **Authentication Required**: Only logged-in users can access
- ✅ **Input Validation**: All inputs are validated and sanitized
- ✅ **Rate Limiting**: Protected by Laravel's throttling
- ✅ **Error Handling**: Graceful degradation on failures
- ✅ **API Key Security**: Stored in environment, not in code
- ✅ **CSRF Protection**: Token validation on all requests

## Troubleshooting

### Issue: Button does nothing when clicked

**Solution:**
- Check browser console for JavaScript errors
- Ensure CSRF token is present in the page
- Verify you're logged in as an organization

### Issue: "Failed to generate description" error

**Possible Causes:**
1. OpenAI API key is invalid (AI mode)
2. OpenAI API is down (AI mode)
3. Network connectivity issues

**Solution:**
- Check `.env` file for correct API key
- Test API key at https://platform.openai.com
- The system will fall back to templates automatically
- Check Laravel logs: `storage/logs/laravel.log`

### Issue: Generated descriptions are generic

**Solution:**
- Fill in event name, category, and location before generating
- More details = better descriptions
- Try regenerating for variations
- Consider using AI mode with OpenAI API key for more dynamic content

## Performance

- **Template Mode**: ~50ms (instant)
- **AI Mode**: 1-3 seconds (OpenAI API call)
- **Concurrent Requests**: Handled by Laravel queue system
- **Caching**: Currently not implemented (descriptions are unique)

## Future Enhancements

Potential improvements for future versions:
- [ ] Support for more AI providers (Google Gemini, Anthropic Claude)
- [ ] Multi-language description generation
- [ ] Tone selection (formal, casual, enthusiastic)
- [ ] Description length control (short, medium, long)
- [ ] Learning from edited descriptions to improve templates
- [ ] Batch generation for multiple events

## Support

For issues, questions, or feature requests:
- **GitHub Issues**: Create an issue in the repository
- **Documentation**: Check README.md and other docs
- **Logs**: Review `storage/logs/laravel.log` for errors

## Credits

Implemented as part of the OneHelp Volunteer Management System enhancement project.

---

**Last Updated:** November 10, 2025
**Feature Version:** 1.0
**Compatible with:** Laravel 12, PHP 8.2+
