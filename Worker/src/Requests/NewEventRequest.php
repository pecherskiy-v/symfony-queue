<?php

declare(strict_types=1);

namespace App\Requests;

use Symfony\Component\HttpFoundation\Request;
use Psr\Log\LoggerInterface;
use App\Requests\Dto\NewEventDto;
use Throwable;
use RuntimeException;

final class NewEventRequest
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param Request $request
     *
     * @return NewEventDto
     * @throws Throwable
     */
    public function getData(Request $request): NewEventDto
    {
        $className = $this->getClass();
        $requestDto = new $className();
        try {
            $dataArray = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
            foreach ($dataArray['data'] as $property => $value) {
                if (property_exists($requestDto, $property)) {
                    $requestDto->$property = $value;
                }
            }
            return $requestDto;
        } catch (Throwable $e) {
            $this->logger->error(
                $e->getMessage(),
                [
                    'content' => $request->getContent()
                ]
            );
            throw new RuntimeException('Not correct request');
        }
    }

    private function getClass(): string
    {
        return NewEventDto::class;
    }
}
