<?php

declare(strict_types=1);

namespace App;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\Promise\Utils;
use Generator;

final class EventGenerator
{
    private $userCount;
    private $messageCount;

    public function __construct(
        int $userCount = 1000,
        int $messageCount = 10
    ) {
        $this->userCount = $userCount;
        $this->messageCount = $messageCount;
    }

    public function promise(
        int $id,
        string $msg
    ): PromiseInterface {
        $url = env('SERVICE_URL', 'http://127.0.0.1:80/event/new');

        $data = new class {
            public $userId;
            public $event;
        };

        $data->userId = $id;
        $data->event = $msg;

        $options = [
            'debug' => env('SEND_DEBUG', false),
            'headers' => [
                'User-Agent' => 'eventGenerator/0.1',
                'Accept' => 'application/json',
            ],
            'json' => [
                'data' => $data,
            ],
        ];

        $client = new Client();
        $request = new Request('POST', $url, $options['headers']);

        return $client->sendAsync($request, $options);
    }

    public function run(): void
    {
        $promiseArray = [];
        foreach ($this->userIdGen(1, $this->userCount) as $userId) {
            foreach ($this->getMessage($userId, $this->messageCount) as $index => $message) {
                $msg = "user ID: {$userId} | send message: {$message}";
                $promiseArray["{$userId}_{$index}"] = $this->promise($userId, $msg);
                if (env('DEBUG', false)) {
                    echo $msg . "\n";
                }
            }
        }
        echo "wait to end send event \n";
        Utils::settle($promiseArray)->wait();
        echo "send event complete";
    }

    private function userIdGen($start, $limit): ?Generator
    {
        for ($i = $start; $i <= $limit; $i++) {
            yield $i;
        }
    }

    private function getMessage(int $id, int $limit): ?Generator
    {
        $baseSrting = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        $str = '';
        $count = 5;
        while ($count > 0) {
            try {
                $key = random_int(1, 51);
            } catch (Exception $e) {
                $key = 2;
            }
            $str .= $id . $baseSrting[$key];
            $count--;
        }

        for ($i = 1; $i <= $limit; $i++) {
            $str[0] = $i . $id;
            $str[5] = $i . $id;
            yield base64_encode(md5($str));
        }
    }
}
