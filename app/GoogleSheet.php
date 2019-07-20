<?php

namespace App;

use Google_Client;
use Google_Service_Sheets;
use Illuminate\Database\Eloquent\Model;

class GoogleSheet extends Model
{
    public static function instance()
    {

        $credentialsPath = './credentials.json';
        $client = new Google_Client();
        $client->setScopes([Google_Service_Sheets::SPREADSHEETS]);
        $client->setAuthConfig($credentialsPath);
        return new Google_Service_Sheets($client);
    }

    public static function columnLabels()
    {
        $alphabets = range('A', 'Z');
        $columnLabels = $alphabets;
        foreach ($alphabets as $baseColumnLabel) {
            foreach ($alphabets as $subColumnLabel) {
                $columnLabels[] = $baseColumnLabel . $subColumnLabel;
            }
        }
        return $columnLabels;
    }
}
