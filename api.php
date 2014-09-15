<?php

require_once(dirname(__FILE__). "/CharterData.php");

if(isset($_REQUEST['call']) && !empty($_REQUEST['call']))
{
	$call = json_decode(base64_decode($_REQUEST['call']), true);

//	print_r($call);die;
	$options = isset($call['arguments']) ? (array)$call['arguments'] : array();
	$method = $call['method'];
	
	if(method_exists('CharterData', $method) && is_callable(array('CharterData', $method)))
    {
		$key = "json_encode_CharterData::$method" . serialize($options);
		
		header('Content-Type: application/json');
		
    	$result = call_user_func_array(array('CharterData', $method), $options);
    	
    	if($call['dataType'] == 'json')
    		$result = json_encode($result);
    	else if($call['dataType'] == 'jsonp' && isset($_GET['callback']))
    		$result = sprintf('%s(%s);', $_GET['callback'], json_encode($result));
		
		echo $result;
	}
}