<?php
	$itm = $modules->get('Itm');
	//$itm_costing = $modules->get('ItmCosting');
	$xrefs = new ProcessWire\WireData();
	$xrefs->cxm  = $modules->get('XrefCxm');
	$xrefs->upcx = $modules->get('XrefUpc');
	$recordlocker = $modules->get('RecordLockerUser');

	if ($input->get->itemID) {
		$itemID = $input->get->text('itemID');

		if ($itm->item_exists($itemID)) {
			$item = $itm->get_item($itemID);
			$page->body .= $config->twig->render('items/itm/xrefs/form.twig', ['page' => $page, 'recordlocker' => $recordlocker, 'm_itm' => $itm, 'item' => $item, 'xrefs' => $xrefs]);
		} else {
			$session->redirect($page->itmURL($itemID), $http301 = false);
		}
	} else {
		$session->redirect($page->itmURL(), $http301 = false);
	}

	include __DIR__ . "/basic-page.php";
