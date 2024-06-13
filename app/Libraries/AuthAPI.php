<?php

namespace App\Libraries;

use GuzzleHttp\Client;
use Http;

class AuthAPI
{
    protected string $token, $username_authorization, $password_authorization, $token_url, $login_url, $grant_type, $bagian_url, $kantor_url, $jabatan_url, $x_pos_key, $bagian_kantor_url;

    public function __construct()
    {
        $data = config('api');

        $this->username_authorization = $data["username"];
        $this->password_authorization = $data["password"];
        $this->token_url = $data["token_url"];
        $this->login_url = $data["login_url"];
        $this->grant_type = $data["grant_type"];
        $this->kantor_url = $data["kantor_url"];
        $this->bagian_url = $data["bagian_url"];
        $this->jabatan_url = $data["jabatan_url"];
        $this->x_pos_key = $data["X-POS-Key"];
        $this->bagian_kantor_url = $data["bagian_kantor_url"];

        $this->getToken();
    }

    public function token()
    {
        $response = Http::withBasicAuth($this->username_authorization, $this->password_authorization)
            ->withHeader('Content-Type', 'application/x-www-form-urlencoded')
            ->asForm()
            ->post($this->token_url, ['grant_type' => $this->grant_type]);

        $res = json_decode($response->getBody());

        return isset($res->access_token) ? $res->access_token : null;
    }

    public function getToken()
    {
        $this->token = $this->token();
    }

    public function login($username, $password)
    {
        $response = Http::withToken($this->token)
            ->withHeader('X-POS-Auth', base64_encode($username . ':' . $password))
            ->post($this->login_url);

        $res = json_decode($response->getBody());

        if (isset($res->success) && $res->success == true) {
            return ['status' => true, 'message' => $res->message, 'data' => (array) $res->data[0]];
        }

        if (isset($res->fault)) {
            return ['status' => false, 'message' => $res->fault->message, 'data' => []];
        }
    }

    public function getKantor()
    {
        $response = Http::withToken($this->token)
            ->withHeader('X-POS-Key', $this->x_pos_key)
            ->get($this->kantor_url);

        $res = json_decode($response->getBody());

        $results = collect();
        return $results->push(isset($res->data) ? $res->data : [])[0];
    }

    public function getBagian()
    {
        $response = Http::withToken($this->token)
            ->withHeader('X-POS-Key', $this->x_pos_key)
            ->get($this->bagian_url);

        $res = json_decode($response->getBody());

        $results = collect();
        return $results->push(isset($res->data) ? $res->data : [])[0];
    }
    public function getBagianKantor($kodekantor = '')
    {
        $response = Http::withToken($this->token)
            ->withHeader('X-POS-Key', $this->x_pos_key)
            ->post($this->bagian_kantor_url,['nopend' => $kodekantor]);

        $res = json_decode($response->getBody());

        $results = collect();
        return $results->push(isset($res->data) ? $res->data : [])[0];
    }

    public function getJabatan()
    {
        $response = Http::withToken($this->token)
            ->withHeader('X-POS-Key', $this->x_pos_key)
            ->get($this->jabatan_url);

        $res = json_decode($response->getBody());
        $results = collect();
        return $results->push(isset($res->data) ? $res->data : [])[0];
    }

    public function dropdownJabatan()
    {
        $jabatan = $this->getJabatan();

        $options = collect();
        foreach ($jabatan as $key => $value) {
            $options->put($value->Jabatan, $value->DescJabatan);
        }

        return $options;
    }

    public function dropdownBagian()
    {
        $bagian = $this->getBagian();

        $options = collect();
        foreach ($bagian as $key => $value) {
            $options->put($value->kdbagchild, $value->namabagian);
        }

        return $options;
    }

    public function dropdownDirektorat()
    {
        $direktorat = $this->getBagian()->filter(function ($value, $key) {
            return $value->scope == 'PUSAT';
        });

        $options = collect();
        foreach ($direktorat as $key => $value) {
            $options->put($value->kdbagchild, $value->namabagian);
        }

        return $options;
    }
}