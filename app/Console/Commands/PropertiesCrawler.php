<?php

namespace App\Console\Commands;

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
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
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
        $this->postCodeHandler();
    }

    /**
     * Handle property [postcodes] related commands
     */
    public function postCodeHandler()
    {
        $postCode = $this->option('postcode');

        if (!isset($postCode) || empty($postCode)) {
            $this->error("option [--postcode] is required");
            return;
        }

        $this->info(__METHOD__ . " start");
        //
    }
}
