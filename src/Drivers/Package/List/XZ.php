<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Process', function ($Call)
    {
        shell_exec('xz -c '.$Call['PackagesFilename'].' > '.$Call['PackagesFilename'].'.xz');
        return $Call;
    });