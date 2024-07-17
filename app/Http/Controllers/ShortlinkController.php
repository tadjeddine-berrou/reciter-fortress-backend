<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class ShortlinkController extends Controller
{

    public function fetchLongUrl(string $shortLink) {
        $url = 'https://maps.app.goo.gl/' . $shortLink;
        try {
            // Initialize Guzzle client
            $client = new Client();

            // Make a GET request to fetch the shortlink
            $response = $client->request('GET', $url, [
                'allow_redirects' => [
                    'max' => 0,
                    'strict' => true,
                    'referer' => true,
                ],
            ]);

            // Get the final URL after all redirects
            $longUrl = $response->getHeaderLine('Location');

            $placeName = $this->extractPlaceNameFromGoogleMapsUrl($longUrl);
            $coordinates = $this->extractCoordinatesFromGoogleMapsUrl($longUrl);

            $queryParams = [];
            if ($placeName) {
                $queryParams[] = $placeName;
            } elseif ($coordinates) {
                $queryParams = array_merge($queryParams, $coordinates);
            }

            $queryString = implode(',', $queryParams);

            $baseUrl = 'https://www.google.com/maps/embed/v1/place';

            $queryParams = [
                'key' => 'AIzaSyCSuXK9_GsG7pJwvGa5sdT8v7hudrsqm1M',
                'q' => $queryString
            ];

            $url = $baseUrl . '?' . http_build_query($queryParams);

            dd($url);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    private function extractCoordinatesFromGoogleMapsUrl($url) {
        $pattern = '/@(-?\d+\.\d+),(-?\d+\.\d+)/';
        preg_match($pattern, $url, $matches);

        if (count($matches) >= 3) {
            $latitude = floatval($matches[1]);
            $longitude = floatval($matches[2]);

            return [$latitude, $longitude];
        } else {
            return null; // Return null if coordinates are not found
        }
    }

    public function extractPlaceNameFromGoogleMapsUrl($url) {
        $pattern = '/place\/([^\/]+)/';
        preg_match($pattern, $url, $matches);

        if (count($matches) >= 2) {
            return urldecode($matches[1]);
        } else {
            return null;
        }
    }
}
