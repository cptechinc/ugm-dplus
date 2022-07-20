<?php
	$rm = strtolower($input->requestMethod());
	$values = $input->$rm;
	$whsesession = WhsesessionQuery::create()->findOneBySessionid(session_id());
	$warehouse   = WarehouseQuery::create()->findOneByWhseid($whsesession->whseid);
	$validate_po = $modules->get('ValidatePurchaseOrderNbr');
	$m_print = $modules->get('PrintLabelItem');

	if ($values->ponbr) {
		$ponbr = PurchaseOrder::get_paddedponumber($input->get->text('ponbr'));

		if ($validate_po->validate($ponbr)) {
			$po = PurchaseOrderQuery::create()->findOneByPonbr($ponbr);

			if ($values->linenbr) {
				$linenbr = $values->int('linenbr');
				$po_line = $po->get_receivingitem($linenbr);
				$labelsession = $m_print->get_session();

				if ($labelsession->isNew()) {
					$labelsession->setSessionid(session_id());
					$labelsession->setWhse($whsesession->whseid);
					$labelsession->setItemid($po_line->itemid);

					if ($values->lotserial) {
						$labelsession->setBin($item->bin);
						$labelsession->setLotserial($item->lotserial);
					}


				}
			} else {
				// TODO CHOOSE LINE  / LOTS
			}
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
