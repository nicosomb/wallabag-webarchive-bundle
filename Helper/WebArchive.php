<?php

namespace Nicosom\WallabagWebarchiveBundle\Helper;

use Psr\Log\LoggerInterface;
use GuzzleHttp\Client;

class Webarchive
{
    private $client;
    private $logger;

    public function __construct(Client $client, LoggerInterface $logger)
    {
        $this->client = $client;
        $this->logger = $logger;
    }

    /**
     * Fetch WebArchive to retrieve old version.
     *
     * @param string $url URL of the entry
     *
     * @return \DateTime
     */
    public function getArchiveUrl($url)
    {
        $response = $this->client->get('https://archive.org/wayback/available?url='.$url);
        $body = $response->getBody();
        $content = json_decode($body->getContents());

        $snapshots = $content->{'archived_snapshots'};
        if ('{}' === $snapshots) {
            $this->logger->debug('WebArchive: no snapshot');

            return false;
        }

        $url = $snapshots->{'closest'}->{'url'};
        $this->logger->debug('WebArchive: found snapshot ' . $url);

        return $url;
    }
}
