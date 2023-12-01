<?php

namespace App\Console\Commands;

use App\Models\ScheduleTimestamp;
use Carbon\Carbon;
use Goutte\Client;
use Illuminate\Console\Command;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ScrapperCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrapper:cron';

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
        // Log::info('Scrapper Cron is working fine!');

        $currency = [
            'USD',
            'SGD',
            'AUD',
            'EUR',
            'CNY',
            'HKD',
            'GBP',
            'JPY',
            'CAD',
            'NZD',
            'MYR',
            'THB',
            'SAR',
            'PHP',
            'KRW',
            'VND',
            'PGK',
            'LAK',
            'KWD',
            'BND',
        ];

        $client = new Client();

        $website = $client->request('GET', 'https://kursdollar.org/');
        
        $data = $website->filter('td')->extract(array('_text'));
        // dd($data);
        $ratesArr = [
            'meta' => [
                "date" => date('d-m-Y'),
                "day" => date('D'),
                "indonesia" => $data[3],
                "word" => $data[4]
            ],
            'rates' => []
        ];
        if (! empty($data)) {
            $length = count($data);
            for ($i = 0; $i < $length; $i++) {
                $currencyCode = substr($data[$i], 0, 3);
                if (array_search($currencyCode, $currency) !== false) {
                    array_push($ratesArr['rates'], [
                        'currency' => $currencyCode,
                        'buy' => explode(' ', $data[$i + 1])[0],
                        'sell' => explode(' ', $data[$i + 2])[0],
                        'average' => explode(' ', $data[$i + 3])[0],
                        'word_rate' => $data[$i + 4],
                    ]);
                }
            }
        }
        $nameFormat = 'rate-' . date('d-m-Y--h--i-s') . '.json';
        Storage::disk('public')->put($nameFormat, json_encode($ratesArr));
    }
}
