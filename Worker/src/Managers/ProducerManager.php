<?php

declare(strict_types=1);

namespace App\Managers;

use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

final class ProducerManager
{
    private $container;
    private $logger;

    public function __construct(
        ContainerInterface $container,
        LoggerInterface $logger
    ) {
        $this->container = $container;
        $this->logger = $logger;
    }

    public function publish(string $msg, string $queue = 'task'): void
    {
        $this->container->get("old_sound_rabbit_mq.{$queue}_producer")->publish($msg);
        $this->logger->debug('message publish', ['queue' => $queue, 'message' => $msg]);
    }
}
