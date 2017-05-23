<?php

namespace Okvpn\R;

interface PipesInterface
{
    const CHUNK_SIZE = 65536;

    /**
     * Returns an array of descriptors for the use of proc_open.
     *
     * @return array
     */
    public function getDescriptors();

    /**
     * Reads data in file handles and pipes.
     *
     * @param string $input
     *
     * @return string[] An array of read data indexed by their fd
     */
    public function writeAndRead($input);

    /**
     * Returns if the current state has open file handles or pipes.
     *
     * @return bool
     */
    public function areOpen();

    /**
     * Closes file handles and pipes.
     */
    public function close();
}
