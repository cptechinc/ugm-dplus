<?php namespace Dplus\Mpm\Pmmain;
// ProcessWire
use ProcessWire\WireData, ProcessWire\WireInput;
// Dplus CRUD
use Dplus\Mpm\Pmmain\Bom;

class Bom extends WireData {
	public function __construct() {
		$this->sessionID  = session_id();
		$this->header     = new Bom\Header();
		$this->component  = new Bom\Component();
	}
}
