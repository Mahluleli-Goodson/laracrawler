<?php

namespace Tests\Feature;

use App\Helpers\PropertiesHelper;
use Illuminate\Contracts\Container\BindingResolutionException;
use Tests\TestCase;

/**
 * Class PropertiesHelperTest
 * @package Tests\Feature
 */
class PropertiesHelperTest extends TestCase {

    /** @var PropertiesHelper */
    private $propertiesHelper;

    /**
     * @throws BindingResolutionException
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->propertiesHelper = $this->app->make(PropertiesHelper::class);
    }

    /**
     * test sanitizing price string
     */
    public function test_should_sanitize_string_to_integer()
    {
        $sample = "&pound;6,419,550";
        $result = $this->propertiesHelper::sanitizePrice($sample);
        $this->assertIsInt($result);
        $this->assertEquals(6419550, $result);
    }

    /**
     * test sort by price and slicing array to required length
     */
    public function test_should_sort_array_by_price_desc_and_slice_array_size_to_length_passed()
    {
        $sample = [
            ["price" => 95],
            ["price" => 15],
            ["price" => 2000],
        ];

        $expected = [
            ["price" => 2000],
            ["price" => 95],
        ];

        $result = $this->propertiesHelper::sortPropertiesAndTrim($sample, 2);
        $this->assertIsArray($result);
        $this->assertEquals(2, sizeof($result));
        $this->assertEquals($expected, $result);
    }
}
