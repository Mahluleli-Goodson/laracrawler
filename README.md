
**<h1 align="center">LaraCrawler 2.0</h1>**



## About LaraCrawler

<p>LaraCrawler is a CLI based project, for finding properties in UK by postcode.</p>
 <p>To test, just run the following in your terminal: <pre>php artisan crawler:properties --postcode=sw1a</pre> </p>

## Environment
This project has been proved to work in the following environment:

* Laravel 7.30.4
* PHP 7.2
* Valet 2.4.2 (note: this is optional in case you want to view frontend)
* Node 15.11.0 (npm 7.6.1) (note: this is optional in case you want to view frontend)
* Composer 1.10.20

## Set up

* git clone project
* rename `.env.example` to `.env`
* generate your laravel APP_KEY
* run `composer install`
* run `npm i` (optional)

At this point, the project should be completely set up

## How to use
This project runs as an artisan command. Run command:
<pre>php artisan crawler:properties --postcode={your_postcode_here}</pre>
Result should be an array with 2 properties `totalSoldProperties` and `properties`, when there's a result, otherwise its an empty array.

## Running Tests
To run tests, please use:
<pre>php artisan test</pre>

## Architectural Improvements that can be done
* Cache data that's returned from crawler so that similar requests can read from cache instead of repeating same request.
This will reduce resource usage and provide better user experience.
* Split `CrawlerService` into `PropertiesService` and `GoutteComponent`, then make `GoutteComponent` a reusable component that accepts dynamic values/parametres
* Add a guard for PostCodes to reject any non-UK postcodes
* Mock all `Goutte` requests in tests

## License

LaraCrawler is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
