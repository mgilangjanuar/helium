<?php 
namespace system;

class Assets {

    public $cssFile = [];
    public $jsFile = [];
    public $css = [];
    public $js = [];

    public function __construct($datas)
    {
        $this->cssFile = isset($datas['css']) ? $datas['css'] : [];
        $this->jsFile = isset($datas['js']) ? $datas['js'] : [];
    }

    public function getCss()
    {
        $results = [];
        foreach ($this->cssFile as $css) {
            $results[] = '<link rel="stylesheet" href="' . App::$url->path() . '/' . $css . '">';
        }
        foreach ($this->css as $css) {
            $results[] = '<style>' . $css . '</style>';
        }
        return $results;
    }

    public function getJs()
    {
        $results = [];
        foreach ($this->jsFile as $js) {
            $results[] = '<script src="' . App::$url->path() . '/' . $js . '"></script>';
        }
        foreach ($this->js as $js) {
            $results[] = '<script>' . $jsCode . '</script>';
        }
        return $results;
    }
}