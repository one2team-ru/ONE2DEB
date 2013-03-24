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

            F::Run('Entity', 'Delete', [
                'Entity' => 'Package',
                'Where' => []
            ]);

            foreach ($Files as $File)
            {
                $Meta = F::Run('Package.Meta', 'Read', ['Data' => ['File' => $File]]);

                if (!isset($Packages[$Meta['Package']][$Meta['Architecture']])
                    or strnatcmp($Packages[$Meta['Package']][$Meta['Architecture']], $Meta['Version'])<0)
                {
                    $Data['File'] = $File;
                    $Data['Modified'] = filemtime(Root.'/Data/Package/'.$File);

                    $Data = F::Run('Entity', 'Create',
                        [
                            'Entity' => 'Package',
                            'One' => true,
                            'Data' => $Data
                        ])['Data'];

                    F::Log($Data['Meta']['Package'].' version '.$Data['Meta']['Version'].' for '.$Data['Meta']['Architecture'].' architecture created', LOG_INFO);

                    $Packages[$Meta['Package']][$Meta['Architecture']] = $Meta['Version'];
                }
                else
                {
                    if (unlink (Root.'/Data/Package/'.$File))
                        F::Log($File.' removed', LOG_INFO);
                    else
                        F::Log($File.' not removed', LOG_ERR);
                }

                F::Log($File.' processed', LOG_INFO);
            }

            $Call['Output']['Content'][] =
                [
                    'Type' => 'Block',
                    'Class' => 'alert alert-success',
                    'Value' => '<l>Package.Directory:Packages.Updated</l>'
                ];
        }
        else
            $Call['Output']['Content'][] =
                [
                    'Type' => 'Block',
                    'Class' => 'alert alert-warning',
                    'Value' => '<l>Package.Directory:Packages.No</l>'
                ];


        return $Call;
    });