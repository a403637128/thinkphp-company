<?php 
namespace Admin\Controller;

use Think\Controller;
class BaseController extends Controller{

	//初始化的方法
	public function _initialize(){
		//判断session是否存在
		if(empty($_SESSION['admin_user'])){
			$this->redirect("Public/login");
		}
	}

	public function _empty($name){
		$this->assign('title','错误页面');
		$this->display('Public/error-404');
	}


    /******** 2017-10-17  **********/
    const JSON = "json";
    /**
     * 按综合方式输出通信数据
     * @param integer $code 状态码
     * @param string $message 提示信息
     * @param array $data 数据
     * @param string $type 数据类型
     * return string
     */
    public function show($code, $message = '', $data = array(), $type = self::JSON) {
        if(!is_numeric($code)) {
            return '';
        }

        $type = isset($_GET['format']) ? $_GET['format'] : self::JSON;

        $result = array(
            'code' => $code,
            'message' => $message,
            'data' => $data,
        );

        if($type == 'json') {
            self::json($code, $message, $data);
            exit;
        } elseif($type == 'array') {
            var_dump($result);
        } elseif($type == 'xml') {
            self::xmlEncode($code, $message, $data);
            exit;
        } else {
            // TODO
        }
    }
    /**
     * 按json方式输出通信数据
     * @param integer $code 状态码
     * @param string $message 提示信息
     * @param array $data 数据
     * return string
     */
    public function json($code, $message = '', $data = array()) {

        if(!is_numeric($code)) {
            return '';
        }

        $result = array(
            'code' => $code,
            'message' => $message,
            'data' => $data
        );

        echo json_encode($result);
        exit;
    }

    /**
     * 按xml方式输出通信数据
     * @param integer $code 状态码
     * @param string $message 提示信息
     * @param array $data 数据
     * return string
     */
    public function xmlEncode($code, $message, $data = array()) {
        if(!is_numeric($code)) {
            return '';
        }

        $result = array(
            'code' => $code,
            'message' => $message,
            'data' => $data,
        );

        header("Content-Type:text/xml");
        $xml = "<?xml version='1.0' encoding='UTF-8'?>\n";
        $xml .= "<root>\n";

        $xml .= self::xmlToEncode($result);

        $xml .= "</root>";
        echo $xml;
    }

    public function xmlToEncode($data) {

        $xml = $attr = "";
        foreach($data as $key => $value) {
            if(is_numeric($key)) {
                $attr = " id='{$key}'";
                $key = "item";
            }
            $xml .= "<{$key}{$attr}>";
            $xml .= is_array($value) ? self::xmlToEncode($value) : $value;
            $xml .= "</{$key}>\n";
        }
        return $xml;
    }
    /******** 2017-10-17  **********/
}
 