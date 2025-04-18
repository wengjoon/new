<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class GenerateSitemapsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate 
                            {input_file : Path to the input file containing URLs} 
                            {--tracking_file=storage/app/sitemap/tracking.txt : Path to the tracking file}
                            {--output_dir=public/sitemaps : Directory where sitemaps will be stored}
                            {--urls_per_sitemap=1000 : Maximum number of URLs per sitemap file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate sitemaps from a text file of URLs';

    /**
     * Counters for tracking progress
     */
    protected $totalUrlsProcessed = 0;
    protected $newUrlsProcessed = 0;
    protected $sitemapsGenerated = 0;

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $startTime = microtime(true);
        $inputFile = $this->argument('input_file');
        $trackingFile = $this->option('tracking_file');
        $outputDir = $this->option('output_dir');
        $urlsPerSitemap = (int) $this->option('urls_per_sitemap');

        $this->info("Starting sitemap generation process...");
        
        // Validate input file
        if (!File::exists($inputFile)) {
            $this->error("Input file does not exist: {$inputFile}");
            return 1;
        }

        // Ensure output directory exists
        if (!File::exists($outputDir)) {
            $this->info("Creating output directory: {$outputDir}");
            File::makeDirectory($outputDir, 0755, true);
        }

        // Read tracking file to get previously processed URLs
        $trackedUrls = $this->readTrackingFile($trackingFile);
        $this->info("Loaded " . count($trackedUrls) . " previously processed URLs");

        // Process input file and generate sitemaps
        $newUrls = $this->processInputFile($inputFile, $trackedUrls, $outputDir, $urlsPerSitemap);
        
        // Update tracking file with all processed URLs
        $this->updateTrackingFile($trackingFile, array_merge($trackedUrls, $newUrls));
        
        // Update robots.txt
        $this->updateRobotsTxt($outputDir);

        // Display summary
        $this->displaySummary($startTime);

        return 0;
    }

    /**
     * Read the tracking file to get previously processed URLs
     *
     * @param string $trackingFile
     * @return array
     */
    protected function readTrackingFile($trackingFile)
    {
        if (!File::exists($trackingFile)) {
            $this->info("Tracking file does not exist. Will create a new one.");
            return [];
        }

        $this->info("Reading tracking file: {$trackingFile}");
        return array_map('trim', file($trackingFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES));
    }

    /**
     * Process the input file and generate sitemaps
     *
     * @param string $inputFile
     * @param array $trackedUrls
     * @param string $outputDir
     * @param int $urlsPerSitemap
     * @return array New URLs processed
     */
    protected function processInputFile($inputFile, $trackedUrls, $outputDir, $urlsPerSitemap)
    {
        $this->info("Processing input file: {$inputFile}");
        
        // Store new URLs
        $newUrls = [];
        
        // Prepare for sitemap generation
        $currentSitemapUrls = [];
        $sitemapPaths = [];
        $sitemapIndex = 1;
        
        // Use file() instead of file_get_contents() to handle large files
        $inputFileHandle = fopen($inputFile, 'r');
        
        if (!$inputFileHandle) {
            $this->error("Could not open input file for reading");
            return [];
        }
        
        $bar = $this->output->createProgressBar();
        $bar->start();
        
        // Process the file line by line to handle large files efficiently
        while (($url = fgets($inputFileHandle)) !== false) {
            $url = trim($url);
            $this->totalUrlsProcessed++;
            
            // Skip empty lines and tracked URLs
            if (empty($url) || in_array($url, $trackedUrls)) {
                continue;
            }
            
            // Add to new URLs list
            $newUrls[] = $url;
            $this->newUrlsProcessed++;
            
            // Add to current sitemap batch
            $currentSitemapUrls[] = $url;
            
            // If we've reached the max URLs per sitemap, write the sitemap
            if (count($currentSitemapUrls) >= $urlsPerSitemap) {
                $sitemapPath = $this->writeSitemap($currentSitemapUrls, $outputDir, $sitemapIndex);
                $sitemapPaths[] = $sitemapPath;
                $currentSitemapUrls = [];
                $sitemapIndex++;
                $this->sitemapsGenerated++;
            }
            
            $bar->advance();
        }
        
        fclose($inputFileHandle);
        
        // Write any remaining URLs to a sitemap
        if (!empty($currentSitemapUrls)) {
            $sitemapPath = $this->writeSitemap($currentSitemapUrls, $outputDir, $sitemapIndex);
            $sitemapPaths[] = $sitemapPath;
            $this->sitemapsGenerated++;
        }
        
        $bar->finish();
        $this->newLine(2);
        
        // Create sitemap index file
        if (!empty($sitemapPaths)) {
            $this->createSitemapIndex($sitemapPaths, $outputDir);
        }
        
        return $newUrls;
    }

    /**
     * Write a sitemap file
     *
     * @param array $urls
     * @param string $outputDir
     * @param int $index
     * @return string The sitemap filename
     */
    protected function writeSitemap($urls, $outputDir, $index)
    {
        $filename = "sitemap-{$index}.xml";
        $filepath = "{$outputDir}/{$filename}";
        
        $this->info("Writing sitemap file: {$filepath} with " . count($urls) . " URLs");
        
        $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"></urlset>');
        
        foreach ($urls as $url) {
            $urlElement = $xml->addChild('url');
            $urlElement->addChild('loc', htmlspecialchars($url));
            $urlElement->addChild('lastmod', Carbon::now()->toDateString());
            $urlElement->addChild('changefreq', 'weekly');
            $urlElement->addChild('priority', '0.8');
        }
        
        File::put($filepath, $xml->asXML());
        
        return $filename;
    }

    /**
     * Create sitemap index file
     *
     * @param array $sitemapFiles
     * @param string $outputDir
     */
    protected function createSitemapIndex($sitemapFiles, $outputDir)
    {
        $filepath = "{$outputDir}/sitemap.xml";
        $this->info("Creating sitemap index file: {$filepath}");
        
        $baseUrl = config('app.url');
        
        $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"></sitemapindex>');
        
        foreach ($sitemapFiles as $file) {
            $sitemap = $xml->addChild('sitemap');
            $sitemap->addChild('loc', "{$baseUrl}/sitemaps/{$file}");
            $sitemap->addChild('lastmod', Carbon::now()->toDateString());
        }
        
        File::put($filepath, $xml->asXML());
    }

    /**
     * Update the tracking file with all processed URLs
     *
     * @param string $trackingFile
     * @param array $urls
     */
    protected function updateTrackingFile($trackingFile, $urls)
    {
        $this->info("Updating tracking file with " . count($urls) . " URLs");
        
        // Ensure directory exists
        $directory = dirname($trackingFile);
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }
        
        // Write the tracking file
        File::put($trackingFile, implode(PHP_EOL, $urls));
    }

    /**
     * Update robots.txt file
     *
     * @param string $outputDir
     */
    protected function updateRobotsTxt($outputDir)
    {
        $robotsPath = public_path('robots.txt');
        $baseUrl = config('app.url');
        
        $this->info("Updating robots.txt file: {$robotsPath}");
        
        // Read existing robots.txt if it exists
        $content = File::exists($robotsPath) ? File::get($robotsPath) : '';
        
        // Remove any existing sitemap references
        $content = preg_replace('/^Sitemap:.*$/m', '', $content);
        
        // Add disallow rule for individual sitemaps if not already present
        if (!str_contains($content, 'Disallow: /sitemaps/sitemap-')) {
            $content .= PHP_EOL . "Disallow: /sitemaps/sitemap-*.xml";
        }
        
        // Add sitemap index reference
        $content .= PHP_EOL . "Sitemap: {$baseUrl}/sitemaps/sitemap.xml";
        
        // Clean up any double new lines
        $content = preg_replace('/(\r\n|\r|\n){2,}/', PHP_EOL . PHP_EOL, $content);
        
        // Write the updated robots.txt
        File::put($robotsPath, trim($content) . PHP_EOL);
    }

    /**
     * Display summary information
     *
     * @param float $startTime
     */
    protected function displaySummary($startTime)
    {
        $executionTime = round(microtime(true) - $startTime, 2);
        
        $this->newLine();
        $this->info("Sitemap generation completed!");
        $this->table(
            ['Metric', 'Value'],
            [
                ['Total URLs Processed', $this->totalUrlsProcessed],
                ['New URLs Added', $this->newUrlsProcessed],
                ['Sitemaps Generated', $this->sitemapsGenerated],
                ['Execution Time', "{$executionTime} seconds"]
            ]
        );
        
        // Log the summary
        Log::info("Sitemap generation completed", [
            'total_urls' => $this->totalUrlsProcessed,
            'new_urls' => $this->newUrlsProcessed,
            'sitemaps_generated' => $this->sitemapsGenerated,
            'execution_time' => $executionTime
        ]);
    }
}
