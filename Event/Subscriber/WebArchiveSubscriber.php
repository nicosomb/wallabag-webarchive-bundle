<?php

namespace Nicosomb\WallabagWebarchiveBundle\Event\Subscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Psr\Log\LoggerInterface;
use Wallabag\CoreBundle\Helper\ContentProxy;
use Wallabag\WebArchiveBundle\Helper\Webarchive;
use Wallabag\CoreBundle\Event\EntrySavedEvent;
use Doctrine\ORM\EntityManager;

class WebarchiveSubscriber implements EventSubscriberInterface
{
    private $em;
    private $logger;
    private $wearchive;
    private $contentProxy;
    private $enabled;

    public function __construct(EntityManager $em, $enabled, Webarchive $webarchive, ContentProxy $contentProxy, LoggerInterface $logger)
    {
        $this->em = $em;
        $this->webarchive = $webarchive;
        $this->contentProxy = $contentProxy;
        $this->logger = $logger;
        $this->enabled = $enabled;
    }

    public static function getSubscribedEvents()
    {
        return [
            EntrySavedEvent::NAME => 'onEntrySaved',
        ];
    }

    /**
     * If http status is 404, ask web.archive if there is a snapshot
     *
     * @param EntrySavedEvent $event
     */
    public function onEntrySaved(EntrySavedEvent $event)
    {
        if (false === $this->enabled) {
            $this->logger->debug('WebArchiveSubscriber: disabled.');

            return;
        }

        $entry = $event->getEntry();

        if (200 === $entry->getHttpStatus()) {
            $this->logger->debug('WebArchiveSubscriber: http status == 200, no need to call web.archive.org');

            return;
        }

        $url = $this->webarchive->getArchiveUrl($entry->getUrl());
        $this->contentProxy->updateEntry($entry, $url);

        $this->em->persist($entry);
        $this->em->flush();
    }
}
