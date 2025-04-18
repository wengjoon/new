# Google Analytics Integration

This project includes Google Analytics integration for tracking user behavior on your website.

## Setup Instructions

1. Create a Google Analytics account at [analytics.google.com](https://analytics.google.com) if you don't have one already.

2. Create a new property in your Google Analytics account.

3. During the setup process, you'll receive a Measurement ID that looks like `G-XXXXXXXXXX`.

4. Add your Measurement ID to the `.env` file:

   ```
   GOOGLE_ANALYTICS_ID=G-XXXXXXXXXX
   ```

5. Clear your configuration cache to ensure the changes take effect:

   ```bash
   php artisan config:clear
   ```

## How It Works

The Google Analytics integration works as follows:

1. The `config/analytics.php` configuration file reads the Measurement ID from the `.env` file.

2. A meta tag is included in the HTML head with the Measurement ID:

   ```html
   <meta name="google-analytics-id" content="{{ config('analytics.measurement_id') }}">
   ```

3. The JavaScript code in `resources/js/analytics.js` initializes Google Analytics with this ID.

4. This ensures that page views and other events are tracked in your Google Analytics dashboard.

## Customization

If you need to track custom events beyond page views, you can modify the `resources/js/analytics.js` file to include additional tracking code. For example:

```javascript
// Track a custom event
export function trackEvent(category, action, label, value) {
    if (window.dataLayer) {
        window.dataLayer.push({
            'event': 'custom_event',
            'event_category': category,
            'event_action': action,
            'event_label': label,
            'event_value': value
        });
    }
}
```

Then, import and use this function in your JavaScript code:

```javascript
import { trackEvent } from './analytics';

// Example: Track a button click
document.querySelector('#submit-button').addEventListener('click', () => {
    trackEvent('User Interaction', 'Button Click', 'Submit Button', 1);
});
```

## Privacy Considerations

Make sure your website includes a privacy policy that discloses your use of Google Analytics and how you handle user data. This is especially important for compliance with privacy regulations like GDPR and CCPA. 