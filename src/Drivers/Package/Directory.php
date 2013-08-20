<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */


    setFn('Do', function ($Call)
    {
        $Files = explode(PHP_EOL, trim(shell_exec('ls '.Root.'/Data/Package')));

        if (!empty($Files[0]))
        {
            arsort($Files);

            F::Run('Entity', 'Delete', ['Entity' => 'Package']);

            foreach ($Files as $IX => $File)
            {
                $Meta = F::Run('Package.Meta', 'Read', ['Data' => ['File' => $File]]);

                if (!isset($Packages[$Meta['Package']][$Meta['Architecture']])
                    or strnatcmp($Packages[$Meta['Package']][$Meta['Architecture']], $Meta['Version'])<0)
                {
                    $Data[$IX]['File'] = $File;
                    $Data[$IX]['Modified'] = filemtime(Root.'/Data/Package/'.$File);

                    $Packages[$Meta['Package']][$Meta['Architecture']] = $Meta['Version'];


                }
                else
                {
                    if (unlink (Root.'/Data/Package/'.$File))
                        $Call['Output']['Content'][] = $File.' removed'.PHP_EOL;
                    else
                        $Call['Output']['Content'][] = $File.' not removed'.PHP_EOL;
                }

                $Call['Output']['Content'][] =  $File.' processed'.PHP_EOL;
            }

            F::Run('Entity', 'Create',
                [
                    'Entity' => 'Package',
                    'Data' => $Data
                ]);

            $Call['Output']['Content'][] = '<l>Package.Directory:Packages.Updated</l>'.PHP_EOL;
        }
        else
            $Call['Output']['Content'][] = '<l>Package.Directory:Packages.No</l>'.PHP_EOL;


        return $Call;
    });