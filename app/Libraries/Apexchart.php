<?php

namespace App\Libraries;

use Illuminate\Support\Facades\View;
use stdClass;

class Apexchart
{
    /*
    |--------------------------------------------------------------------------
    | Chart
    |--------------------------------------------------------------------------
    | Nyieun sorangan sugan jalan
    */

    public $id;
    protected $title;
    protected $subtitle;
    protected $subtitlePosition;
    protected $type = 'line';
    protected $labels;
    protected $fontFamily;
    protected $foreColor;
    protected $dataset;
    protected $height = 500;
    protected $width;
    protected $colors;
    protected $horizontal;
    protected $xAxis;
    protected $yAxis;
    protected $grid;
    protected $markers;
    protected $stroke;
    protected $toolbar;
    protected $zoom;
    protected $legends;
    protected $dataLabels;
    private $chartLetters = 'abcdefghijklmnopqrstuvwxyz';

    /*
    |--------------------------------------------------------------------------
    | Constructors
    |--------------------------------------------------------------------------
    */

    public function __construct()
    {
        $this->id = substr(str_shuffle(str_repeat($x = $this->chartLetters, ceil(25 / strlen($x)))), 1, 25);
        $this->horizontal = json_encode(['horizontal' => false]);
        $this->colors = json_encode(config('larapex-charts.colors'));
        $this->setXAxis([]);
        $this->grid = json_encode(['show' => false]);
        $this->markers = json_encode(['show' => false]);
        $this->toolbar = json_encode(['show' => false]);
        $this->zoom = json_encode(['enabled' => true]);
        $this->dataLabels = json_encode(['enabled' => false]);
        $this->fontFamily = json_encode(config('larapex-charts.font_family'));
        $this->foreColor = config('larapex-charts.font_color');
        return $this;
    }

    /*
    |--------------------------------------------------------------------------
    | Setters
    |--------------------------------------------------------------------------
    */

    /**
     *
     * @param null $type
     * @return $this
     */
    public function setType($type = null): Apexchart
    {
        $this->type = $type;
        return $this;
    }

    public function setFontFamily($fontFamily): Apexchart
    {
        $this->fontFamily = $fontFamily;
        return $this;
    }

    public function setFontColor($fontColor): Apexchart
    {
        $this->foreColor = $fontColor;
        return $this;
    }

    public function setDataset($dataset): Apexchart
    {
        $this->dataset = json_encode($dataset);
        return $this;
    }

    public function setHeight(int $height): Apexchart
    {
        $this->height = $height;
        return $this;
    }

    public function setWidth(int $width): Apexchart
    {
        $this->width = $width;
        return $this;
    }

    public function setColors(array $colors): Apexchart
    {
        $this->colors = json_encode($colors);
        return $this;
    }

    public function setHorizontal(bool $horizontal): Apexchart
    {
        $this->horizontal = json_encode(['horizontal' => $horizontal]);
        return $this;
    }

    public function setTitle(string $title): Apexchart
    {
        $this->title = $title;
        return $this;
    }

    public function setSubtitle(string $subtitle, string $position = 'left'): Apexchart
    {
        $this->subtitle = $subtitle;
        $this->subtitlePosition = $position;
        return $this;
    }

    public function setLabels(array $labels): Apexchart
    {
        $this->labels = $labels;
        return $this;
    }

    public function setLegends(array $legends): Apexchart
    {
        $this->legends = json_encode($legends);
        return $this;
    }

    public function setXAxis(array $categories): Apexchart
    {
        $this->xAxis = json_encode($categories);
        return $this;
    }

    public function setYAxis(array $yaxisattrs): Apexchart
    {
        $foo = $yaxisattrs;
        $value_arr = array();
        $replace_keys = array();
        
        if (count($foo)) {
            foreach ($foo as $key => $value) {

                // Look for values starting with 'function('
                if (is_array($value)) {
                    foreach($value as $ckey => $cvalue){
                        if (strpos($cvalue, 'function(') === 0) {
                            // Store function string.
                            $value_arr[] = $cvalue;
                            // Replace function string in $foo with a ‘unique’ special key.
                            $cvalue = '%' . $ckey . '%';
                            // Later on, we’ll look for the value, and replace it.
                            $replace_keys[] = '"' . $cvalue . '"';
                            $foo[$key][$ckey] = $cvalue;
                        }
                    }
                }else{
                    if (strpos($value, 'function(') === 0) {
                        // Store function string.
                        $value_arr[] = $value;
                        // Replace function string in $foo with a ‘unique’ special key.
                        $value = '%' . $key . '%';
                        // Later on, we’ll look for the value, and replace it.
                        $replace_keys[] = '"' . $value . '"';
                    }
                    $foo[$key] = $value;
                }
            }
        }

        $json = json_encode($foo);
        $json = str_replace($replace_keys, $value_arr, $json);

        $this->yAxis = $json;
        // Now encode the array to json format


        return $this;
    }

    public function setGrid($transparent = true, $color = '#e5e5e5', $opacity = 0.1): Apexchart
    {
        if ($transparent) {
            $this->grid = json_encode(['show' => true]);
            return $this;
        }

        $this->grid = json_encode([
            'row' => [
                'colors' => [$color, 'transparent'],
                'opacity' => $opacity ? $opacity : 0.5
            ],
        ]);

        return $this;
    }

    public function setMarkers($colors = [], $width = 4, $hoverSize = 7): Apexchart
    {
        if (empty($colors)) {
            $colors = config('larapex-charts.colors');
        }

        $this->markers = json_encode([
            'size' => $width,
            'colors' => $colors,
            'strokeColors' => "#fff",
            'strokeWidth' => $width / 2,
            'hover' => [
                'size' => $hoverSize,
            ]
        ]);

        return $this;
    }

    public function setStroke(array $attrs = []): Apexchart
    {
        if (!$attrs) {
            $attrs = [
                'show'    =>  true,
                'curve'   =>  'smooth'
            ];
        }
        $this->stroke = json_encode($attrs);
        return $this;
    }

    public function setToolbar(bool $show, bool $zoom = true): Apexchart
    {
        $this->toolbar = json_encode(['show' => $show]);
        $this->zoom = json_encode(['enabled' => $zoom ? $zoom : false]);
        return $this;
    }

    public function setDataLabels(bool $enabled = true): Apexchart
    {
        $this->dataLabels = json_encode(['enabled' => $enabled]);
        return $this;
    }

    /*
    |--------------------------------------------------------------------------
    | Getters
    |--------------------------------------------------------------------------
    */

    /**
     * @param array $array
     * @return array|false|string
     */
    public function transformLabels(array $array)
    {
        $stringArray = array_filter($array, function ($string) {
            return "{$string}";
        });
        return json_encode(['"' . implode('","', $stringArray) . '"']);
    }

    /**
     * @return mixed
     */
    public function script()
    {
        return View::make('chart.script', ['chart' => $this]);
    }

    /**
     * @return false|string
     */
    public function id($id = '')
    {
        $this->id = !empty($id) ? $id : $this->id;
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function title()
    {
        return $this->title;
    }

    /**
     * @return mixed
     */
    public function subtitle()
    {
        return $this->subtitle;
    }

    /**
     * @return mixed
     */
    public function subtitlePosition()
    {
        return $this->subtitlePosition;
    }

    /**
     * @return string
     */
    public function type()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function fontFamily()
    {
        return $this->fontFamily;
    }

    /**
     * @return string
     */
    public function foreColor()
    {
        return $this->foreColor;
    }

    /**
     * @return mixed
     */
    public function labels()
    {
        return $this->labels;
    }

    /**
     * @return mixed
     */
    public function legends()
    {
        return $this->legends;
    }

    /**
     * @return mixed
     */
    public function dataset()
    {
        return $this->dataset;
    }

    /**
     * @return int
     */
    public function height(): int
    {
        return $this->height;
    }

    public function width(): string
    {
        return $this->width ? $this->width : '100%';
    }

    /**
     * @return false|string
     */
    public function colors()
    {
        return $this->colors;
    }

    /**
     * @return false|string
     */
    public function horizontal()
    {
        return $this->horizontal;
    }

    /**
     * @return mixed
     */
    public function xAxis()
    {
        return $this->xAxis;
    }

    /**
     * @return mixed
     */
    public function yAxis()
    {
        return $this->yAxis;
    }

    /**
     * @return false|string
     */
    public function grid()
    {
        return $this->grid;
    }

    /**
     * @return false|string
     */
    public function markers()
    {
        return $this->markers;
    }

    /**
     * @return mixed
     */
    public function stroke()
    {
        return $this->stroke;
    }

    /**
     * @return true|boolean
     */
    public function toolbar()
    {
        return $this->toolbar;
    }

    /**
     * @return true|boolean
     */
    public function zoom()
    {
        return $this->zoom;
    }

    /**
     * @return true|boolean
     */
    public function dataLabels()
    {
        return $this->dataLabels;
    }

    /*
    |--------------------------------------------------------------------------
    | JSON Helper
    |--------------------------------------------------------------------------
    */

    public function toJson()
    {
        $options = [
            'chart' => [
                'type' => $this->type(),
                'height' => $this->height(),
                'width' => $this->width(),
                'toolbar' => json_decode($this->toolbar()),
                'zoom' => json_decode($this->zoom()),
                'fontFamily' => json_decode($this->fontFamily()),
                'foreColor' => $this->foreColor(),
            ],
            'plotOptions' => [
                'bar' => json_decode($this->horizontal()),
            ],
            'colors' => json_decode($this->colors()),
            'series' => json_decode($this->dataset()),
            'dataLabels' => json_decode($this->dataLabels()),
            'title' => [
                'text' => $this->title()
            ],
            'subtitle' => [
                'text' => $this->subtitle() ? $this->subtitle() : '',
                'align' => $this->subtitlePosition() ? $this->subtitlePosition() : '',
            ],
            'xaxis' => [
                'categories' => json_decode($this->xAxis()),
            ],
            'grid' => json_decode($this->grid()),
            'markers' => json_decode($this->markers()),
        ];

        if ($this->labels()) {
            $options['labels'] = $this->labels();
        }

        if ($this->stroke()) {
            $options['stroke'] = json_decode($this->stroke());
        }

        return response()->json([
            'id' => $this->id(),
            'options' => $options,
        ]);
    }
}
