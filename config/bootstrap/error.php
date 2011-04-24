<?php
/**
 * First, import the relevant Lithium core classes.
 */
use \lithium\core\ErrorHandler;
use \lithium\analysis\Logger;
use lithium\action\Response;
use lithium\net\http\Media;

/**
 * Then, set up a basic logging configuration that will write to a file.
 */
Logger::config(array(
	'default' => array('adapter' => 'File'),
	'transactions' => array('adapter' => 'File',
		'file' => function($data, $config) { return "transactions.log"; }
	)
));


ErrorHandler::apply('lithium\action\Dispatcher::run', array(), function($info, $params) {
	$response = new Response(array('request' => $params['request']));
	
	$message = "/(^Template not found|^Controller '\w+' not found|^Action '\w+' not found)/";
	$template = (preg_match($message, $info['message'])) ? '404' : '500';
	
	Logger::write('error', "{$info['file']} : {$info['line']} : {$info['message']}");
	switch($template){
		case '500':
			debug($info);die;
		break;
	}
	Media::render($response, compact('info', 'params'), array(
		'controller' => 'errors',
		'template' => $template,
		'layout' => 'default',
		'request' => $params['request']
	));
	return $response;
});

?>