<?php

namespace Okvpn\R\Process;

use Okvpn\R\Exception\RError;
use Okvpn\R\Exception\RuntimeException;
use Okvpn\R\PipesInterface;
use Okvpn\R\ROutput;
use Okvpn\R\RProcessInterface;

class RProcess implements RProcessInterface
{
    /** @var PipesInterface */
    protected $processPipes;

    /** @var string */
    protected $rPath;

    /** @var resource */
    protected $process;

    /**
     * @param PipesInterface $pipes
     * @param string $rPath
     */
    public function __construct(PipesInterface $pipes, $rPath)
    {
        if (!function_exists('proc_open')) {
            throw new RuntimeException(
                'The Process class relies on proc_open, which is not available on your PHP installation.'
            );
        }

        $this->processPipes = $pipes;
        $this->rPath = $rPath;
    }


    /**
     * {@inheritdoc}
     */
    public function write($rInput)
    {
        $rInputLines = explode("\n", $rInput);

        $rOutputs = [];
        $rErrors = [];
        foreach ($rInputLines as $line) {
            $output = $this->processPipes->writeAndRead($line . "\n");
            if (isset($output[1])) {
                $currentOutput = preg_replace('/\n>\x20$/', '', $output[1]);
                $currentOutput = explode("\n", $currentOutput);
                $rCurrentOutput = [];
                foreach ($currentOutput as $lineOutput) {
                    if ($lineOutput !== $line) {
                        $rCurrentOutput[] = $lineOutput;
                    }
                }

                $rOutputs[] = $rCurrentOutput ? implode("\n", $rCurrentOutput) : null;
            }

            if (isset($output[2])) {
                $rErrors[] = $output[2];
            }
        }

        $rOutput = new ROutput($rOutputs, $rErrors);
        if ($rErrors) {
            throw new RError($rOutput);
        }

        return $rOutput;
    }

    /**
     * {@inheritdoc}
     */
    public function start()
    {
        if ($this->processPipes->areOpen()) {
            throw new RuntimeException('Could not create the R process');
        }

        $process = proc_open(
            sprintf("\"%s\" --silent --vanilla", $this->rPath),
            $this->processPipes->getDescriptors(),
            $this->processPipes->pipes
        );

        if (!is_resource($process)) {
            throw new RuntimeException('Could not create the R process');
        }

        $this->process = $process;
        $this->processPipes->writeAndRead("options(error=expression(NULL))\n");
    }
}