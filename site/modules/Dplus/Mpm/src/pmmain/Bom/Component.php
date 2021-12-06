<?php namespace Dplus\Mpm\Pmmain\Bom;
// Dplus Models
use BomComponentQuery, BomComponent;
// ProcessWire
use ProcessWire\WireData, ProcessWire\WireInput;
// Dplus CRUD
use Dplus\Mpm\Pmmain\Itm\Response;

class Component extends WireData {
	public function __construct() {
		$this->sessionID = session_id();
	}
}
