<?php

namespace App\Services;
use GuzzleHttp\Client;

class NistCveService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://services.nvd.nist.gov/rest/json/cves/2.0/',
            'verify' => false,  // Disable SSL verification
        ]);
    }

    public function getCveBaseScore($libraryName)
    {
        // Query the NIST API for CVE information related to the library
        $endpoint = "cves/1.0";
        $response = $this->client->get($endpoint, [
            'query' => [
                'keyword' => $libraryName,
            ]
        ]);

        $data = json_decode($response->getBody(), true);

        // Extract the base score from the CVE information
        $baseScores = [];
        if (isset($data['result']['CVE_Items'])) {
            foreach ($data['result']['CVE_Items'] as $cveItem) {
                if (isset($cveItem['impact']['baseMetricV3']['cvssV3']['baseScore'])) {
                    $baseScores[] = $cveItem['impact']['baseMetricV3']['cvssV3']['baseScore'];
                }
            }
        }

        return $baseScores;
    }
}
