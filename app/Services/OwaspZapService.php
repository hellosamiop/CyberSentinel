<?php

namespace App\Services;
use GuzzleHttp\Client;

class OwaspZapService
{
    protected $client;
    private $apiKey = 'crimson'; // Your API key

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://zap.cyberenew.au',
        ]);
    }

    public function startScan($target)
    {
        // Endpoint and parameters
        $endpoint = '/JSON/spider/action/scan/';

        // Make the API call
        $response = $this->client->get($endpoint, [
            'query' => [
                'apikey' => $this->apiKey,
                'url' => $target,
                'contextName' => '',
                'recurse' => ''
            ]
        ]);

        return json_decode($response->getBody(), true);
    }

    public function getScanStatus($scanId)
    {
        $endpoint = "/JSON/spider/view/status/?apikey={$this->apiKey}&scanId={$scanId}";
        $response = $this->client->get($endpoint);

        return json_decode($response->getBody(), true);
    }

    public function getScanResults($scanId)
    {
        $endpoint = "/JSON/spider/view/results/?apikey={$this->apiKey}&scanId={$scanId}";
        $response = $this->client->get($endpoint);

        return json_decode($response->getBody(), true);
    }

    public function getAlerts($targetUrl, $start = 0, $count = 10)
    {
        $endpoint = "/JSON/core/view/alerts/?apikey={$this->apiKey}&baseurl={$targetUrl}&start={$start}&count={$count}";
        $response = $this->client->get($endpoint);

        return json_decode($response->getBody(), true);
    }

}
