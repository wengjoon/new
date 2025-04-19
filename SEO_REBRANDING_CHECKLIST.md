# SEO Rebranding Checklist

This document provides a comprehensive list of files you need to modify when rebranding your TikTok Viewer site for SEO purposes. Each section includes the exact file path and what needs to be changed.

## Content & Meta Tags

### 1. Layout Template
**File Path:** `resources/views/layouts/app.blade.php`
- Update site name in title tags
- Update meta description if it contains the brand name
- Update Open Graph meta tags

### 2. Home Page
**File Path:** `resources/views/home.blade.php`
- Update page title and meta description in the `@section('meta-tags')` block
- Update main headings to include new keywords
- Update body content to reflect new branding/keywords
- Update any branded CTAs or buttons

### 3. Popular Profiles Page
**File Path:** `resources/views/pages/popular-profiles.blade.php`
- Update meta tags in `@section('meta-tags')` block
- Update page title and description
- Update schema.org JSON-LD markup at the bottom

### 4. How It Works Page
**File Path:** `resources/views/pages/how-it-works.blade.php`
- Update meta tags in `@section('meta-tags')` block
- Update page title and description
- Update any branded content in the explanations

### 5. TikTok Tips Page
**File Path:** `resources/views/pages/tiktok-tips.blade.php`
- Update meta tags in `@section('meta-tags')` block
- Update page title and description
- Update any content mentioning the old brand name

### 6. User Profile Page
**File Path:** `resources/views/profile.blade.php`
- Update meta tags in `@section('meta-tags')` block
- Update any branded content in the template

## Routes & URLs

### 7. Web Routes File
**File Path:** `routes/web.php`
- Update any route names that contain the brand name
- Update URL patterns if they include the brand name

## Configuration Files

### 8. Application Config
**File Path:** `config/app.php`
- Update the `name` parameter with the new brand name
- Update the `url` parameter if the domain is changing

### 9. Meta Tags for Social Sharing
**File Path:** `public/images/tiktok-viewer-og.jpg`
- Replace with new branded image for social sharing
- Update dimensions to 1200×630 pixels for optimal display

## Controllers

### 10. TikTok Controller
**File Path:** `app/Http/Controllers/TikTokController.php`
- Update any hardcoded meta titles, descriptions
- Update any branded content in the responses

## Assets

### 11. Favicon
**File Path:** `public/favicon.ico`
- Replace with new brand icon

### 12. Logo
**File Path:** `public/images/logo.png` (if it exists)
- Replace with new brand logo

## Footer & Credits

### 13. Footer Information
**File Path:** `resources/views/layouts/app.blade.php` (footer section)
- Update copyright information
- Update brand name in footer text
- Update any legal links (privacy policy, terms of service)

## Schema.org Markup

### 14. Site-wide Schema
**File Path:** All view files with `@section('schema-markup')` sections
- Update Organization/Website schema with new brand name
- Update WebSite name in JSON-LD

## Robots.txt & Sitemap

### 15. Robots.txt
**File Path:** `public/robots.txt`
- If the site name is in comments, update them
- Ensure sitemap URL is correct if domain is changing

### 16. Sitemap URLs
**File Path:** Your sitemap input file (e.g., `urls.txt`)
- Update all URLs to reflect new domain if applicable
- Run the sitemap generator after making changes:
  ```
  php artisan sitemap:generate urls.txt
  ```

## Application Messaging

### 17. Error Messages
**File Path:** `resources/lang/en/messages.php` (if it exists)
- Update any branded error messages or notifications

### 18. Email Templates
**File Path:** `resources/views/emails/` (if email functionality exists)
- Update email templates with new branding
- Update email signatures and footers

## Implementation Process

1. Create a backup of your site
2. Make all the above changes
3. Test the site thoroughly after changes
4. Monitor rankings closely after rebranding
5. Submit the new sitemap to search engines
6. Set up proper redirects if URLs have changed

## SEO Follow-up Actions

- Inform Google of branding change via Search Console
- Update any external profiles (social media, directories)
- Update any backlinks where possible
- Monitor for 404 errors that might indicate missed URL updates 