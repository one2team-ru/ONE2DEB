<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Check', function ($Call)
    {
        $Elements = F::Run('Entity', 'Read',
                    array(
                         'Entity' => 'Package',
                         'Where' =>
                             [
                                 'Meta.Package' => $Call['Data']['Meta']['Package'],
                                 'Meta.Version' => $Call['Data']['Meta']['Version'],
                                 'Meta.Architecture' => $Call['Data']['Meta']['Architecture']
                             ]
                    ));

        if (sizeof($Elements) > 0)
        {
            $Call['Failure'] = true;
            $Call = F::Hook('Package.NonUnique', $Call);
        }
        else
        {
            $Elements = F::Run('Entity', 'Read',
                    array(
                         'Entity' => 'Package',
                         'Where' =>
                             [
                                 'Meta.Package' => $Call['Data']['Meta']['Package'],
                                 'Meta.Architecture' => $Call['Data']['Meta']['Architecture']
                             ]
                    ));

            if (!empty($Elements))
            {
                $Call['Failure'] = true;

                foreach ($Elements as $Element)
                    if ($Call['Data']['Meta']['Version']>$Element['Meta']['Version'])
                    {
                        $Call['Failure'] = false;
                        break;
                    }
            }

            if ($Call['Failure'])
                $Call = F::Hook('Package.Outdated', $Call);
        }

        return $Call;
    });