<?php

namespace App\Services;

use App\Helpers\LogHelper;
use App\Helpers\PropertiesHelper;
use Exception;
use Goutte\Client;
use Symfony\Component\HttpClient\HttpClient;

/**
 * Class CrawlerService
 * @package App\Services
 */
class CrawlerService extends BaseService {

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Find properties by postCode passed
     *
     * @param $postCode
     * @return array
     */
    public function findProperties(string $postCode)
    {
        $postCode = trim($postCode);

        if (!isset($postCode) || empty($postCode)) {
            LogHelper::logger()->error("PostCode is empty or not set :: " . __METHOD__);
            return [];
        }

        $scrapedData = $this->crawlerComponent($postCode);

        return $this->sanitizeDataDump($scrapedData);
    }

    /**
     * get properties array from results
     * this uses [Goutte] package to crawl & scrap the data we require
     *
     * @param $postCode
     * @return array
     */
    public function crawlerComponent($postCode)
    {
        try {
            $propertiesConfig = config("goutte.properties");
            $result = [];

            $goutte = new Client(HttpClient::create(["timeout" => $propertiesConfig["timeout"]]));
            $htmlResponse = $goutte->request("GET", $propertiesConfig["uri"]);
            $form = $htmlResponse->selectButton($propertiesConfig["submitBtnSelector"])->form();
            $response = $goutte->submit($form, [$propertiesConfig["inputSelector"] => $postCode]);

            # now get the redirected-to uri and add filter parameter of 10 year period [soldIn=10]
            $sortResponse = $goutte->request("GET", "{$response->getUri()}&soldIn=10");

            # results are within a <script> tag hence filter and find string with "window.__PRELOADED_STATE__ = "
            $sortResponse->filter($propertiesConfig["filterSelector"])
                ->each(function ($element) use (&$result, $propertiesConfig) {

                if (str_contains($element->text(), $propertiesConfig["stringOfInterest"])) {
                    $cleanString = str_replace($propertiesConfig["stringOfInterest"], "", ($element->text()));
                    $decodedData = (json_decode(trim($cleanString), true));
                    $result =  $decodedData["results"] ?? [];
                }
            });

        } catch (Exception $exc) {
            LogHelper::logger()->error($exc->getMessage() . " :: " . __METHOD__);
            return [];
        }

        return $result;
    }

    /**
     * Structure data from crawler to standardized and operable object
     * @param  array  $rawData
     * @return array
     */
    public function sanitizeDataDump(array $rawData = [])
    {
        $structured = [];
        $properties = [];
        $propertiesConfig = config("goutte.properties");

        if (!isset($rawData["properties"]) || !isset($rawData["resultCount"])) {
            LogHelper::logger()->error(
                "Can not find [properties] and [resultCount] in rawData passed :: " . __METHOD__
            );
            return [];
        }

        try {
            # push number of sold properties into structured data array
            $structured["totalSoldProperties"] = $rawData["resultCount"];

            foreach ($rawData["properties"] as $propertyObject) {
                $properties[] = [
                    "address" => $propertyObject["address"],
                    "type" => $propertyObject["propertyType"],
                    "price" => PropertiesHelper::sanitizePrice($propertyObject["transactions"][0]["displayPrice"]),
                ];
            }

            # sort properties by descending price and only select max [$propertiesConfig["trimSize"]]
            $structured["properties"] =
                PropertiesHelper::sortPropertiesAndTrim($properties, $propertiesConfig["trimSize"]);

        } catch (Exception $exc) {
            LogHelper::logger()->error($exc->getMessage() . " :: " . __METHOD__);
            return [];
        }

        return $structured;
    }
}
