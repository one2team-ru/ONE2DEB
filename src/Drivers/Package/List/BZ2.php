<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Process', function ($Call)
    {
        $bz2 = bzopen($Call['PackagesFilename'].'.bz2', "w");
        bzwrite($bz2, file_get_contents($Call['PackagesFilename']));
        bzclose($bz2);
        return $Call;
    });