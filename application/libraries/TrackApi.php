<?php
/**
 * 物流信息跟踪
 *
 * @author: shaoqi <shaoqisq123@gmail.com>
 * @copyright: Copyright (c) 2012 UFCEC Tech All Rights Reserved.
 * @version: $Id$
 */
class TrackApi{

    const KUAIDI = ['Kuaidi'    => ['dhl', 'ups', 'usps', 'fedex', 'tnt'], 
                    'Icha'      => ['ems', 'shunfeng'], 
                    'Royalmail' => ['royalmail'],
                    ]; 

    public static function get($com, $nu, $valicode){
        $interface = '';
        foreach (KUAIDI as $key => $value) {
            if(in_array($com, $value)){
                $interface = $key;
            }
        }

        if(empty($interface)){
            if($com == ''){

            }else{

            }
        }
    }
}