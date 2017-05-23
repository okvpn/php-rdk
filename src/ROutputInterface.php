<?php

namespace Okvpn\R;

interface ROutputInterface
{
//    public function __construct(array $error, array $output);

    /**
     * @return array
     */
    public function getAllOutput();

    /**
     * @return array
     */
    public function getAllError();

    /**
     * @param string|null $type
     * @return mixed
     */
    public function getLastOutput($type = null);

    /**
     * @return string
     */
    public function getLastError();
}
