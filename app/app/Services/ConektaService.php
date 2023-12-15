<?php

namespace App\Services;

use GuzzleHttp\Client;

class ConektaService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => env('CONEKTA_URL_BASE'),
            'headers' => [
                'Content-Type' => 'application/json',
                'accept' => 'application/vnd.conekta-v2.1.0+json',
                'Authorization' => 'Basic ' . base64_encode(env('CONEKTA_API_KEY_PRIV').':'),
            ],
        ]);
    }

    public function getPaymentLinkById($debtData, $debtId)
    {
        $response = $this->client->get("/checkouts/{$debtId}", [
            'json' => $debtData,
        ]);
        //dd($debtData);

        return json_decode($response->getBody()->getContents());
    }

    public function postPaymentLink($debtData)
    {
        $response = $this->client->post("/checkouts", [
            'json' => $debtData,
        ]);
        //dd($debtData);

        return json_decode($response->getBody()->getContents());
    }
}