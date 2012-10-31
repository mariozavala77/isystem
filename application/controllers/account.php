<?php
/**
 * 用户中心
 *
 * @author: weelion <weelion@qq.com>
 * @copyright: Copyright (c) 2012 EIMO Tech All Rights Reserved.
 * @version: $Id:account.php  2012年10月25日 星期四 13时54分24秒Z $
 */
class Account_Controller extends Base_Controller {

    // 用户登录页面
    public function action_login() {

        if(Sentry::check()) return Redirect::to('/');

        return View::make('account.login');
    }

    // 用户登录处理
    public function action_do_login() {

        $input = Input::all();

        // 验证 & 登录
        $rules = Config::get('validation.login');
        $validation = Validator::make($input, $rules);
        if( $validation->fails() ) {
            $result = 'validation_fail';
        } else {
            if( !User::login($input['username'], $input['password'], $input['remember']) ) {
                $result = 'login_fail';
            } else {
                $result = 'login_success';
            }
        }

        return Response::json($result);
    }

    // 用户退出
    public function action_logout() {
        User::logout();
        Session::flash('tips', '您已成功退出！');

        return Redirect::to('account/login');
    }

    /*
    public function action_add() {
        User::add('test', 'test', 'weelion@qq.com');
    }
     */
}
?>
