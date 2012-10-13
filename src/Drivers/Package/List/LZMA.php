<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    self::setFn('Process', function ($Call)
    {
        shell_exec('lzma -c '.$Call['PackagesFilename'].' > '.$Call['PackagesFilename'].'.lzma');
        return $Call;
    });