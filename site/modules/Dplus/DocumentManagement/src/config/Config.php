<?php namespace Dplus\DocManagement\Config;
// Purl URI manipulation library
use Purl\Url as Purl;
// ProcessWire
use ProcessWire\WireData;
use Processwire\Config as PwConfig;


/**
 * Config Abstract
 */
abstract class Config extends PwConfig {
	protected static $instance;

	public static function getInstance($json = []) {
		if (empty(static::$instance)) {
			$instance = new static();
			$instance->init($json);
			static::$instance = $instance;
		}
		return static::$instance;
	}

	public function init($json = []) {
		$this->initConfig($json);
	}

	abstract public function initConfig($json = []);
}
