<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        $PackageFilename = Root.'/Data/Lists/Packages';

        $Size = filesize($PackageFilename);
        $MD5 = md5_file($PackageFilename);
        $SHA1 = sha1_file($PackageFilename);
        $SHA256 = hash("sha256", file_get_contents($PackageFilename));

        $Call['Output']['Content'][] = 'Origin: '.$Call['Host'].'
Label: '.$Call['Host'].'
Suite: stable
Codename: squeeze
Architectures: amd64
Components: all

MD5Sum:
 '.$MD5.' '.$Size.' binary/Packages

SHA1:
 '.$SHA1.' '.$Size.' binary/Packages

SHA256:
 '.$SHA256.' '.$Size.' binary/Packages
';

        return $Call;
    });