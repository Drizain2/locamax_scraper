<?php

namespace App\Console\Commands;

use App\Models\RentalSource;
use Illuminate\Console\Command;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\RequestException;
use Symfony\Component\DomCrawler\Crawler;

class ScrapeRentals extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:scrape-rentals';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $client = new HttpClient();

        $url = "https://www.expat-dakar.com/immobilier";
        $this->info("Scraping : {$url}");

        // 1)Requette pour récupérer le HTML
        try {
            $response = $client->request("GET", $url);
        } catch (RequestException $e) {
            $this->error("erreur :" . $e->getMessage());
        }

        $this->info("Recupération du contenu de html");
        $html = $response->getBody()->getContents();
        $crawler = new Crawler($html);

        //Boucle sur la page pour récupérer les liens
        $this->info("Récupérons les hrefs");
        $links = $crawler->filter(".listing-card a")->each(function (Crawler $node) use ($url) {
            $url = $node->attr("href");

            if (!str_starts_with($url, "http")) {
                return null;
            }
            return $url;
        });

        $this->info("Filtrons pour ne garder que des urls valides");
        $links = array_filter(array_unique($links));

        $this->info("Annonces trouvées : " . count($links));

        $bar = $this->output->createProgressBar(count($links));
        // $bar->setFormat("d");
        $bar->start();

        // Scrapper les données de chaque annonce
        foreach ($links as $url) {
            $this->scrapDetailPage($client, $url);
            $bar->advance();
        }

        $bar->finish();
        $this->info("\nScraping completed successfully");

    }
    private function scrapDetailPage(
        HttpClient
        $client,
        $url
    ) {
        if (RentalSource::where('source_url', $url)->exists()) {
            return;
        }

        try {
            $response = $client->get($url);

        } catch (RequestException $e) {
            $this->error('Impossible de charger la page');
            return;
        }

        $htm = $response->getBody()->getContents();
        $crawler = new Crawler($htm);

        // 1. Filtrer URL
        if (!str_contains($url, '/annonce/')) {
            return;
        }

        // 2. Charger la page
        $response = $client->get($url);
        $crawler = new Crawler((string) $response->getBody());

        // 3. Sécuriser extraction
        $title = $crawler->filter('h1')->count()
            ? trim($crawler->filter('h1')->text())
            : null;

        if (!$title) {
            return;
        }

        //Description
        $description = $crawler->filter(".listing-item__description")->text();
        $district = $crawler->filter('.listing-item__address-location')->count()
            ? trim($crawler->filter('.listing-item__address-location')->text())
            : null;

        $city = $crawler->filter('.listing-item__address-region')->count()
            ? trim($crawler->filter('.listing-item__address-region')->text())
            : null;
        //Telephone si present
        $phone = null;
        if ($crawler->filter('a[href^="tel:"]')->count()) {
            $phone = str_replace('tel:', '', $crawler->filter('a[href^="tel:"]')->attr('href'));
        }

        // Déduire le type
        $type = preg_match('/agence|immobilier|sarl/i', $description)
            ? 'AGENCY'
            : 'PRIVATE';
        RentalSource::create([
            "name_or_title" => $title,
            "source_url" => $url,
            "source_type" => $type,
            "phone_number" => $phone,
            "email" => null,
            "property_type" => "Appartement",
            "city" => $city,
            "district" => $district,
            "is_qualified" => $phone ? true : false
        ]);

    }
}
