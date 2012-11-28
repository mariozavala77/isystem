<?php
class Statistics_Controller extends Base_Controller {
    public function action_index(){
        return View::make('statistics.main');
    }
}