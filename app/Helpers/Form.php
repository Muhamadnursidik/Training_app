<?php
namespace App\Helpers;

use Illuminate\Support\Arr;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
class Form
{
    protected $spoofedMethods = ['DELETE', 'PATCH', 'PUT'];
    protected $reserved = ['method', 'url', 'route', 'action', 'files'];
    protected $url;
    protected $html;

    public function __construct()
    {
        $this->url = url();
    }

    public static function open(array $options)
    {
        $attributes['method'] = isset($options['method']) ? $options['method'] : 'GET';
        $attributes['action'] = (new self)->getAction($options);
        $attributes['accept-charset'] = 'UTF-8';

        $attr = Arr::except($options, (new self)->reserved);

        return html()->form($attributes['method'], $attributes['action'])->attributes($attr)->open();
    }

    public static function close()
    {
        return html()->form()->close();
    }

    public static function select($name,$options, $value, array $attributes){
        if (isset($attributes['multiple'])) {
            return html()->multiselect($name, $options, $value)->attributes($attributes);
        } else {
            return html()->select($name, $options, $value)->attributes($attributes);
        }
    }

    public static function text($name, $value, array $attributes){
        return html()->text($name, $value)->attributes($attributes);
    }
    
    public static function textarea($name, $value, array $attributes){
        return html()->textarea($name, $value)->attributes($attributes);
    }
    
    public static function hidden($name, $value, $attributes = null){
        
        if(is_array($attributes) && count($attributes)){
            return html()->hidden($name, $value)->attributes($attributes);
        }

        return html()->hidden($name, $value);
    }

    public static function number($name, $value, array $attributes, $min = null, $max = null, $step = null){
        return html()->number($name, $value, $min, $max, $step)->attributes($attributes);
    }

    protected function getAction(array $options)
    {

        if (isset($options['url'])) {
            return $this->getUrlAction($options['url']);
        }

        if (isset($options['route'])) {
            return $this->getRouteAction($options['route']);
        }

        elseif (isset($options['action'])) {
            return $this->getControllerAction($options['action']);
        }

        return $this->url->current();
    }

    protected function getUrlAction($options)
    {
        if (is_array($options)) {
            return $this->url->to($options[0], array_slice($options, 1));
        }

        return $this->url->to($options);
    }

    protected function getRouteAction($options)
    {
        if (is_array($options)) {
            $parameters = array_slice($options, 1);

            if (array_keys($options) === [0, 1]) {
                $parameters = head($parameters);
            }

            return $this->url->route($options[0], $parameters);
        }

        return $this->url->route($options);
    }

    protected function getControllerAction($options)
    {
        if (is_array($options)) {
            return $this->url->action($options[0], array_slice($options, 1));
        }

        return $this->url->action($options);
    }
}