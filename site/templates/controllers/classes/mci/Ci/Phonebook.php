<?php namespace Controllers\Mci\Ci;
// Propel ORM Ljbrary
use Propel\Runtime\Util\PropelModelPager;
// Dplus Model
use CustomerQuery, Customer;
use CustomerShiptoQuery, CustomerShipto;
use PhoneBookQuery, PhoneBook as PbModel;
// Dpluso Model
use CustindexQuery, Custindex;
// ProcessWire Classes, Modules
use ProcessWire\Page;
// Dplus Validators
use Dplus\CodeValidators\Mar as MarValidator;
// Dplus Filters
use Dplus\Filters;
// Mvc Controllers
use Mvc\Controllers\AbstractController;

class Phonebook extends Base {

/* =============================================================
	Indexes
============================================================= */
	public static function index($data) {
		$fields = ['custID|text', 'shiptoID|text', 'q|text'];
		self::sanitizeParametersShort($data, $fields);

		if (self::validateCustidPermission($data) === false) {
			return self::displayInvalidCustomerOrPermissions($data);
		}

		return self::list($data);
	}

	private static function list($data) {
		$filter = new Dplus\Filters\Misc\Phonebook;
		if (empty($data->shiptoID)) {
			$filter->custid($data->custID);
		}
		if (empty($data->shiptoID) === false) {
			$filter->custidShiptoid($data->custID, $data->shiptoID);
		}
		$filter->sortby(self::pw('page'));
		$contacts = $filter->query->paginate(self::pw('input')->pageNum, 0);
		$customer = self::getCustomer($data->custID);
		self::pw('page')->headline = "CI: $customer->name PhoneBook";
		return self::displayList($data, $customer, $shiptos);
	}

/* =============================================================
	Displays
============================================================= */
	private static function displayList($data, Customer $customer, PropelModelPager $contacts) {
		$config = self::pw('config');

		$html = '';
		$html .= self::displayBreadCrumbs($data);
		$html .= $config->twig->render('customers/ci/ship-tos/list.twig', ['customer' => $customer, 'shiptos' => $shiptos]);
		return $html;
	}
}
