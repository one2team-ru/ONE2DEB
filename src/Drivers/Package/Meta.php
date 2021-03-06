<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Read', function ($Call)
    {
        exec('dpkg --info '.(isset($Call['Data']['File']['tmp_name'])?
            $Call['Data']['File']['tmp_name']:
            $Call['Data']['File']).' control', $Meta, $Return);

        $NewMeta = [];
        if ($Return == 0)
        {
            foreach ($Meta as $Line)
            {
                if (preg_match('@^(\S*)\:(.*)$@SsUu', $Line, $Pockets))
                    list(,$Key, $Value) = $Pockets;
                else
                    $Value = $Line;

                $Key = trim($Key);

                $Value = htmlentities($Value);
                if (isset($NewMeta[$Key]))
                    $NewMeta[$Key].= PHP_EOL.trim($Value);
                else
                    $NewMeta[$Key]= trim($Value);
            }
        }

        $NewMeta['Description'] = nl2br($NewMeta['Description']);

        if (empty($NewMeta['X-Private']))
            $NewMeta['X-Private'] = 'No';

        return $NewMeta;
    });