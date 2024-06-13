<?php
namespace App\Libraries;

class Echart{
    public $add_function = array();
    
    private function _config($config){
        $graph = array();
        if(isset($config->title)){
            $graph['title'] = $config->title;
        }
        if(isset($config->legend)){
            $graph['legend'] = $config->legend;
        }
        if(isset($config->grid)){
            $graph['grid'] = $config->grid;
        }
        if(isset($config->xAxis)){
            $graph['xAxis'] = $config->xAxis;
        }
        if(isset($config->yAxis)){
            $graph['yAxis'] = $config->yAxis;
        }
        if(isset($config->series)){
            $graph['series'] = $config->series;
        }
        if(isset($config->tooltip)){
            $graph['tooltip'] = $config->tooltip;
        }
    }

    public function render($temp) {
        return $this->_config($temp);
    }

    private function encode_js($data) {
        header("Content-Type: application/javascript");
        $result = json_encode($data);
        
        if (count($this->add_function) > 0 && is_array($this->add_function)) {
            foreach ($this->add_function as $key => $value) {
                $result = str_replace($key, $value, $result);
            }
        }

        $obj = "while(1){function xhr_result(w,s,p){return {$result}}; break;}";
        return $obj;
    }
}