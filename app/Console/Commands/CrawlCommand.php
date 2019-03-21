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
class CrawlCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = "db:crawl";

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
                $this->call('migrate:fresh');
                $this->call('sources:fetch');
                $this->call('words:fetch');
                $this->call('articles:fetch', ['week']);
                $this->call('articles:fetch', ['month']);
                $this->call('articles:fetch', ['semester']);
                $this->call('articles:fetch', ['year']);

        } catch (Exception $e) {
            $this->error("An error occurred");
        }
    }

}
