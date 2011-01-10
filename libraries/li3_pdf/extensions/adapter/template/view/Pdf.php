<?php
namespace li3_pdf\extensions\adapter\template\view;

use \lithium\util\String;
use \lithium\core\Libraries;
use \lithium\template\TemplateException;
use li3_pdf\extensions\PdfWrapper;


/**
 * 5:52 < nateabele> masom: You *could* have the layout be a file that returns an 
                   array containing two closures.
15:52 -!- asper [~asper@2a01:e35:2ee2:9460:6ef0:49ff:fe5e:dc44] has quit [Quit: 
          http://www.asper.fr]
15:52 -!- asper [~asper@car75-6-82-238-41-70.fbx.proxad.net] has joined #li3
15:53 < nateabele> Then you'd just get the name of the layout from a 
                   File->template() lookup, say $layout = include $layoutFile, 
                   and then call $layout['header']() and $layout['footer']() 
                   from your custom class.
15:54 < nateabele> Then you won't have to have each person write their own 
                   custom class.
15:54 < masom> yeah that could work
15:55 < masom> i'll see what gives, publish it and look for feedback
15:56 < masom> thanks
 * Enter description here ...
 * @author msamson
 *
 */

class Pdf extends \lithium\template\view\Renderer implements \ArrayAccess {

	/**
	 * These configuration variables will automatically be assigned to their corresponding protected
	 * properties when the object is initialized.
	 *
	 * @var array
	 */
	protected $_autoConfig = array(
		'classes' => 'merge', 'request', 'context', 'strings', 'handlers', 'view', 'compile'
	);

	/**
	 * An array containing the variables currently in the scope of the template. These values are
	 * manipulable using array syntax against the template object, i.e. `$this['foo'] = 'bar'`
	 * inside your template files.
	 *
	 * @var array
	 */
	protected $_data = array();

	/**
	 * Variables that have been set from a view/element/layout/etc. that should be available to the
	 * same rendering context.
	 *
	 * @var array Key/value pairs of variables
	 */
	protected $_vars = array();
	
	/**
	 * `Pdf`'s dependencies. These classes are used by the output handlers to generate URLs
	 * for dynamic resources and static assets, as well as compiling the templates.
	 *
	 * @see Renderer::$_handlers
	 * @var array
	 */
	protected $_classes = array(
		'router' => 'lithium\net\http\Router',
		'media'  => 'lithium\net\http\Media',
		'pdf' => 'li3_pdf\extensions\template\PdfWrapper'
	);

	protected $Pdf = null;
	
	public function __construct(array $config = array()) {
		$defaults = array('classes' => array(), 'compile' => false, 'extract' => true);
		
		$this->Pdf = new PdfWrapper();
		parent::__construct($config + $defaults);
	}

	/**
	 * Renders content from a template file provided by `template()`.
	 *
	 * @param string $template
	 * @param string $data
	 * @param array $options
	 * @return string
	 */
	public function render($template, $data = array(), array $options = array()) {
		$defaults = array('context' => array());
		$options += $defaults;

		$this->_context = $options['context'] + $this->_context;
		$this->_data = (array) $data + $this->_vars;
		$template__ = $template;
		unset($options, $template, $defaults, $data);

		if ($this->_config['extract']) {
			extract($this->_data, EXTR_OVERWRITE);
		} elseif ($this->_view) {
			extract((array) $this->_view->outputFilters, EXTR_OVERWRITE);
		}
		ob_start();
		include $template__;
		return ob_get_clean();
	}

	/**
	 * Returns a template file name
	 *
	 * @param string $type
	 * @param array $options
	 * @return string
	 */
	public function template($type, $options) {
		if (!isset($this->_config['paths'][$type])) {
			return null;
		}
		$options = array_filter($options, function($item) { return is_string($item); });

		$library = Libraries::get(isset($options['library']) ? $options['library'] : true);
		$options['library'] = $library['path'];
		$path = $this->_paths((array) $this->_config['paths'][$type], $options);
		return $path;
	}

	/**
	 * Allows checking to see if a value is set in template data, i.e. `$this['foo']` in templates.
	 *
	 * @param string $offset The key / variable name to check.
	 * @return boolean Returns `true` if the value is set, otherwise `false`.
	 */
	public function offsetExists($offset) {
		return array_key_exists($offset, $this->_data);
	}

	public function offsetGet($offset) {
		return isset($this->_data[$offset]) ? $this->_data[$offset] : null;
	}

	public function offsetSet($offset, $value) {
		$this->_data[$offset] = $value;
	}

	public function offsetUnset($offset) {
		unset($this->_data[$offset]);
	}

	/**
	 * Searches a series of path templates for a matching template file, and returns the file name.
	 *
	 * @param array $paths The array of path templates to search.
	 * @param array $options The set of options keys to be interpolated into the path templates
	 *              when searching for the correct file to load.
	 * @return string Returns the first template file found. Throws an exception if no templates
	 *         are available.
	 */
	protected function _paths($paths, $options) {
		foreach ($paths as $path) {
			if (file_exists($path = String::insert($path, $options))) {
				return $path;
			}
		}
		throw new TemplateException("Template not found at {$path}");
	}
}

?>