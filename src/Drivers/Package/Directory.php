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
            asort($Files);

            F::Run('Entity', 'Delete', ['Entity' => 'Package']);

            foreach ($Files as $IX => $File)
            {
                $Meta = F::Run('Package.Meta', 'Read', ['Data' => ['File' => $File]]);

                if (isset($Versions[$Meta['Package']][$Meta['Architecture']]))
                {
                    if(strnatcmp($Versions[$Meta['Package']][$Meta['Architecture']], $Meta['Version'])<0)
                    {
                        $Data[$IX]['File'] = $File;
                        $Data[$IX]['Modified'] = filemtime(Root.'/Data/Package/'.$File);
                        $Versions[$Meta['Package']][$Meta['Architecture']] = $Meta['Version'];
                    }
                    else
                    {
                        if (unlink (Root.'/Data/Package/'.$File))
                        {
                            $Call['Output']['Content'][] = [
                                'Type' => 'Block',
                                'Value' => $File.' removed'
                            ];
                        }
                        else
                            $Call['Output']['Content'][] = [
                                'Type' => 'Block',
                                'Value' => $File.' not removed'
                            ];
                    }

                    $Call['Output']['Content'][] = [
                                'Type' => 'Block',
                                'Value' => $File.' processed'
                            ];
                }
            }

            F::Run('Entity', 'Create',
                [
                    'Entity' => 'Package',
                    'Data' => $Data
                ]);

            $Call['Output']['Content'][] = [
                'Type' => 'Block',
                'Value' => '<l>Package.Directory:Packages.Updated</l>'
            ];
        }
        else
            $Call['Output']['Content'][] = [
                'Type' => 'Block',
                'Value' => '<l>Package.Directory:Packages.No</l>'
            ];


        return $Call;
    });