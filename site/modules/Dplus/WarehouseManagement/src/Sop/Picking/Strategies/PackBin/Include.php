<?php namespace Dplus\Wm\Sop\Picking\Strategies\PackBin;

use Dplus\Wm\Sop\Picking\Strategies\PackBin\PackBin;

/**
 * Include
 */
class Included extends PackBin {
	const INCLUDED = false;

	/**
	 * Return if Pack Bin should be included
	 * @return bool
	 */
	public function includePackBin(){
		return self::INCLUDED;
	}
}
