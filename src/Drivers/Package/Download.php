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
                    [
                        'Entity' => 'Package',
                        'One' => true
                    ]);

        if ($Call['Data']['Meta']['X-Private'] == 'No' || $Call['Key'] == $Call['Data']['Meta']['X-Private'])
            $Call['Output']['Content'] = $Call['Data']['File'];

        return $Call;
    });