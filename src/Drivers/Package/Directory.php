<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */


    self::setFn('Do', function ($Call)
    {
        $Files = explode(PHP_EOL, trim(shell_exec('ls '.Root.'/Data/Package')));

        arsort($Files);

        F::Run('Entity', 'Delete', [
            'Entity' => 'Package'
        ]);

        foreach ($Files as $File)
        {
            $Meta = F::Run('Package.Meta', 'Read', ['Data' => ['File' => $File]]);

            if (!isset($Packages[$Meta['Package']][$Meta['Architecture']])
                or strnatcmp($Packages[$Meta['Package']][$Meta['Architecture']], $Meta['Version'])<0)
            {
                $Data['File'] = $File;
                $Data['Modified'] = filemtime(Root.'/Data/Package/'.$File);

                F::Run('Entity', 'Create',
                    [
                        'Entity' => 'Package',
                        'Data' => $Data
                    ]);

                $Packages[$Meta['Package']][$Meta['Architecture']] = $Meta['Version'];
            }
            else
                unlink (Root.'/Data/Package/'.$File);
        }

        return $Call;
    });