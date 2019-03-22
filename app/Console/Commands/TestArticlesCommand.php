<?php

namespace App\Console\Commands;

use App\Article;
use App\Source;
use Exception;
use Illuminate\Console\Command;
use GuzzleHttp\Client;
use App\Helpers\DateRange;
use App\Helpers\ArticleHelper;

/**
 * Class deletePostsCommand
 *
 * @category Console_Command
 * @package  App\Console\Commands
 */
class TestArticlesCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = "articles:test";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Test articles confidence";


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            self::testArticles();
            $this->info("Articles have been tested");
        } catch (Exception $e) {
            $this->error("An error occurred");
        }
    }

    public static function testArticles()
    {
        $http = new Client();

        $articles = Article::all();

        foreach ($articles as $article) {
            if($article->trust === 0){
                $check = ArticleHelper::check($article->url);
                $article->biased = $check['biased'];
                $article->trust = $check['fakenews'];
                $article->clickbait = $check['clickbait'];
                $article->save();
                echo $article->id.' saved';
            }
        }

        return true;
    }
}
