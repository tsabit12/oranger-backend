<?php
class Model_auth extends CI_Model
{
    public function login($body){
        $result['success'] = true;
        $result['data'] = array(
            "iat" => 1356999524,
            "nbf" => 1357000000,
            "uid" => 596283773,
            'fullname' => 'Tsabit Abdul Aziz',
            'level' => 'P01'
        );

        return $result;
    }
}
?>