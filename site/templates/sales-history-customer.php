<?php
	use Propel\Runtime\ActiveQuery\Criteria;

	$query = SalesHistoryQuery::create();
	$custID = $input->get->text('custID');
	$query->filterByCustId("$custID");

	if ($user->is_salesrep()) {
		$query->filterbySalesPerson($user->roleid);
	}
	$orders = $query->limit(10)->orderByDate_ordered('DESC')->find();

	if ($input->get->filter) {
		if ($input->get->text('ordernumber_from') && $input->get->text('ordernumber_through')) {
			$query->filterByOrdernumber(array($input->get->text('ordernumber_from'), $input->get->text('ordernumber_through')));
		} else if ($input->get->text('ordernumber_from')) {
			$query->filterByOrdernumber($input->get->text('ordernumber_from'));
		} else if ($input->get->text('ordernumber_through')) {
			$query->filterByOrdernumber($input->get->text('ordernumber_through'));
		}

		if ($input->get->text('custpo')) {
			$custpo = $input->get->text('custpo');
			$query->filterByCustpo("%$custpo%", Criteria::LIKE);
		}

		if ($input->get->text('order_total_from') && $input->get->text('order_total_through')) {
			$query->filterByOrdertotal(array($input->get->text('order_total_from'), $input->get->text('order_total_through')));
		} else if ($input->get->text('order_total_from')) {
			$query->filterByTotal_total($input->get->text('order_total_from'), Criteria::GREATER_EQUAL);
		} else if ($input->get->text('order_total_through')) {
			$query->filterByTotal_total($input->get->text('order_total_through'), Criteria::LESS_EQUAL);
		}

		if ($input->get->text('invoicedate_from') || $input->get->text('invoicedate_through')) {
			$invoicedate_from = date("Ymd", strtotime($input->get->text('invoicedate_from')));

			if (empty($input->get->text('invoicedate_through'))) {
				$invoicedate_through = date('Ymd');
			} else {
				$invoicedate_through = date("Ymd", strtotime($input->get->text('invoicedate_through')));
			}

			if ($invoicedate_from && $invoicedate_through) {
				$query->filterByInvoicedate(array($invoicedate_from, $invoicedate_through));
			} else if ($invoicedate_from) {
				$query->filterByInvoicedate($invoicedate_from);
			} else if ($invoicedate_through) {
				$query->filterByInvoicedate($invoicedate_through);
			}
		}

		if ($input->get->text('orderdate_from') || $input->get->text('orderdate_through')) {
			$orderdate_from = date("Ymd", strtotime($input->get->text('orderdate_from')));

			if (empty($input->get->text('orderdate_through'))) {
				$orderdate_through = date('Ymd');
			} else {
				$orderdate_through = date("Ymd", strtotime($input->get->text('orderdate_through')));
			}

			if ($orderdate_from && $orderdate_through) {
				$query->filterByOrderdate(array($orderdate_from, $orderdate_through));
			} else if ($orderdate_from) {
				$query->filterByOrderdate($orderdate_from);
			} else if ($orderdate_through) {
				$query->filterByOrderdate($orderdate_through);
			}
		}
	}

	$orders = $query->paginate($input->pageNum, 10);

	$page->body = $config->twig->render('customers/ci/customer/shipped-orders-search-form.twig', ['page' => $page, 'input' => $input, 'custid' => $custID]);
	$page->body .= $config->twig->render('customers/ci/customer/cust-shipped-orders-list.twig', ['orders' => $orders, 'orderpage' => $pages->get('pw_template=sales-order-view')->url]);
	$page->body .= $config->twig->render('util/paginator.twig', ['page' => $page, 'pagenbr' => $input->pageNum, 'resultscount'=> $orders->getNbResults()]);
	include __DIR__ . "/basic-page.php";
