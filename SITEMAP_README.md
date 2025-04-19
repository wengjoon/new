# Sitemap Generator Guide

## Command Syntax

```bash
php artisan sitemap:generate {input_file} [options]
```

## Required Parameters

- `input_file`: Path to the text file containing URLs (one URL per line)

## Optional Parameters

- `--tracking_file=PATH`: Path to the file that tracks processed URLs (default: `storage/app/sitemap/tracking.txt`)
- `--output_dir=PATH`: Directory where sitemaps will be stored (default: `public/sitemaps`)
- `--urls_per_sitemap=NUMBER`: Maximum number of URLs per sitemap file (default: 1000)

## Files Used

1. **Input File**: Contains the list of URLs to include in the sitemap (one URL per line)
   ```
   https://tiktokviewer.com/
   https://tiktokviewer.com/how-it-works
   https://tiktokviewer.com/popular-profiles
   https://tiktokviewer.com/tiktok-tips
   https://tiktokviewer.com/user/charlidamelio
   https://tiktokviewer.com/user/khaby.lame
   https://tiktokviewer.com/user/addisonre
   https://tiktokviewer.com/user/zachking
   https://tiktokviewer.com/user/bellapoarch
   https://tiktokviewer.com/user/willsmith
   ```

2. **Tracking File**: Automatically tracks processed URLs to avoid duplication

## Example Usage

To generate sitemaps for a new keyword:

1. Add new URLs to your input file (e.g., `urls.txt`)
2. Run the command:

```bash
php artisan sitemap:generate urls.txt --tracking_file=storage/app/sitemap/tracking.txt --output_dir=public/sitemaps --urls_per_sitemap=5000
```

## Process

The command:
- Reads URLs from the input file
- Compares against previously tracked URLs
- Generates sitemap XML files (maximum URLs per file as specified)
- Creates a sitemap index file
- Updates the tracking file with all processed URLs
- Updates robots.txt to reference the sitemap 