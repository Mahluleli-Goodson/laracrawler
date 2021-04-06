<?php

namespace App\Helpers;

/**
 * Class PropertiesHelper
 * @package App\Helpers
 */
class PropertiesHelper {

    /**
     * convert string price to a logical number
     * @note: $displayPrice passed has the form "&pound;6,419,550"
     *
     * @param $displayPrice
     * @return int
     */
    public static function sanitizePrice(string $displayPrice)
    {
        if (!isset($displayPrice) || empty($displayPrice)) {
            return 0;
        }

        # [NumberFormatter] class won't work here because of comma separators
        $displayPrice = str_replace("&pound;", "", $displayPrice);
        $displayPrice = str_replace(",", "", $displayPrice);
        return (int) $displayPrice;
    }

    /**
     * Sort properties by price DESC and trim out anything above [$trimSize]
     * @param  array  $properties
     * @param  int  $trimSize
     * @return array
     */
    public static function sortPropertiesAndTrim(array $properties, int $trimSize)
    {
        if (empty($properties)) {
            return [];
        }

        $displayPrice = array_column($properties, "price");
        array_multisort($displayPrice, SORT_DESC, $properties);
        return isset($trimSize) ? array_slice($properties, 0, $trimSize) : $properties;
    }
}
