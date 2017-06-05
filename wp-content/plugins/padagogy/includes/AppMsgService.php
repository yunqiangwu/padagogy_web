<?php

/**
 * Created by PhpStorm.
 * User: wayne
 * Date: 2017/6/4
 * Time: 14:35
 */
use Goutte\Client;

class AppMsgService
{

    protected static $single_instance = null;

    protected $client = null;

    public static function get_instance() {
        if ( null === self::$single_instance ) {
            self::$single_instance = new self();
        }

        return self::$single_instance;
    }

    function __construct()
    {
        $this->AppMsgService();
    }

    private function AppMsgService(){
        $this->client = new Client();
    }

    public function getAppMsgListByName(){

        return 'asdfasdfasdfasdf';
    }


}