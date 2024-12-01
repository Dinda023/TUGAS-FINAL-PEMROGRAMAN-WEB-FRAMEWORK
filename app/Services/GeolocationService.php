<?php

namespace App\Services;

use GuzzleHttp\Client;

class GeolocationService
{
    public function getLocation()
    {
        // Inisialisasi Guzzle client
        $client = new Client();

        // Mendapatkan IP publik pengguna
        $response = $client->get('https://api.ipify.org?format=json');
        $ip = json_decode($response->getBody(), true)['ip'];

        // URL endpoint IP Geolocation API
        $url = 'https://api.ipgeolocation.io/ipgeo';
        $apiKey = env('GEO_API_KEY');  // Ambil API key dari .env

        // Menambahkan IP ke dalam query
        $response = $client->get($url, [
            'query' => [
                'apiKey' => $apiKey,
                'ip' => $ip,
            ]
        ]);

        // Mendapatkan data lokasi
        $data = json_decode($response->getBody(), true);

        // Mengembalikan lokasi sebagai string
        return $data['city'] . ', ' . $data['country_name'];
    }
}
