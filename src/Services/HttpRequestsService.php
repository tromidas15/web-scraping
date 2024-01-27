<?php

namespace App\Services;

use Symfony\Component\Console\Command\Command;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class HttpRequestsService
{
    public const MEP_URL = 'https://www.europarl.europa.eu/meps/en/full-list/xml/';

    public function __construct(private readonly HttpClientInterface $client,)
    {
    }

    public function getData(): \SimpleXMLElement
    {
        try {
            $response = $this->client->request('GET', self::MEP_URL);
            $xmlContents = $response->getContent();
        } catch (ClientExceptionInterface | ServerExceptionInterface | TransportExceptionInterface $e) {
            throw new FailedToFetchDataException('Failed to fetch data from the European Parliament website', 0, $e);
        }

        try {
            $xml = new \SimpleXMLElement($xmlContents);
        } catch (\Exception $e) {
            throw new FailedToFetchDataException('Error parsing XML: ', 0, $e);
        }

        return $xml;
    }
}