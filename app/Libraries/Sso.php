<?php

namespace App\Libraries;

use Http;

class Sso
{
    protected string $token_url, $login_url, $username, $password, $token_reqId, $login_reqId, $token, $token_user, $token_pswd;
    /**
     * Create a new class instance.
     */
    public function __construct($username, $password)
    {
        $sso = config('sso');

        $this->token_url    = $sso['token_url'];
        $this->login_url    = $sso['login_url'];

        $this->token_user    = $sso['token_user'];
        $this->token_pswd    = $sso['token_pswd'];

        $this->token_reqId  = $sso['reqId_token'];
        $this->login_reqId  = $sso['reqId_login'];

        $this->username     = $username;
        $this->password     = $password;
    }

    private function token(){
        $response = Http::withBody(json_encode(['reqId' => $this->token_reqId, 'username' => $this->token_user, 'pswd' => $this->token_pswd]))
            ->withHeader('Content-Type', 'application/json')
            ->post($this->token_url);

        $res = json_decode($response->getBody());

        return isset($res->token) ? $res->token : null;
    }

    public function getToken(){
        return $this->token = $this->token();
    }

    public function username(){
        return (config('data.login_auth_with')) ? config('data.login_auth_with') : 'username';
    }

    public function signIn(){
        $response = Http::withToken($this->token)
            ->withBody(json_encode([
                'reqId' => $this->token_reqId, $this->username() => $this->username, 'pswd' => $this->password
            ]))
            ->post($this->login_url);

        $res = json_decode($response->getBody());

        if (isset($res->success) && $res->success == true) {
            return ['status' => true, 'message' => $res->message, 'data' => (array) $res->result];
        }

        if (isset($res->fault)) {
            return ['status' => false, 'message' => $res->fault->message, 'data' => []];
        }
    }


}
