<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Read', function ($Call)
    {
        return $Call['Data']['Meta']['Package'].'_'.$Call['Data']['Meta']['Version'].'_'.$Call['Data']['Meta']['Architecture'].'.deb';
    });