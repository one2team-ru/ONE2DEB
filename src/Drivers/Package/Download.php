<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */


    self::setFn('Do', function ($Call)
    {

        $Call['Data'] = F::Run('Entity', 'Read', $Call,
                    array(
                         'Entity' => 'Package'
                    ))[0];

        if (!isset($Call['Data']['Meta']['X-Private']) || $Call['Key'] == $Call['Data']['Meta']['X-Private'])
        {
            $Call['Output']['Title'] = $Call['Data']['Name'];
            $Call['Output']['File'] = Root.'/Data/Package/'.$Call['Data']['File'];
        }

        return $Call;
    });