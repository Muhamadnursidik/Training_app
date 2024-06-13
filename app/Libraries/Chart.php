<?php
namespace App\Libraries;

class Chart
{
    public $add_function = array();
    public $chart, $title, $plotOptions, $series, $pane, $xAxis, $yAxis, $tooltip, $legend, $colors, $drilldown, $exporting, $subtitle, $colorAxis;

    private function config($grap) {
        $temp = array();

        if (isset($this->chart))
            $temp['chart'] = $this->chart;
        if (isset($this->title))
            $temp['title'] = $this->title;
        if (isset($this->pane))
            $temp['pane'] = $this->pane;
        if (isset($this->subtitle))
            $temp['subtitle'] = $this->subtitle;
        if (isset($this->xAxis))
            $temp['xAxis'] = $this->xAxis;
        if (isset($this->yAxis))
            $temp['yAxis'] = $this->yAxis;
        if (isset($this->series))
            $temp['series'] = $this->series;
        if (isset($this->tooltip))
            $temp['tooltip'] = $this->tooltip;
        if (isset($this->plotOptions))
            $temp['plotOptions'] = $this->plotOptions;
        if (isset($this->legend))
            $temp['legend'] = $this->legend;
        if (isset($this->colors))
            $temp['colors'] = $this->colors;
        if (isset($this->colorAxis))
            $temp['colorAxis'] = $this->colorAxis;
        if (isset($this->drilldown))
            $temp['drilldown'] = $this->drilldown;
        if (isset($this->exporting))
            $temp['exporting'] = $this->exporting;
        // add function
        if (isset($this->add_function))
            $this->add_function = $this->add_function;

        $temp['accessibility']['enabled'] = false;
        $temp['credits']['enabled'] = false;

        return $this->encode_js($temp);
    }

    public function render($temp) {
        return $this->config($temp);
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
