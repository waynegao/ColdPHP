<?php
// +----------------------------------------------------------------------
// | QINGDAO YINGWANG Co., Ltd.
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.xzhiliao.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: gaov <qdgaov@163.com>
// +----------------------------------------------------------------------
// | Create Date: 2014-12-15
// +----------------------------------------------------------------------

class Controller{
	
	public function run(){
		$method = isset($_GET['a'])?$_GET['a']:'index';
		if(method_exists($this, $method)){
			$this->$method();
		}else{
			Debug::addmsg("<font color='red'>对不起!你访问的方法不存在！</font>");
		}
	}
	
	public function display($data=array(),$codeType='json'){
		switch ($codeType) {
			case 'json':
				header('Content-Type:application/json; charset=utf-8');
				exit(json_encode($data));
				break;
			case 'xml':
				header('Content-Type:text/xml; charset=utf-8');
				exit($this->ToXML($data));
				break;
			case 'jsonp':
				header('Content-Type:application/json; charset=utf-8');
                $handler  =   isset($_GET[C('VAR_JSONP_HANDLER')]) ? $_GET[C('VAR_JSONP_HANDLER')] : C('DEFAULT_JSONP_HANDLER');
                exit($handler.'('.json_encode($data).');');  
				break;
			default:
				var_dump($data);
				break;
		}
	}
	
	private function ToXML($data){
		return $data;
	}
}