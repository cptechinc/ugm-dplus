<?php namespace Controllers\Mwm\Inventory\Mlot;
// Purl URI Manipulation Library
use Purl\Url as Purl;
// Propel ORM Library
use Propel\Runtime\Util\PropelModelPager;
// Document Management

// Dplus Filters
use Dplus\Filters;
// Dplus Warehouse Management
use Dplus\Wm\Inventory\Lotm;

// Dplus CRUD

// Mvc Controllers
use Controllers\Wm\Base;

class Labels extends Base {
	const DPLUSPERMISSION = 'wm';
	const TITLE = 'Labels';

	private static $docm;

/* =============================================================
	Indexes
============================================================= */
	public static function index($data) {
		self::sanitizeParametersShort($data, ['scan|text', 'lotserial|text', 'action|text']);
		if (self::validateUserPermission() === false) {
			return self::displayUserNotPermitted();
		}
		if (empty($data->action) === false) {
			return self::handleCRUD($data);
		}
		if (empty($data->lotserial) === false) {
			return self::lotserial($data);
		}
		return self::list($data);
	}

	// public static function handleCRUD($data) {
	// 	self::sanitizeParametersShort($data, ['scan|text', 'lotserial|text', 'action|text']);
	// 	$url = Menu::imgUrl();
	// 	$manager = self::getImg();
	// 	$success = $manager->process(self::pw('input'));
	//
	// 	switch ($data->action) {
	// 		case 'update':
	// 			if ($success === false) {
	// 				$url = self::lotserialUrl($data->lotserial);
	// 			}
	// 			break;
	// 	}
	// 	self::pw('session')->redirect($url, $http301 = false);
	// }


	// private static function lotserial($data) {
	// 	Search::getInstance()->requestSearch($data->lotserial);
	// 	self::copyImage($data);
	//
	// 	self::initHooks();
	// 	self::pw('page')->headline = "Lotserial #$data->lotserial";
	// 	self::pw('page')->js .= self::pw('config')->twig->render('warehouse/inventory/mlot/img/lotserial/.js.twig');
	// 	self::pw('config')->scripts->append(self::pw('modules')->get('FileHasher')->getHashUrl('scripts/lib/jquery-validate.js'));
	// 	$html = self::displayLotserial($data);
	// 	self::getImg()->deleteResponse();
	// 	return $html;
	// }

	private static function list($data) {
		self::sanitizeParametersShort($data, ['q|text', 'itemID|text']);
		self::initHooks();
		self::pw('page')->headline = self::TITLE;
		// self::pw('page')->js .= self::pw('config')->twig->render('warehouse/inventory/mlot/img/lotserial/.js.twig');
		// self::pw('config')->scripts->append(self::pw('modules')->get('FileHasher')->getHashUrl('scripts/lib/jquery-validate.js'));
		$filter = new Filters\Min\LotMaster();

		if (empty($data->q) === false) {
			$lotm = Lotm::getInstance();

			if ($lotm->exists($data->q)) {
				self::pw('session')->redirect(self::lotserialUrl($data->q), $http301 = false);
			}
			self::pw('page')->headline = "Searching Lots for $data->q";
			$filter->search($data->q);
		}
		$lots = $filter->query->paginate(self::pw('input')->pageNum, 10);
		$html = self::displayList($data, $lots);
		// self::getImg()->deleteResponse();
		return $html;
	}


/* =============================================================
	Display Functions
============================================================= */
	private static function displayList($data, PropelModelPager $lots) {
		$html  = '';
		// $html .= self::displayResponse($data);
		$html .= self::pw('config')->twig->render('warehouse/inventory/mlot/labels/list/display.twig', ['lots' => $lots]);
		return $html;
	}

	//
	// private static function displayLotserial($data) {
	// 	$inventory = Search::getInstance();
	// 	$lotserial = $inventory->getLotserial($data->lotserial);
	// 	$docm = self::getDocm();
	//
	// 	$html  = '';
	// 	$html .= self::displayResponse($data);
	// 	$html .= self::pw('config')->twig->render('warehouse/inventory/mlot/img/lotserial/display.twig', ['lotserial' => $lotserial, 'docm' => $docm]);
	// 	return $html;
	// }
	//
	// private static function displayResponse($data) {
	// 	$imgM = self::getImg();
	// 	$response = $imgM->getResponse();
	//
	// 	if (empty($response)) {
	// 		return '';
	// 	}
	// 	return self::pw('config')->twig->render('code-tables/response.twig', ['response' => $response]);
	// }

/* =============================================================
	URL Functions
============================================================= */
	public static function lotserialUrl($lotserial) {
		$url = new Purl(Menu::labelsUrl());
		$url->query->set('lotserial', $lotserial);
		return $url->getUrl();
	}

/* =============================================================
	Init
============================================================= */
	public static function initHooks() {
		$m = self::pw('modules')->get('Dpages');

		$m->addHook('Page(pw_template=whse-mlot)::lotserialUrl', function($event) {
			$event->return = self::lotserialUrl($event->arguments(0));
		});

		$m->addHook('Page(pw_template=whse-mlot)::labelsUrl', function($event) {
			$event->return = Menu::labelsUrl();
		});
	}
}
