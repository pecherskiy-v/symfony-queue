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

final class EventsController extends AbstractController
{
    /**
     * @Route("/event/new", name="event_new")
     * @param Request         $request
     * @param LoggerInterface $logger
     * @param NewEventRequest $eventRequest
     *
     * @return Response
     * @throws Throwable
     */
    public function new(Request $request, LoggerInterface $logger, NewEventRequest $eventRequest): Response
    {
        $dto = $eventRequest->getData($request);
        return new Response('all ok ');
    }
}
