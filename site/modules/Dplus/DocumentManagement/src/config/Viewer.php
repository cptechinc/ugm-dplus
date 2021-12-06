<?php namespace Dplus\DocManagement\Config;
// Purl URI manipulation library
use Purl\Url as Purl;
// ProcessWire
use ProcessWire\WireData;
// Dplus Document Management
use Dplus\DocManagement\Viewer\Config;


/**
 * Document Viewer Config
 */
class Viewer extends Config {
	protected static $instance;

	public static function getInstance($json = []) {
		if (empty(static::$instance)) {
			$instance = new static();
			$instance->init($json);
			static::$instance = $instance;
		}
		return static::$instance;
	}

	public function __construct() {
		$this->directory = '';
		$this->urlpath   = '';
		$this->url       = '';
	}

	public function initConfig($json = []) {
		$config = $json['viewer'];
		$this->setArray($config);
	}
}
