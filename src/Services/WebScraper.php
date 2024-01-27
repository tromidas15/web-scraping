<?php

declare(strict_types=1);

namespace App\Services;

use App\Repository\ContactsRepository;
use App\Services\Dto\ContactInfoDto;
use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

class WebScraper
{
    private const BASE_URL = 'https://www.europarl.europa.eu/meps/en/';
    public function __construct(private readonly DecodeService $decodeService)
    {
    }

    public function scrape(string $name, int $refId): ContactInfoDto
    {
        $contactInfoDto = new ContactInfoDto();

        $client = new Client();
        $crawler = $client->request('GET', $this->buildScrapeRequestUrl($name, (string)$refId));
        $socialMediaContainer = $crawler->filter('#presentationmep > div > div:nth-child(2) > div > div > div.separator.separator-dotted.separator-1x.border-secondary.mb-3 > div');

        $contactInfoDto->addContactDetails(ContactsRepository::TYPE_ADDRESS, $this->getAddress($crawler))
            ->addContactDetails(ContactsRepository::TYPE_EMAIL, $this->getEmail($socialMediaContainer))
            ->addContactDetails(ContactsRepository::TYPE_SOCIAL, $this->getSocial($socialMediaContainer,'Instagram'))
            ->addContactDetails(ContactsRepository::TYPE_SOCIAL, $this->getSocial($socialMediaContainer,'Twitter'))
            ->addContactDetails(ContactsRepository::TYPE_SOCIAL, $this->getSocial($socialMediaContainer,'Facebook'));

        return $contactInfoDto;
    }

    private function getAddress(Crawler $crawler): string
    {
        $contactCards = $crawler->filter('#contacts > div > div.row.justify-content-center > div:nth-child(1) > div > div > div.erpl_contact-card-list > span')
            ->each(function ($node) {
                return $node->html();
            });

        return strip_tags($contactCards[0]);
    }

    private function getEmail(Crawler $container): string
    {
        $emailAnchor = $container->filter('a[data-original-title="E-mail"]');

        if ($emailAnchor->count()) {
            return $this->decodeService->decodeEmail($emailAnchor->attr('href')); // Extract the href attribute
        }

        return '';
    }

    private function getSocial(Crawler $container, string $type): string
    {
        $emailAnchor = $container->filter('a[data-original-title="'.$type.'"]');

        if ($emailAnchor->count()) {
            return $emailAnchor->attr('href'); // Extract the href attribute
        }

        return '';
    }
    private function buildScrapeRequestUrl(string $name, string $id): string
    {
        $fullName = strtoupper($name);
        $fullName = str_replace(' ', '_', $fullName);

        return self::BASE_URL . $id . '/' . $fullName . '/home';
    }
}