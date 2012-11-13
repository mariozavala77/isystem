<?php

/**
 * 渠道控制器
 *
 * @author: shaoqi <413729863@qq.com>
 * @copyright: Copyright (c) 2012 UFCEC Tech All Rights Reserved.
 * @version: $Id$
 */
class Channel_Controller extends Base_Controller {

    // 渠道默认页
    public function action_index(){
        $types = Config::get('application.channel_type');

        return View::make('channel.list')->with('channel_type', $types);
    }

    // 渠道列表
    public function action_filter(){
        $fields = ['type', 'name', 'alias', 'description', 'status', 'id'];
        $channel = Channel::filter($fields);
        $data = Datatables::of($channel)->make();

        return Response::json($data);
    }

    // 渠道添加
    public function action_add(){
        $type = Input::get('type'); // 获取分类
        $types = Config::get('application.channel_type'); // 获取允许的渠道分类

        if (!isset($types[$type])){
            session::flash('tips', '渠道分类不存在');

            return Redirect::back();
        }

        $view = 'channel.' . strtolower($type) . '_add';

        return View::make($view)->with('channel', $types[$type]);
    }

    // 插入渠道信息
    public function action_insert(){
        $data = [
            'type'         => Input::get('type'),
            'name'         => Input::get('name'),
            'alias'        => Input::get('alias'),
            'description'  => Input::get('description'),
            'accredit'     => serialize(Input::get('accredit')),
            'synced_at'    => date('Y-m-d H:i:s'),
            'language'     => Input::get('language'),
        ];

        if(Channel::insert( $data )) {
            session::flash('tips', sprintf('渠道%s添加成功！', $data['type']) );

            return Redirect::to('channel');
        } else {
            session::flash('tips', '添加失败。');

            return Redirect::back();
        }
    }

    // 渠道编辑
    public function action_edit(){

        $channel_id = Input::get('channel_id');
        $channel = Channel::info($channel_id);
        $channel->accredit = unserialize($channel->accredit);
        $types = Config::get('application.channel_type');
        $view = 'channel.' . strtolower($channel->type) . '_edit';

        return View::make($view)->with('channel', $channel)
                                 ->with('channel_type', $types);
    }

    // 更新渠道信息
    public function action_update(){
        $channel_id = Input::get('channel_id');

        $data = [
            'name'        => Input::get('name'),
            'alias'       => Input::get('alias'),
            'description' => Input::get('description'),
            'accredit'    => serialize(Input::get('accredit')),
            'language'    => Input::get('language'),
        ];

        Channel::update($channel_id, $data);

        return Redirect::to('channel');
    }

    // 删除渠道
    public function action_delete(){
        $channel_id = Input::get('channel_id');

        $channel_id = intval($channel_id);

        if (empty($channel_id)) {
            return Response::json([ 'status' => 'fail', 'message' => '渠道id无效']);
        }

        $result = Channel::delete($channel_id);

        if ($result) {
            $return = [ 'status' => 'success', 'message' => '删除成功！'];
        } else {
            $return = [ 'status' => 'fail', 'message' => '删除失败！'];
        }

        return Response::json($return);
    }
}