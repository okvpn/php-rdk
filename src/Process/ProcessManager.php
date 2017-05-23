<?php

namespace Okvpn\R\Process;

use Okvpn\R\Builder\ExpressionBuilder;
use Okvpn\R\Exception\RError;
use Okvpn\R\ROutputInterface;
use Okvpn\R\RProcessInterface;
use Okvpn\R\UnixPipes;

class ProcessManager
{
    /** @var RProcessInterface $process */
    protected $process;

    /**
     * @param RProcessInterface $process
     */
    public function __construct(RProcessInterface $process)
    {
        $this->process = $process;
    }

    /**
     * @param string $rPath
     * @return static
     */
    public static function create($rPath = '/usr/bin/R')
    {
        if (DIRECTORY_SEPARATOR === '\\') {
            $pipes = new UnixPipes(); //todo: Should be added support Window platform
        } else {
            $pipes = new UnixPipes();
        }

        $process = new RProcess($pipes, $rPath);
        $process->start();

        return new static($process);
    }

    /**
     * @return ExpressionBuilder
     */
    public function createExpressionBuilder()
    {
        return new ExpressionBuilder($this->process);
    }

    /**
     * @param $rInput
     * @param bool $throwErrorException
     * @return ROutputInterface
     *
     * @throws RError
     */
    public function write($rInput, $throwErrorException = true)
    {
        try {
            return $this->process->write($rInput);
        } catch (RError $error) {
            if ($throwErrorException) {
                return $error->getOutput();
            }

            throw $error;
        }
    }
}
