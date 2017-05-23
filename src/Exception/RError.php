<?php

namespace Okvpn\R\Exception;

use Okvpn\R\ROutputInterface;

class RError extends \Exception implements RExceptionInterface
{
    /** @var ROutputInterface */
    protected $output;

    /**
     * @param ROutputInterface $output
     */
    public function __construct(ROutputInterface $output)
    {
        $this->output = $output;
        $message = 'An error occurred in R:' . implode(", ", $output->getAllError());

        parent::__construct($message);
    }

    /**
     * @return ROutputInterface
     */
    public function getOutput()
    {
        return $this->output;
    }
}
