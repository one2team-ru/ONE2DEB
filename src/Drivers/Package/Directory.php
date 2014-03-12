<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */


    setFn('Do', function ($Call)
    {
        $LockFile = Root.'/locks/repo';

        $IX = 0;

        while(file_exists($LockFile) and $IX < 60)
        {
            sleep(1);
            $IX++;
        }

        {
            touch($LockFile);

            $Files = explode(PHP_EOL, trim(shell_exec('ls '.Root.'/Data/Package/*.deb')));

            if (!empty($Files[0]))
            {
                $Data = [];

                arsort($Files);

                F::Run('Entity', 'Delete', ['Entity' => 'Package']);

                foreach ($Files as $IX => $File)
                {
                    $Meta = F::Run('Package.Meta', 'Read', ['Data' => ['File' => $File]]);

                    if (!isset($Versions[$Meta['Package']][$Meta['Architecture']]))
                        $Versions[$Meta['Package']][$Meta['Architecture']] = 0;

                    if (strnatcmp($Versions[$Meta['Package']][$Meta['Architecture']], $Meta['Version'])<0)
                    {
                        $Data[$IX]['File'] = $File;

                        if (isset($Meta['X-Private']))
                            $Data[$IX]['X-Private'] = $Meta['X-Private'];
                        else
                            $Data[$IX]['X-Private'] = 'No';

                        $Data[$IX]['Modified'] = filemtime($File);
                        $Versions[$Meta['Package']][$Meta['Architecture']] = $Meta['Version'];
                    }
                    else
                    {
                        if (unlink ($File))
                        {
                            $Call['Output']['Content'][] = [
                                'Type' => 'Block',
                                'Value' => $File.' removed, because '.$Versions[$Meta['Package']][$Meta['Architecture']].' > '.$Meta['Version']
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

            unlink($LockFile);
        }

        return $Call;
    });