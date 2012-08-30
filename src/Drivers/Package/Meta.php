<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    self::setFn('Read', function ($Call)
    {
        exec('dpkg --info '.(isset($Call['Data']['File']['tmp_name'])?
            $Call['Data']['File']['tmp_name']:
            Root.'/Data/Package/'.$Call['Data']['File']).' control', $Meta, $Return);

        $NewMeta = [];
        if ($Return == 0)
        {
            foreach ($Meta as $Line)
            {
                if (preg_match('@^(.*)\:(.*)$@SsUu', $Line, $Pockets))
                    list(,$Key, $Value) = $Pockets;
                else
                    $Value = nl2br($Line);

                $NewMeta[trim($Key)].= trim($Value);
            }
        }

        return $NewMeta;
    });