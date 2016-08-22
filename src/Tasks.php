<?php
namespace Robo;

use Robo\Common\IO;
use Robo\Contract\BuilderAwareInterface;
use Robo\Contract\IOAwareInterface;
use Robo\Common\BuilderAwareTrait;

class Tasks implements BuilderAwareInterface, IOAwareInterface
{
    use BuilderAwareTrait;
    use LoadAllTasks;
    use IO;

    protected function stopOnFail($stopOnFail = true)
    {
        Result::$stopOnFail = $stopOnFail;
    }
}
