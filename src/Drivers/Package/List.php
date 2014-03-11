<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Generate', function ($Call)
    {
        if (!isset($Call['Key']))
            $Call['Key'] = 'No';

        $Elements = F::Run('Entity', 'Read', ['Entity' => 'Package', 'Where' => ['X-Private' => $Call['Key']]]);

        $Elements = F::Sort($Elements, 'Meta.Package', SORT_ASC);

        foreach ($Elements as $Element)
        {
            $Block = '';

            if (!empty($Element['Meta']['Package']))
            {
                $URL = $Element['Meta']['Package'].'_'.$Element['Meta']['Version'].'_'.$Element['Meta']['Architecture'].'.deb';

                $Package = 'Package: '.$Element['Meta']['Package'].PHP_EOL;
                unset($Element['Meta']['Package']);

                $Description = $Element['Meta']['Description'];
                unset($Element['Meta']['Description']);

                $Element['Meta']['Size'] = filesize($Element['File']);
                $Element['Meta']['MD5'] = md5_file($Element['File']);
                $Element['Meta']['SHA1'] = sha1_file($Element['File']);

                foreach ($Element['Meta'] as $Key => $Value)
                    if (!empty($Value))
                        $Package.= $Key.': '.$Value.PHP_EOL;

                $Package.= 'Filename: download/'.$URL.PHP_EOL;

                $Block.= htmlspecialchars_decode($Package.'Description: '.$Description.PHP_EOL).PHP_EOL;

                $Call['Output']['Content'][] = $Block;
            }
        }


        return $Call;
    });