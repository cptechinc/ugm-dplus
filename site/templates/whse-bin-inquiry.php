<?php
	$page->print = ($page->template == 'print');
	$whsesession = WhsesessionQuery::create()->findOneBySessionid(session_id());
	$warehouse = WarehouseQuery::create()->findOneByWhseid($whsesession->whseid);
	$config->inventory = $modules->get('ConfigsWarehouseInventory');

	if ($input->get->binID) {
		$binID = $input->get->text('binID');
		$page->binID = $binID;
		$page->title = "Bin Inquiry for $binID";
		$items = InvsearchQuery::create()->findDistinctItems(session_id(), $binID);
		$resultscount = InvsearchQuery::create()->countDistinctItemid(session_id(), $binID);
		$inventory = InvsearchQuery::create();

		if (!$config->ajax) {
			$page->body = $config->twig->render('warehouse/inventory/bin-inquiry/results-links-header.twig', ['page' => $page]);
		}

		if (file_exists($config->paths->templates."twig/warehouse/inventory/bin-inquiry/$config->company/results-list.twig")) {
			$page->body .= $config->twig->render("warehouse/inventory/bin-inquiry/$config->company/results-list.twig", ['page' => $page, 'resultscount' => $resultscount, 'items' => $items, 'warehouse' => $warehouse, 'inventory' => $inventory]);
		} else {
			$page->body .= $config->twig->render('warehouse/inventory/bin-inquiry/results-list.twig', ['page' => $page, 'resultscount' => $resultscount, 'items' => $items, 'warehouse' => $warehouse, 'inventory' => $inventory]);
		}
	} else {
		$page->formurl = $page->parent('template=warehouse-menu')->child('template=redir')->url;
		$page->body =  $config->twig->render('warehouse/inventory/bin-form.twig', ['page' => $page]);
		$page->body .= $config->twig->render('warehouse/inventory/bins-modal.twig', ['warehouse' => $warehouse]);
	}

	// Add JS
	$config->scripts->append(hash_templatefile('scripts/lib/jquery-validate.js'));
	$config->scripts->append(hash_templatefile('scripts/warehouse/shared.js'));
	$config->scripts->append(hash_templatefile('scripts/warehouse/bin-inquiry.js'));

	if ($page->print) {
		$page->show_title = true;
		include __DIR__ . "/blank-page.php";
	} elseif($config->ajax) {
		echo "<div class=''>$page->body</div>";
	} else {
		include __DIR__ . "/basic-page.php";
	}
