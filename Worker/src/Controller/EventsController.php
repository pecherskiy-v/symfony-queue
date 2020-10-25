<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Requests\NewEventRequest;
use Throwable;
use Psr\Log\LoggerInterface;
use App\Managers\ProducerManager;

final class EventsController extends AbstractController
{
    /**
     * @Route("/event/new", name="event_new")
     * @param Request         $request
     * @param LoggerInterface $logger
     * @param NewEventRequest $eventRequest
     * @param ProducerManager $producerManager
     *
     * @return Response
     * @throws Throwable
     */
    public function new(
        Request $request,
        LoggerInterface $logger,
        NewEventRequest $eventRequest,
        ProducerManager $producerManager
    ): Response {
        $logger->debug('took the event');
        $dto = $eventRequest->getData($request);
        $logger->debug('make event dto end', [json_encode($dto)]);
        $msg = [
            'userId' => $dto->userId,
            'message' => $dto->event,
            'timeshtamp' => time(),
            ];
        $producerManager->publish(json_encode($msg));
        return new Response('all ok ');
    }
}
