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
        $ReleaseFilename = Root.'/Data/Lists/Release';

        if (true or filemtime($ReleaseFilename) < filemtime($PackageFilename))
        {
            $Size = filesize($PackageFilename);
            $MD5 = md5_file($PackageFilename);
            $SHA1 = sha1_file($PackageFilename);
            $SHA256 = hash("sha256", file_get_contents($PackageFilename));

        $Output = 'Origin: '.$Call['Links']['Production'].'
Label: '.$Call['Project']['Title'].'
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
            file_put_contents($ReleaseFilename, $Output);
            chdir(Root.'/Data/Lists/');
            shell_exec('gpg -abs -o Release.gpg Release');
            $Call['Output']['Content'][] = $Output;
        }
        else
            $Call['Output']['Content'][] =  file_get_contents($ReleaseFilename);

        return $Call;
    });