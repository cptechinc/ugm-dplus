<?php
	$module_ii = $modules->get('DpagesMii');
	$module_ii->init_iipage();
	$html = $modules->get('HtmlWriter');
	$lookup_ii = $modules->get('LookupItemIi');

	if ($input->get->itemID) {
		$itemID = $input->get->text('itemID');

		if ($lookup_ii->lookup_itm($itemID)) {
			$page->headline = "II: $itemID";
			$item = ItemMasterItemQuery::create()->findOneByItemid($itemID);
			$itempricing = ItemPricingQuery::create()->findOneByItemid($itemID);
			$module_json = $modules->get('JsonDataFiles');
			$json = $module_json->get_file(session_id(), 'ii-stock');
			$documentmanagement = $modules->get('DocumentManagement');

			$toolbar = $config->twig->render('items/ii/toolbar.twig', ['page' => $page, 'item' => $item]);
			$links = $config->twig->render('items/ii/item/ii-links.twig', ['page' => $page, 'itemID' => $itemID, 'lastmodified' => $module_json->file_modified(session_id(), 'ii-stock'), 'refreshurl' => $page->get_itemURL($itemID)]);
			$description = $config->twig->render('items/ii/item/description.twig', ['item' => $item, 'page' => $page]);
			$itemdata = $config->twig->render('items/ii/item/item-data.twig', ['page' => $page, 'item' => $item, 'itempricing' => $itempricing]);

			if ($module_json->file_exists(session_id(), 'ii-stock')) {
				$session->itemtry = 0;

				if ($json['error']) {
					$stock .= $config->twig->render('util/alert.twig', ['type' => 'danger', 'title' => 'Error!', 'iconclass' => 'fa fa-warning fa-2x', 'message' => $json['errormsg']]);
				} else {
					$module_formatter = $modules->get('SfIiStockItem');
					$module_formatter->init_formatter();
					$stock = $config->twig->render('items/ii/item/stock.twig', ['page' => $page, 'itemID' => $itemID, 'json' => $json, 'module_formatter' => $module_formatter, 'blueprint' => $module_formatter->get_tableblueprint()]);
				}
			} else {
				if ($session->itemtry > 3) {
					$stock = $config->twig->render('util/alert.twig', ['type' => 'danger', 'title' => "JSON Decode Error", 'iconclass' => 'fa fa-warning fa-2x', 'message' => $module_json->get_error()]);
				} else {
					$session->itemtry++;
					$session->redirect($page->get_itemURL($itemID));
				}
			}
			$page->body = "<div class='row'>";
				$page->body .= $html->div('class=col-sm-2 pl-0', $toolbar);
				$page->body .= $html->div('class=col-sm-10', $links.$description.$itemdata.$stock);
			$page->body .= "</div>";
		} else {
			$page->headline = $page->title = "Item $itemID could not be found";
			$page->body = $config->twig->render('util/error-page.twig', ['title' => $page->title, 'msg' => "Check if the item ID is correct"]);
		}
	} else {
		$q = $input->get->q ? $input->get->text('q') : '';
		$page->title = $q ? "II: results for '$q'" : $page->title;

		if ($lookup_ii->lookup(strtoupper($q))) {
			$session->redirect($page->get_itemURL($lookup_ii->itemID));
		} else {
			$filter_itm = $modules->get('FilterItemMaster');
			$filter_itm->init_query($user);
			$filter_itm->filter_search($q);
			$query = $filter_itm->get_query();
			$items = $query->paginate($input->pageNum, 10);
		}

		$page->searchURL = $page->url;
		$page->body = $config->twig->render('items/item-search.twig', ['page' => $page, 'items' => $items]);
		$page->body .= $config->twig->render('util/paginator.twig', ['page' => $page, 'resultscount'=> $items->getNbResults()]);
	}

	if ($page->print) {
		include __DIR__ . "/blank-page.php";
	} else {
		include __DIR__ . "/basic-page.php";
	}
