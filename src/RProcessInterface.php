<?php

namespace Okvpn\R;

interface RProcessInterface
{
    /**
     * @param $rInput
     * @return ROutput
     */
    public function write($rInput);

    /**
     * Create R process
     */
    public function start();
}
