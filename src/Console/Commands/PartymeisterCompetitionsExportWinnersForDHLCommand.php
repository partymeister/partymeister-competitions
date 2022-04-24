<?php

namespace Partymeister\Competitions\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use League\Csv\Writer;
use Partymeister\Competitions\Services\VoteService;
use Symfony\Component\Intl\Countries;

/**
 * Class PartymeisterCompetitionsExportVotesToCSVCommand
 *
 * @package Partymeister\Competitions\Console\Commands
 */
class PartymeisterCompetitionsExportWinnersForDHLCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'partymeister:competitions:export-winners-dhl';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync all entries';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $results = VoteService::getAllVotesByRank();

        $header = [
            'COMPETITION',
            'RANK',
            'POINTS',
            'TITLE',
            'AUTHOR',
            'EMAIL',
            'SEND_NAME1',
            'SEND_NAME2',
            'SEND_STREET',
            'SEND_HOUSENUMBER',
            'SEND_PLZ',
            'SEND_CITY',
            'SEND_COUNTRY',
            'RECV_NAME1',
            'RECV_NAME2',
            'RECV_STREET',
            'RECV_HOUSENUMBER',
            'RECV_PLZ',
            'RECV_CITY',
            'RECV_COUNTRY',
            'PRODUCT',
            'COUPON',
            'SEND_EMAIL',
        ];

        $records = [
            'DE'    => [],
            'EU'    => [],
            'WORLD' => [],
            'ALL'   => [],
        ];

        foreach ($results as $competition) {
            foreach ($competition['entries'] as $entry) {
                if ((int) $entry['rank'] <= 3) {

                    // Get country iso alpha 3
                    $countryAlpha3 = $entry['author_country_iso_3166_1'];
                    try {
                        $countryAlpha3 = Countries::getAlpha3Code($entry['author_country_iso_3166_1']);
                    } catch (\Exception $e) {
                        $this->info('No match found to convert country alpha2 to alpha3 for '.$entry['author_country_iso_3166_1']);
                    }

                    // Get country group for product selection
                    switch ($entry['author_country_iso_3166_1']) {
                        case 'DE':
                            $productCountry = 'DE';
                            break;
                        case 'AT':
                        case 'BE':
                        case 'BG':
                        case 'HR':
                        case 'CY':
                        case 'DK':
                        case 'EE':
                        case 'FI':
                        case 'FR':
                        case 'GR':
                        case 'HU':
                        case 'IE':
                        case 'IT':
                        case 'LV':
                        case 'LT':
                        case 'LU':
                        case 'MT':
                        case 'NL':
                        case 'PT':
                        case 'RO':
                        case 'SK':
                        case 'SI':
                        case 'ES':
                        case 'SE':
                        case 'PL':
                        case 'CZ':
                            $productCountry = 'EU';
                            break;
                        default:
                            $productCountry = 'WORLD';
                    }

                    $record = [
                        $competition['name'],
                        $entry['rank'],
                        $entry['points'],
                        $entry['title'],
                        $entry['author'],
                        $entry['author_email'],
                        config('partymeister-competitions-dhl-export.sender_name'),
                        config('partymeister-competitions-dhl-export.sender_company'),
                        config('partymeister-competitions-dhl-export.sender_street'),
                        config('partymeister-competitions-dhl-export.sender_street_number'),
                        config('partymeister-competitions-dhl-export.sender_zip'),
                        config('partymeister-competitions-dhl-export.sender_city'),
                        config('partymeister-competitions-dhl-export.sender_country'),
                        $entry['author_name'],
                        '', // Additional name
                        $entry['author_address'],
                        '', // Street number
                        $entry['author_zip'],
                        $entry['author_city'],
                        Str::upper($countryAlpha3),
                        config('partymeister-competitions-dhl-export.product.'.$productCountry),
                        '',
                        config('partymeister-competitions-dhl-export.sender_email'),
                    ];

                    foreach ($record as $key => $value) {
                        $record[$key] = mb_convert_encoding($value, "Windows-1252", "UTF-8");
                    }
                    $records[$productCountry][] = $record;
                    $records['ALL'][] = $record;
                }
            }
        }

        // Export by region
        foreach ($records as $destination => $data) {
            //load the CSV document from a string
            $csv = Writer::createFromString();
            $csv->setEnclosure("\"");
            $csv->setDelimiter(';');

            //insert the header
            $csv->insertOne($header);

            //insert all the records
            $csv->insertAll($data);

            file_put_contents('dhl_export_'.$destination.'.csv', $csv->toString());
        }
    }
}
