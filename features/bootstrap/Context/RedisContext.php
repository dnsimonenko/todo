<?php declare(strict_types=1);

namespace App\Behat\Context;

use Behat\Behat\Context\Context;
use Symfony\Component\Process\Process;

class RedisContext implements Context
{
    /**
     * @Given I flush redis storage
     */
    public static function flushRedis()
    {
        $process = new Process(
            [
                'php',
                'bin/console',
                'redis:flushall',
                '--env=test',
                '--no-interaction',
            ]
        );

        $process->run();
    }
}
