<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Generate', function ($Call)
    {
        $Elements = F::Run('Entity', 'Read',
                    array(
                         'Entity' => 'Package'
                    ));

        $Output = '';

        if (isset($Call['Key']))
            $Call['PackagesFilename'] = Root.'/Data/Lists/'.$Call['Key'];
        else
            $Call['PackagesFilename'] = Root.'/Data/Lists/Packages';

        $Mtimes = array_map(function($Element){return $Element['Modified'];}, $Elements);

        $Mtime = filemtime($Call['PackagesFilename']);

        if (($Mtime < max($Mtimes)) or ($Mtime == false))
        {
            foreach ($Elements as $Element)
            {
                $Decision = true;

                if ($Element['Meta']['X-Private'] != 'No' && $Call['Key'] != $Element['Meta']['X-Private'])
                    $Decision = false;

                if (!$Decision)
                    continue;
                /*
                            if (isset($Element['Meta']['X-Private']))
                                $Private = $Element['Meta']['X-Private'].'/';
                            else
                                $Private = '';*/

                if (!empty($Element['Meta']['Package']))
                {
                    $Description = $Element['Meta']['Description'];
                    unset($Element['Meta']['Description']);

                    $Package = 'Package: '.$Element['Meta']['Package'].PHP_EOL;
                    unset($Element['Meta']['Package']);

                    $Element['Meta']['Size'] = filesize(Root.'/Data/Package/'.$Element['File']);
                    $Element['Meta']['MD5'] = md5_file(Root.'/Data/Package/'.$Element['File']);
                    $Element['Meta']['SHA1'] = sha1_file(Root.'/Data/Package/'.$Element['File']);

                    foreach ($Element['Meta'] as $Key => $Value)
                        if (!empty($Value))
                            $Package.= $Key.': '.$Value.PHP_EOL;

                    $Package.= 'Filename: download/'.$Element['Name'].PHP_EOL;
                    $Output.= htmlspecialchars_decode($Package.'Description: '.$Description.PHP_EOL).PHP_EOL;
                }
            }

            file_put_contents($Call['PackagesFilename'], $Output);

            $Call = F::Hook('afterBuild', $Call);
            
        }
        else
        {
            if (isset($Call['Format']))
            {
                $Call['PackagesFilename'].= $Call['Format'];
                readfile($Call['PackagesFilename']);
            }
            else
                $Output = file_get_contents($Call['PackagesFilename']);
        }

        $Call['Output']['Content'][] = $Output;

        return $Call;
    });