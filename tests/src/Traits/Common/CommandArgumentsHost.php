<?php

namespace Robo\Traits\Common;

use Robo\Common\CommandArguments;
use Robo\Common\ProcessUtils;

class CommandArgumentsHost
{
    use CommandArguments;

    /**
     * @return string
     */
    public function getArguments()
    {
        return ProcessUtils::replacePlaceholders($this->arguments, $this->argumentsEnv);
    }
}
