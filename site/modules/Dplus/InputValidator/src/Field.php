<?php namespace Dplus\RecordValidator;

use ProcessWire\WireData;

/**
 * @property string $name        Record Field Name
 * @property string $input       Input Name NOTE: Can be left blank, uses $name
 * @property string $description Description of Field / Input
 * @property string $validator   Validator Code (FORMAT: ValidatorClass::function e.g. Min::itemid | Min\Itm::hazmat_packgroup
 * @property string $sanitizer   Sanitizer function to use for Input Sanitization
 * @property int    $length      Max Length of field property 0 = no max length
 * @property string $allowblank  Allow Value to be blank?
 * @property array  $requires    Array of input names ( NOT BLANK ) this requires
 */
class Field extends WireData {
	const EXAMPLES = [
		'packgroup' => [
			'input'       => 'packgroup', //
			'function'    => 'hazmat_packgroup', // IF ABSENT / EMPTY WILL DEFAULT TO KEY'S VALUE
			'validator'   => 'Min\Itm::hazmat_packgroup'
			'description' => 'Packing Group', //
			'allowblank'  => true,
			'requires'    => ['number'],
			'rules'       => [
				
			]
		],
	];
}
