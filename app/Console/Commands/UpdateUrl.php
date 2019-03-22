<?php

namespace App\Console\Commands;

use App\Word;
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
class UpdateUrl extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = "update:urls";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Launch all fetch commands";


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            $articles = Article::all();

            foreach ($articles as $article) {
                $article->url = urlencode($article->url);
                $article->edition = $article->source->edition;
                $article->save();
            }

        } catch (Exception $e) {
            $this->error("An error occurred");
        }
    }

}
