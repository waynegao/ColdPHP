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

	header("Content-Type:text/html;charset=utf-8");  //设置系统的输出字符为utf-8
	date_default_timezone_set("PRC");    		     //设置时区（中国）
	
	//框架程序所有需要的路径，都使用相对路径
	define("COLDPHP_PATH", rtrim(COLDPHP, '/').'/');     //COLDPHP框架的路径
	define("APP_PATH", rtrim(APP,'/').'/');              //用户项目的应用路径
	define("PROJECT_PATH", dirname(COLDPHP_PATH).'/');   //项目的根路径，也就是框架所在的目录
	
	//设置Debug模式
	if(defined("DEBUG") && DEBUG==1){
		$GLOBALS["debug"]=1;                                     //初例化开启debug
		error_reporting(E_ALL ^ E_NOTICE);                       //输出除了注意的所有错误报告
		include COLDPHP_PATH."library/debug.php";                //包含debug类
		Debug::start();                                          //开启脚本计算时间
		set_error_handler(array("Debug", 'Catcher'));            //设置捕获系统异常
	}else{
		ini_set('display_errors', 'Off'); 		                 //屏蔽错误输出
		ini_set('log_errors', 'On');             	             //开启错误日志，将错误报告写入到日志中
		ini_set('error_log', PROJECT_PATH.'runtime/error_log');  //指定错误日志文件
	
	}
	
	//包含系统默认配置文件
	include COLDPHP_PATH.'config/config.php';
	//包含项目配置文件
	$configFile = PROJECT_PATH.'config/config.php';
	if(file_exists($configFile)){
		include $configFile;
	}
	$GLOBALS['config'] = $config;
	//包含框架中的函数库文件
	include COLDPHP_PATH.'common/functions.php';
	
	//包含用户自定义的函数库文件
	$userFunc = PROJECT_PATH.'common/functions.php';
	if(file_exists($userFunc)){
		include $userFunc;
	}
	
	//设置包含目录（类所在的全部目录）,  PATH_SEPARATOR 分隔符号 Linux(:) Windows(;)
	$include_path=get_include_path();                         //原基目录
	$include_path.=PATH_SEPARATOR.COLDPHP_PATH."library/";    //框架中扩展类的目录
	$include_path.=PATH_SEPARATOR.PROJECT_PATH."library/";    //项目中用的到的工具类
	$include_path.=PATH_SEPARATOR.APP_PATH."controllers/";    //项目的控制器类
	$include_path.=PATH_SEPARATOR.COLDPHP_PATH."config/";     //框架中的配置目录
	
	//设置include包含文件所在的所有目录	
	set_include_path($include_path);
	
	Routes::parseUrl();    //解析处理URL 
	
	//控制器类的路径
	$controlerClassPath = isset($_GET['m'])?APP_PATH."controllers/".strtolower($_GET["m"]).".php":APP_PATH."controllers/".strtolower($config['DEFAULT_CONTROLLER']).".php";
	Debug::addmsg("当前访问的控制器类在项目应用目录下的: <b>$controlerClassPath</b> 文件！");
	//控制器类的创建
	if(file_exists($controlerClassPath)){
		//Structure::commoncontroler(APP_PATH."controllers/",$controlerpath);
		//Structure::controler($srccontrolerfile, $controlerpath, $_GET["m"]);
		$className=ucfirst(isset($_GET['M'])?$_GET['M']:$config['DEFAULT_CONTROLLER']);
		
		$controler=new $className();
		$controler->run();
	
	}else{
		Debug::addmsg("<font color='red'>对不起!你访问的模块不存在,应该在".APP_PATH."controls目录下创建文件名为".strtolower($_GET["m"]).".php的文件，声明一个类名为".ucfirst($_GET["m"])."的类！</font>");
		
	}
	
	function __autoload($className){
		include strtolower($className).'.php';
		Debug::addmsg("<b> $className </b>类", 1);
	}
	
	//设置输出Debug模式的信息
	if(defined("DEBUG") && DEBUG==1 && $GLOBALS["debug"]==1){
		Debug::stop();
		Debug::message();
	}
