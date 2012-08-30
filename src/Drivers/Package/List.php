<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    self::setFn('Generate', function ($Call)
    {
        $Elements = F::Run('Entity', 'Read',
                    array(
                         'Entity' => 'Package'
                    ));

        $Output = '';

        foreach ($Elements as $Element)
        {
            $Decision = true;

            if (isset($Element['Meta']['X-Private']) && $Call['Key'] != $Element['Meta']['X-Private'])
                $Decision = false;

            if (!$Decision)
                continue;

            if (isset($Element['Meta']['X-Private']))
                $Private = $Element['Meta']['X-Private'].'/';
            else
                $Private = '';

            $Description = $Element['Meta']['Description'];
            unset($Element['Meta']['Description']);

            $Package = 'Package: '.$Element['Meta']['Package'].PHP_EOL;
            unset($Element['Meta']['Package']);

            $Element['Meta']['Size'] = filesize(Root.'/Data/Package/'.$Element['File']);
            $Element['Meta']['MD5'] = md5_file(Root.'/Data/Package/'.$Element['File']);
            $Element['Meta']['SHA1'] = sha1_file(Root.'/Data/Package/'.$Element['File']);

            foreach ($Element['Meta'] as $Key => $Value)
                $Package.= $Key.': '.$Value.PHP_EOL;

            $Package.= 'Filename: download/'.$Element['Name'].PHP_EOL;
            $Output.= htmlspecialchars_decode($Package.'Description: '.$Description.PHP_EOL).PHP_EOL;
        }


        $Call['Output']['Content'][] = $Output;

        return $Call;
    });