<?php
/**
 * 物流信息跟踪
 *
 * @author: shaoqi <shaoqisq123@gmail.com>
 * @copyright: Copyright (c) 2012 UFCEC Tech All Rights Reserved.
 * @version: $Id$
 */
class AgentAPILogException extends Exception{}

class TrackAPI{

    public function get($com, $nu){
        $kuaidi = ['Kuaidi' => ['dhl', 'ups', 'usps', 'fedex', 'tnt', 'ems', 'shunfeng', 'fedexus'], 
                    //'Icha'      => ['shunfeng'],
                    'Royalmail' => ['royalmail'],
                   ];
        $interface = '';
        $com = ($com=='EUB' || $com=='eub')?'ems':$com;

        foreach ($kuaidi as $key => $value) {
            if(in_array($com, $value)){
                $interface = $key;
            }
        }
        if(in_array($com, ['singPost', 'HK Post'])){
            $interface = 'Royalmail';
        }

        if($com == 'Amazon FBA'){
            $interface = 'Amazon';
        }

        $interface = empty($interface)?$com:$interface;

        $interface = 'TrackAPI_' . ucfirst($interface);

        $api = new $interface();
        return $api->get($com, $nu);
    }
}