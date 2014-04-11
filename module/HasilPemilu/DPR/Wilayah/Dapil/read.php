<?php

/*
 * Copyright 2014 - Mhd Sulhan
 * Authors:
 *   - mhd.sulhan (m.shulhan@gmail.com)
 */

require_once "../../../../json_begin.php";

try {
	$query = $_GET["query"];

	// Get total row
	$q	=
"
select		COUNT(A.dapil_id) as total
from		(
				select	distinct
						dapil_id
				from	hasil_dpr
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
select		A.dapil_id	as id
,			(
				select	B.nama
				from	dapil	B
				where	B.id	= A.dapil_id
			) as nama
from		(
				select	distinct
						dapil_id
				from	hasil_dpr
			) A
";

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

