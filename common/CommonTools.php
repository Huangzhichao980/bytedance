<?php


namespace panthsoni\bytedance\common;


class CommonTools
{
    /**
     * CommonTools constructor.
     */
    public function __construct(){}

    /**
     * xml转数组
     * @param $xml
     * @return mixed
     */
    protected static function xmlToArray($xml){
        $ob= simplexml_load_string($xml,'SimpleXMLElement', LIBXML_NOCDATA);
        $json = json_encode($ob);
        $configData = json_decode($json, true);

        return $configData;
    }

    /**
     * curl请求
     * @param string $url
     * @param string $requestWay
     * @param array $params
     * @return bool|mixed
     */
    protected static function httpCurl($url='',$requestWay='GET',$params=[]){
        if (!$url) return false;

        /*curl请求*/
        $ch = curl_init();//初始化curl
        curl_setopt($ch, CURLOPT_URL,$url);//抓取指定网页
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);//设置header
        curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
        if ($requestWay == 'POST'){
            curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params,JSON_UNESCAPED_UNICODE));
        }
        $data = curl_exec($ch);//运行curl
        curl_close($ch);

        return json_decode($data,true);
    }

    /**
     * 数据验证
     * @param $data
     * @param $validate
     * @param $scene
     * @return array
     * @throws \Exception
     */
    public static function validate($data,$validate,$scene){
        /*数据接收*/
        if (!is_array($data)) throw new \Exception('参数必须为数组',-10001);

        /*验证场景验证*/
        if (!$scene) throw new \Exception('场景不能为空',-10002);

        $validate->scene($scene);

        /*数据验证*/
        if (!$validate->check($data)){
            throw new \Exception($validate->getError(),-10003);
        }

        $scene = $validate->scene[$scene];
        $_scene = [];
        foreach ($scene as $key => $val){
            if(is_numeric($key)){
                $_scene[] = $val;
            }else{
                $_scene[] = $key;
            }
        }

        $_data = [];
        foreach ($data as $key=>$val){
            if ($val === '' || $val === null) continue;

            if(is_numeric($key)){
                if(in_array($key,$_scene)) $_data[$key] = $val;
            }else{
                if(in_array($key,$_scene)) $_data[$key] = $val;
            }
        }

        return $_data;
    }
}