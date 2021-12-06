<?php namespace Dplus\Mpm\Pmmain\Bom;
// Dplus Models
use BomItemQuery, BomItem;
// ProcessWire
use ProcessWire\WireData, ProcessWire\WireInput;
// Dplus CRUD


class Header extends WireData {
	const MODEL              = 'BomItem';
	const MODEL_KEY          = 'itemid';
	const DESCRIPTION        = 'BoM Header';
	const DESCRIPTION_RECORD = 'BoM Header';
	const RESPONSE_TEMPLATE  = 'BoM {itemid} {not} {crud}';
	const RECORDLOCKER_FUNCTION = 'bom-header';

	public function __construct() {
		$this->sessionID = session_id();
	}

	public function query() {
		return BomItemQuery::create();
	}

	public function queryHeader($itemID, $level = 1) {
		$q = $this->query();
		$q->filterByItemid($itemID);
		$q->filterByLevel($level);
		return $q;
	}

	public function header($itemID, $level = 1) {
		$q = $this->queryHeader($itemID, $level);
		return $q->findOne();
	}
}
