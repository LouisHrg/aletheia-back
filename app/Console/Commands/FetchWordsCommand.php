<?php

namespace App\Console\Commands;

use App\Word;
use App\Article;
use App\Source;

use Exception;
use Illuminate\Console\Command;
use GuzzleHttp\Client;

/**
 * Class deletePostsCommand
 *
 * @category Console_Command
 * @package  App\Console\Commands
 */
class FetchWordsCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = "words:fetch";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Fetch words & articles related";


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            self::fetchAllWords();
            $this->info("Words & Articles has been fetched");
        } catch (Exception $e) {
            $this->error("An error occurred");
        }
    }

    public static function fetchAllWords()
    {
        $http = new Client();

        $res = $http->request('GET', 'https://api.ozae.com/gnw/ngrams?date=20190301__20190320&limit=20&key='.env('OZAE_API_KEY'));
        $data = json_decode($res->getBody());

        for ($i = 0; $i < 20; $i ++) {

            if(in_array($data->ngrams[$i]->ngram, Word::RESTRICTED)){
              continue;
            }

            $word = Word::updateOrCreate(
                [ 'value' => $data->ngrams[$i]->ngram ],
                [ ]
            );
            for ($j = 0; $j < 10; $j ++) {
                $_res = $http->request('GET', 'https://api.ozae.com/gnw/article/'.$data->ngrams[$i]->articles_ids[$j].'?key='.env('OZAE_API_KEY'));
                $_data = json_decode($_res->getBody());
                $source = Source::where('name', '=', $_data->source->domain)->first();
                if (!is_null($source)) {
                    Article::updateOrCreate(
                        [ 'idOzae' => $_data->id ],
                        [ 'url' => $_data->url, 'title' => $_data->name, 'idOzae' => $_data->id, 'source_id' => $source->id, 'word_id' => $word->id ]
                    );
                }
            }
        }

        return true;
    }
}
