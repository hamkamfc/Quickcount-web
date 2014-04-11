<?php

/*
 * Copyright 2014 - Mhd Sulhan
 * Authors:
 *   - mhd.sulhan (m.shulhan@gmail.com)
 */

require_once "../../../../json_begin.php";

try {
	$query	= $_GET["query"];
	$filter = json_decode ($_GET["filter"]);
	$qwhere	= "";

	if (count($filter) > 0) {
		for ($i = 0; $i < count($filter); $i++) {
			$qwhere .=" and ". $filter[$i]->property ." = ". $filter[$i]->value;
		}
	}

	// Get total row
	$q	=
"
select	COUNT(kelurahan_id)			as total
from	(
			select	distinct
					H.kelurahan_id
			from	hasil_dprd		H
			where	1 = 1 ". $qwhere ."
		) A
";

	$ps = Jaring::$_db->prepare ($q);
	$ps->execute ();
	$rs = $ps->fetchAll (PDO::FETCH_ASSOC);
	$ps->closeCursor ();

	if (count ($rs) > 0) {
		$t = (int) $rs[0]["total"];
	}

	// Get data
	$q	=
"
select	A.kelurahan_id						as id
,		(
			select	K.nama
			from	kelurahan	K
			where	K.id = A.kelurahan_id
		)									as nama
from	(
			select	distinct
					H.kelurahan_id
			from	hasil_dprd	H
			where	1 = 1 ". $qwhere ."
		)	A
order by	A.kelurahan_id
limit		". (int) $_GET["start"] .",". (int) $_GET["limit"];

	$ps = Jaring::$_db->prepare ($q);
	$i	= 1;
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
	$r['q']		= $q;
}

require_once "../../../../json_end.php";

