<?php

/*
 * Copyright 2014 - Mhd Sulhan
 * Authors:
 *   - mhd.sulhan (m.shulhan@gmail.com)
 */

require_once "../../json_begin.php";

try {
	$query		= $_GET["query"];
	$filter		= json_decode ($_GET["filter"]);
	$qfilter	= "";

	if (count($filter) > 0) {
		for ($i = 0; $i < count($filter); $i++) {
			$qfilter .=" and ". $filter[$i]->property ." = ". $filter[$i]->value;
		}
	}

	// Get total row
	$q	=
"	select	COUNT(id) as total
	from	saksi
	where	1 = 1
".	$qfilter;

	$ps = Jaring::$_db->prepare ($q);
	$ps->execute ();
	$rs = $ps->fetchAll (PDO::FETCH_ASSOC);
	$ps->closeCursor ();

	if (count ($rs) > 0) {
		$t = (int) $rs[0]["total"];
	}

	// Get data
	$q	="
			select		kode
			from		saksi
			where		1 = 1
		". $qfilter;

	$q	.="	order by	id"
		."	limit		". (int) $_GET["start"] .",". (int) $_GET["limit"];

	$ps = Jaring::$_db->prepare ($q);
	$ps->execute ();
	$rs = $ps->fetchAll (PDO::FETCH_ASSOC);
	$ps->closeCursor ();

	$r = array (
		'success'	=> true
	,	'data'		=> $rs
	,	'total'		=> $t
	);
} catch (Exception $e) {
	$r['data']	= $e->getMessage ();
}

require_once "../../json_end.php";
