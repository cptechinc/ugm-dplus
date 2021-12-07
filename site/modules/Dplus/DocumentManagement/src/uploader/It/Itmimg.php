<?php namespace Dplus\DocManagement\Uploader\It;

use ProcessWire\WireData, ProcessWire\WireInput, ProcessWire\WireUpload;

use Dplus\DocManagement\Uploader as Base;
use Dplus\DocManagement\Config;

/**
 * Itmimg
 * Class for uploading Images and tying them to lots
 *
 * @property string $itemID       Item ID
 */
class Itmimg extends Base {
	const FILENAME_PREFIX = 'ITMIMG_';
	const FIELDS = [
		'image'      => ['type' => 'file', 'extensions' => ['jpeg', 'jpg', 'gif', 'png']],
		'itemID'     => ['type' => 'text', 'uppercase' => true],
	];

	private static $instance;

	public function __construct() {
		parent::__construct();
		$this->itemID = '';
	}

	public static function getInstance() {
		if (empty(self::$instance)) {
			$instance = new self();
			self::$instance = $instance;
		}
		return self::$instance;
	}

	/**
	 * Set ItemID
	 * @param string $itemID  Item ID
	 */
	public function setItemID($itemID) {
		$this->itemID = strtoupper($this->wire('sanitizer')->text($itemID));
	}

	/**
	 * Return File Name Prefix
	 * @return string
	 */
	public function getFilenamePrefix() {
		$config = Config::getInstance();
		return $config->folder->useLowercase() ? strtolower(self::FILENAME_PREFIX) : self::FILENAME_PREFIX;
	}

/* =============================================================
	FILE Uploading
============================================================= */
	/**
	 * Return File Uploader with settings
	 * @param  array  $file          Element from $_FILES
	 * @return WireUpload
	 */
	protected function getUploader(array $file) {
		$ext = pathinfo($file['name'], PATHINFO_EXTENSION);

		$uploader = parent::getUploader($file);
		$uploader->setTargetFilename($this->getFilenamePrefix(). $this->itemID . ".$ext");
		$uploader->setLowercase(false);
		return $uploader;
	}
}
