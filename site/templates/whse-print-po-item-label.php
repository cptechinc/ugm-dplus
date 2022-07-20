<?php
	$whsesession = WhsesessionQuery::create()->findOneBySessionid(session_id());
	$warehouse   = WarehouseQuery::create()->findOneByWhseid($whsesession->whseid);
	$validate_po = $modules->get('ValidatePurchaseOrderNbr');

	if ($input->get->ponbr) {
		$ponbr = $input->get->text('ponbr');

		if ($validate_po->validate($ponbr)) {

		} else {
			$page->headline = "PO #$ponbr could not be found";
			$page->body = $config->twig->render('util/alert.twig', ['type' => 'danger', 'title' => "Error! PO Number $ponbr not found", 'iconclass' => 'fa fa-warning fa-2x', 'message' => "Check if the Order Number is correct"]);
		}
	} else {

	}

	// Add JS
	$config->scripts->append(hash_templatefile('scripts/lib/jquery-validate.js'));
	$config->scripts->append(hash_templatefile('scripts/warehouse/print-item-label.js'));

	include __DIR__ . "/basic-page.php";
