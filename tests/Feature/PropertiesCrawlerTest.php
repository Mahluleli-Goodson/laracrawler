<?php

namespace Tests\Feature;

use App\Services\CrawlerService;
use Illuminate\Contracts\Container\BindingResolutionException;
use Tests\TestCase;

/**
 * Class PropertiesCrawlerTest
 * @package Tests\Feature
 */
class PropertiesCrawlerTest extends TestCase
{
    /** @var CrawlerService */
    private $propertiesCrawlerService;

    /**
     * @throws BindingResolutionException
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->withExceptionHandling();
        $this->propertiesCrawlerService = $this->app->make(CrawlerService::class);
    }

    /**
     * test [php artisan crawler:properties] WITHOUT options
     */
    public function test_should_return_an_error_without_parameters()
    {
        $this->artisan('crawler:properties')
            ->expectsOutput('option [--postcode] is required')
            ->assertExitCode(1);
    }

    /**
     * test [php artisan crawler:properties] WITH options
     */
    public function test_should_return_an_array_with_parameters()
    {
        $this->artisan('crawler:properties --postcode=sw1a')
            ->assertExitCode(0);
    }

    /**
     * passing empty array to [sanitizeDataDump] must yield []
     */
    public function test_should_return_empty_array_if_data_passed_is_empty()
    {
        $result = $this->propertiesCrawlerService->sanitizeDataDump([]);
        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    /**
     * passing proper array to [sanitizeDataDump] must yield non-empty array
     */
    public function test_should_return_array_with_data()
    {
        $sample = [
            "resultCount" => 1,
            "properties" => [
                [
                    "address" => "123 address",
                    "propertyType" => "test type",
                    "transactions" => [["displayPrice" => "&pound;250,000"]]
                ]
            ]
        ];
        $result = $this->propertiesCrawlerService->sanitizeDataDump($sample);
        $this->assertIsArray($result);
        $this->assertNotEmpty($result);
        $this->assertArrayHasKey("properties", $result);
        $this->assertArrayHasKey("totalSoldProperties", $result);
    }

    /**
     * test passing proper postCde to [crawlerComponent]
     */
    public function test_should_return_an_non_empty_array_with_proper_postcode()
    {
        $result = $this->propertiesCrawlerService->crawlerComponent('sw1a');
        $this->assertIsArray($result);
        $this->assertNotEmpty($result);
    }

    /**
     * test passing null postcode
     */
    public function test_should_return_an_empty_array_with_null_postcode()
    {
        $result = $this->propertiesCrawlerService->crawlerComponent(null);
        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    /**
     * test passing invalid postcode
     */
    public function test_should_return_an_empty_array_with_wrong_postcode()
    {
        $result = $this->propertiesCrawlerService->crawlerComponent('zzz');
        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }
}
