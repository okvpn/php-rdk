<?php

namespace Okvpn\R\Process;

use Okvpn\R\Builder\ExpressionBuilder;
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
        $process = new RProcess(new UnixPipes(), $rPath);
        $process->start();

        return new static($process);
    }

    /**
     * @return ExpressionBuilder
     */
    public function getQueryBuilder()
    {
        return new ExpressionBuilder($this->process);
    }
}
