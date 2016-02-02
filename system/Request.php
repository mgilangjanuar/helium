<?php 
namespace system;

class Request 
{

    public function post($data = null)
    {
        if ($data) {
            $datas = explode('[', str_replace(']', '', $data));
            if (!isset($_POST[$datas[0]])) return null;
            $result = $_POST[$datas[0]];
            foreach ($datas as $i => $data) {
                if ($i > 0) 
                    $result = @$result[$data];
            }
            return $result;
        }
        return $_POST;
    }

    public function get($data = null)
    {
        if ($data) {
            $datas = explode('[', str_replace(']', '', $data));
            if (!isset($_GET[$datas[0]])) return null;
            $result = $_GET[$datas[0]];
            foreach ($datas as $i => $data) {
                if ($i > 0)
                    $result = @$result[$data];
            }
            return $result;
        }
        return $_GET;
    }

}