<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Process', function ($Call)
    {
        shell_exec('gzip -c '.$Call['PackagesFilename'].' > '.$Call['PackagesFilename'].'.gz');
        return $Call;
    });