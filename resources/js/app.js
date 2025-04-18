import { initializeGoogleAnalytics } from './analytics';
import './bootstrap';

// Initialize Google Analytics with the measurement ID from Laravel config
const analyticsId = document.head.querySelector('meta[name="google-analytics-id"]')?.getAttribute('content');
if (analyticsId) {
    initializeGoogleAnalytics(analyticsId);
}
