<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */


    setFn('Do', function ($Call)
    {

        $Call['Data'] = F::Run('Entity', 'Read', $Call,
                    array(
                         'Entity' => 'Package'
                    ))[0];

        if ($Call['Data']['Meta']['X-Private'] == 'No' || $Call['Key'] == $Call['Data']['Meta']['X-Private'])
        {
            header('X-Accel-Redirect: /Package/'.$Call['Data']['File']);
        }

        return $Call;
    });