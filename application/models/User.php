<?php

/**
 * 用户模型
 *
 * @author: weelion <weelion@qq.com>
 * @copyright: Copyright (c) 2012 EIMO Tech All Rights Reserved.
 * @version: $Id:User.php  2012年10月25日 星期四 14时16分22秒Z $
 */
class User {

    /**
     * 用户登录
     *
     * @param: $username string  帐号
     * @param: $password string  密码
     * @param: $remember boolean 是否记住登录信息
     *
     * return boolean
     */
    public static function login( $username, $password, $remember ) {
        try{
            return Sentry::login( $username, $password, $remember);
        } catch (Sentry\SentryException $e) {
            return false;
        }
    }

    /**
     * 用户登出
     *
     * @return: viod
     */
    public static function logout() {
        Sentry::logout();
    }

    /**
     * 用户列表
     *
     * @param: $fields array 字段
     * @param: $filter array 附加筛选条件
     *
     * @return: object
     */
    public static function filter( $fields, $filter = [] ) {
        $query = DB::table('users')->select( $fields );
        foreach($filter as $key => $value) {
            $query = $query->where($key, '=', $value);
        }

        return $query;
    }

    /**
     *  新增用户
     *
     * @param: $username string 帐号
     * @param: $password string 密码
     * @param: $email    string 邮箱
     *
     * return void
     */
    public static function add( $username, $password, $email ) {
        $data = compact('username', 'password', 'email');
        Sentry::user()->create($data);
    }

    /**
     * 获取单条用户的信息
     *
     * @param: $users_id integer 用户ID
     *
     * return object
     */
    public static function info($users_id){
        return DB::table('users')->where('id', '=', $users_id)->first();
    }

}