<?php
	// NOTE: $page->codetable is a hook Property that points to $page->name

	$po_codetables = $modules->get('CodeTablesPo');

	if ($input->requestMethod('POST') || $input->get->action) {
		$rm = strtolower($input->requestMethod());
		$action = $input->$rm->text('action');

		// TODO: LOGIC to see if code was removed
		$code = $input->$rm->text('code');

		if ($po_codetables->validate_codetable($page->codetable)) {
			$module_codetable = $po_codetables->get_codetable_module($page->codetable);
			$module_codetable->process_input($input);
			$session->redirect($page->get_codetable_viewURL($page->codetable, ''), $http301 = false);
		}
	}

	if ($po_codetables->validate_codetable($page->codetable)) {
		$page->focus = $input->get->focus ? $input->get->text('focus') : '';
		$module_codetable = $po_codetables->get_codetable_module($page->codetable);

		$page->headline = "$module_codetable->description Table";
		$page->body .= $config->twig->render('code-tables/links-header.twig', ['page' => $page, 'input' => $input]);

		if ($session->response_codetable) {
			$page->body .= $config->twig->render('code-tables/code-table-response.twig', ['response' => $session->response_codetable]);
		}

		if (file_exists(__DIR__."/po-code-table-$page->codetable.php")) {
			include(__DIR__."/po-code-table-$page->codetable.php");
		} else {
			$page->body .= $config->twig->render("code-tables/mpo/$page->codetable/list.twig", ['page' => $page, 'codes' => $module_codetable->get_codes(), 'response' => $session->response_codetable]);
			$page->body .= $config->twig->render('code-tables/edit-code-modal.twig', ['page' => $page, 'file' => "mpo/$page->codetable/form.twig", 'max_length_code' => $module_codetable->get_max_length_code()]);
			$page->js   .= $config->twig->render("code-tables/mpo/$page->codetable/js.twig", ['page' => $page, 'max_length_code' => $module_codetable->get_max_length_code()]);
		}
	} else {
		$page->body .= $config->twig->render('util/alert.twig', ['type' => 'danger', 'title' => "Code Table Error", 'iconclass' => 'fa fa-warning fa-2x', 'message' => "IN Code Table '$page->codetable' does not exist"]);
	}

	$config->scripts->append(hash_templatefile('scripts/lib/jquery-validate.js'));
	$page->js .= $config->twig->render("code-tables/js.twig", ['page' => $page]);

	if ($session->response_codetable) {
		$session->remove('response_codetable');
	}

	if ($page->print) {
		$page->show_title = true;
		include __DIR__ . "/blank-page.php";
	} else {
		include __DIR__ . "/basic-page.php";
	}
