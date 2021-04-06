<?php

namespace App\Console\Commands;

use App\Services\CrawlerService;
use Illuminate\Console\Command;

class PropertiesCrawler extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawler:properties {--postcode=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List properties found in passed postcode area [--postcode]';

    /**
     * @var CrawlerService
     */
    private $crawlerService;

    /**
     * Create a new command instance.
     *
     * @param  CrawlerService  $crawlerService
     */
    public function __construct(CrawlerService $crawlerService)
    {
        parent::__construct();
        $this->crawlerService = $crawlerService;
    }

    /**
     * Console command executer
     *
     * Add your conditional cases here for all different properties-crawler related issues
     * @example:
     * if ($this->option('size') > 200) {
        $this->propertySizeHandler();
     * }
     */
    public function handle()
    {
        return $this->postCodeHandler();
    }

    /**
     * Handle property [postcodes] related commands
     */
    public function postCodeHandler()
    {
        $postCode = $this->option('postcode');

        if (!isset($postCode) || empty($postCode)) {
            $this->error("option [--postcode] is required");
            return 1;
        }

        $this->info(__METHOD__ . " start");
        $response = $this->crawlerService->findProperties($postCode);

        # let's dump response here for visibilty in CLI/Terminal
        dump($response);

        $this->info(__METHOD__ . " complete");
        return 0;
    }
}
