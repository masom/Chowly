<?php
/**
 * First, import the relevant Lithium core classes.
 */
use \lithium\core\ErrorHandler;
use \lithium\analysis\Logger;
use \lithium\template\View;

/**
 * Then, set up a basic logging configuration that will write to a file.
 */
Logger::config(array('error' => array('adapter' => 'File')));

/**
 * Configure an error page renderer function that we can use to render 404 and 500 error pages (for
 * this part to work, you need to create errors/404.html.php and errors/500.html.php in your views/
 * directory).
 */
$render = function($template, $content) {
    $view = new View(array(
        'paths' => array(
            'template' => '{:library}/views/{:controller}/{:template}.{:type}.php',
            'layout'   => '{:library}/views/layouts/{:layout}.{:type}.php',
        )
    ));
    echo $view->render('all', compact('content'), compact('template') + array(
        'controller' => 'errors',
        'layout' => 'default',
        'type' => 'html'
    ));
};

/**
 * Finally, wire up the error configuration. The first rule captures any exceptions where the
 * message matches one of the given regular expressions: eitheer a template or controller wasn't
 * found. In either case, render a 404.
 *
 * For all other exceptions, log them to the error log, and show the user a 500 error.
 */
ErrorHandler::config(array(
    array(
        'type' => 'Exception',
        'message' => "/(^Template not found|^Controller '\w+' not found)/",
        'handler' => function($info) use ($render) {
            $render('404', $info);
        }
    ),
    array(
        'type' => 'Exception',
        'handler' => function($info) use ($render) {
            Logger::write('error', "{$info['file']} : {$info['line']} : {$info['message']}");
            $render('500', $info);
        }
    )
));

/**
 * Last but not least, tell the ErrorHandler to start capturing errors.
 */
ErrorHandler::run();