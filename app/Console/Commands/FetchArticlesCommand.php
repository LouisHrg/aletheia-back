<?php

namespace App\Console\Commands;

use App\Article;
use App\Source;
use Exception;
use Illuminate\Console\Command;
use GuzzleHttp\Client;
use App\Helpers\DateRange;

/**
 * Class deletePostsCommand
 *
 * @category Console_Command
 * @package  App\Console\Commands
 */
class FetchArticlesCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = "articles:fetch {range}";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Fetch articles";


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            self::fetchAllArticles();
            $this->info("Articles has been fetched");
        } catch (Exception $e) {
            $this->error("An error occurred");
        }
    }

    public static function fetchAllArticles()
    {
        $http = new Client();

        $editions = Source::EDITIONS;
        $dateRange = DateRange::getDateRange($this->argument('range'));

        foreach ($editions as $edition) {

            $res = $http->request('GET', 'https://api.ozae.com/gnw/articles?hard_limit=100&edition='.$edition.'&date='.$dateRange.'&key='.env('OZAE_API_KEY'));

            $data = json_decode($res->getBody());
            foreach ($data->articles as $article) {
                $source = Source::where('name', '=', $article->source->domain)->first();
                if (!is_null($source)) {
                    Article::updateOrCreate(
                        [ 'idOzae' => $article->id ],
                        [ 'url' => $article->url, 'image' => $article->img_uri, 'title' => $article->name, 'idOzae' => $article->id, 'source_id' => $source->id, 'score' => $article->article_score ]
                    );
                }
            }
        }

        return true;
    }
}
