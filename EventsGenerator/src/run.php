<?php
declare(strict_types=1);

use App\EventGenerator;

require_once __DIR__ . '/../vendor/autoload.php';

if (!function_exists('value')) {
    function value($value)
    {
        return $value instanceof Closure ? $value() : $value;
    }
}

if (!function_exists('env')) {
    function env($key, $default = null)
    {
        $value = getenv($key);

        if (false === $value) {
            return value($default);
        }

        switch (strtolower($value)) {
            case 'true':
            case '(true)':
                return true;

            case 'false':
            case '(false)':
                return false;

            case 'empty':
            case '(empty)':
                return '';

            case 'null':
            case '(null)':
                return null;
        }

        if (is_numeric($value)) {
            if ((float)$value > (int)$value) {
                return (float)$value;
            }
            return (int)$value;
        }

        $startsWith = function ($haystack, $needles) {
            foreach ((array)$needles as $needle) {
                if ('' !== $needle && 0 === strpos($haystack, (string)$needle)) {
                    return true;
                }
            }

            return false;
        };

        $endsWith = function ($haystack, $needles) {
            foreach ((array)$needles as $needle) {
                if (substr($haystack, -strlen($needle)) === (string)$needle) {
                    return true;
                }
            }

            return false;
        };

        if ($startsWith($value, '"') && $endsWith($value, '"')) {
            return substr($value, 1, -1);
        }

        return $value;
    }
}


try {
    (new Dotenv\Dotenv(__DIR__ . '/../'))->load();
} catch (Dotenv\Exception\InvalidPathException $e) {
    //
}

$eventGenerator = new EventGenerator(env('USER_COUNT'), env('MESSAGE_COUNT'));
$eventGenerator->run();
