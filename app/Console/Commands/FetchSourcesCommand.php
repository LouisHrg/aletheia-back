<?php

namespace App\Console\Commands;

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
class FetchSourcesCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = "sources:fetch";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Fetch sources";


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            self::fetchAllSources();
            $this->info("Sources has been fetched");
        } catch (Exception $e) {
            $this->error("An error occurred");
        }
    }

    public static function fetchAllSources()
    {
        $http = new Client();

        $editions = Source::EDITIONS;

        foreach ($editions as $edition) {
            $dateRange = DateRange::getDateRange('week');

            $res = $http->request('GET', 'https://api.ozae.com/gnw/sources?date='.$dateRange.'&edition='.$edition.'&segment=domain&topic=_&key='.env('OZAE_API_KEY'));
            $data = json_decode($res->getBody());

            foreach ($data->sources as $sourceData) {
                Source::updateOrCreate(
                [ 'idOzae' => $sourceData->id ],
                [ 'name' => $sourceData->name, 'edition' => $edition, 'score' => 50 ]
            );
            }
        }

        return true;
    }
}
