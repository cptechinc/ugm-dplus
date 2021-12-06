<?php namespace Dplus\Configs;
// Propel ORM Library
use Propel\Runtime\ActiveQuery\ModelCriteria as Query;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface as Model;
// ProcessWire
use ProcessWire\ProcessWire;

abstract class AbstractConfig {
	const MODEL = '';

	private static $pw;

	/** @var Model */
	protected static $config;

	/**
	 * Return New Query Class
	 * @return Query
	 */
	public static function query() {
		$class = static::queryClassName();
		return $class::create();
	}

	/**
	 * Return Config from Database
	 * @return Model
	 */
	public static function getConfig() {
		return static::query()->findOne();
	}

	/**
	 * Return Config from Memory
	 * @return Model
	 */
	public static function config() {
		return static::getConfig();
	}

	/**
	 * Return Query Class Name
	 * @return string
	 */
	public static function queryClassName() {
		return static::MODEL.'Query';
	}

	/**
	 * Return the current ProcessWire Wire Instance
	 * @param  string            $var   Wire Object
	 * @return ProcessWire|mixed
	 */
	public static function pw($var = '') {
		if (empty(self::$pw)) {
			self::$pw = ProcessWire::getCurrentInstance();
		}
		return $var ? self::$pw->wire($var) : self::$pw;
	}
}
