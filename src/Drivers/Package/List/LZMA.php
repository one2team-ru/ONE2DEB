<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Process', function ($Call)
    {
        shell_exec('lzma -c '.$Call['PackagesFilename'].' > '.$Call['PackagesFilename'].'.lzma');
        return $Call;
    });