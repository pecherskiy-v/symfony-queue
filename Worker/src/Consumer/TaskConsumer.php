<?php

declare(strict_types=1);

namespace App\Consumer;

use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use Psr\Log\LoggerInterface;
use App\Consumer\Dto\TaskDto;
use Carbon\Carbon;

final class TaskConsumer implements ConsumerInterface
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @return void
     * @var AMQPMessage $msg
     */
    public function execute(AMQPMessage $msg): void
    {
        // $this->logger->debug('new AMQPMessage');
        // так как у нас в этот обработчик попадает только один тип сообщений
        // то тут только один варинат объекта.
        $msgDto = new TaskDto($msg->getBody());
        // ждем 1 сек.
        sleep(1);
        // пишем в лог сообщение.
        $this->logger->info(
            "new event message ({$msgDto->message}) from user: {$msgDto->userId}",
            ['timeshtamp' => Carbon::createFromTimestamp($msgDto->timeshtamp)->format('H:i:s')]
        );
        // $this->logger->debug('message processing completed');
    }
}
