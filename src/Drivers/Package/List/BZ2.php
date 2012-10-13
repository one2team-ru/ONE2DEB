<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    self::setFn('Process', function ($Call)
    {
        shell_exec('bzip2 -c '.$Call['PackagesFilename'].' > '.$Call['PackagesFilename'].'.bz2');
        return $Call;
    });