<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;

class OwaspZapService
{
    protected $client;
    private $apiKey = 'crimson'; // Your API key

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://localzap.cyberenew.au',
        ]);
    }

    public function startScan($target)
    {
        // Endpoint and parameters
        $endpoint = '/JSON/spider/action/scan/';

        // Make the API call
        try {
            $response = $this->client->get($endpoint, [
                'query' => [
                    'apikey' => $this->apiKey,
                    'url' => $target,
                    'contextName' => '',
                    'recurse' => ''
                ]
            ]);
        } catch (ClientException $e) {
            if ($e->getResponse()->getStatusCode() == 400) {
                return ['error' => 'Bad Request'];
            }
            return ['error' => 'Client error'];
        } catch (ServerException $e) {
            if ($e->getResponse()->getStatusCode() == 502) {
                return ['error' => 'Bad Gateway'];
            }
            return ['error' => 'Server error'];
        }
        return json_decode($response->getBody(), true);
    }

    public function getScanStatus($scanId)
    {
        try {
            $endpoint = "/JSON/spider/view/status/?apikey={$this->apiKey}&scanId={$scanId}";
            $response = $this->client->get($endpoint);
        } catch (ClientException $e) {
            if ($e->getResponse()->getStatusCode() == 400) {
                return ['error' => 'Bad Request'];
            }
            return ['error' => 'Client error'];
        } catch (ServerException $e) {
            if ($e->getResponse()->getStatusCode() == 502) {
                return ['error' => 'Bad Gateway'];
            }
            return ['error' => 'Server error'];
        }
        return json_decode($response->getBody(), true);
    }

    public function getScanResults($scanId)
    {
        $endpoint = "/JSON/spider/view/results/?apikey={$this->apiKey}&scanId={$scanId}";
        $response = $this->client->get($endpoint);

        return json_decode($response->getBody(), true);
    }

    public function getAlerts($targetUrl, $start = 0, $count = 100)
    {
        $endpoint = "/JSON/core/view/alerts/?apikey={$this->apiKey}&baseurl={$targetUrl}&start={$start}&count={$count}";
        $response = $this->client->get($endpoint);

        return json_decode($response->getBody(), true);
    }

    public function getAlertsCount($baseUrl)
    {
        $endpoint = "/JSON/alert/view/numberOfAlerts/?apikey={$this->apiKey}&baseurl={$baseUrl}";
        $response = $this->client->get($endpoint);
        return json_decode($response->getBody(), true);
    }

    public function viewAlertsSummary($baseUrl)
    {
        $endpoint = "/JSON/alert/view/alertsSummary/?apikey={$this->apiKey}&baseurl={$baseUrl}";
        $response = $this->client->get($endpoint);

        return json_decode($response->getBody(), true);
    }

    public function viewAlertsByRisk($baseUrl, $recurse = "")
    {
        $endpoint = "/JSON/alert/view/alertsByRisk/?apikey={$this->apiKey}&url={$baseUrl}&recurse={$recurse}";
        $response = $this->client->get($endpoint);

        return json_decode($response->getBody(), true);
    }

    public function checkThirdPartyLibrariesForCve($scanId)
    {
        $scanResults = $this->getScanResults($scanId);
        $thirdPartyLibraries = $scanResults['results']; // Extract 3rd party libraries from $scanResults

        $nistCveService = new NistCveService();

        $libraryCveScores = [];
        foreach ($thirdPartyLibraries as $library) {
            $baseScores = $nistCveService->getCveBaseScore($library);
            $libraryCveScores[$library] = $baseScores;
        }

        return $libraryCveScores;
    }
}
