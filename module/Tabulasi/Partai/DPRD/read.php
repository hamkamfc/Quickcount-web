<?php

/*
 * Copyright 2014 - Mhd Sulhan
 * Authors:
 *   - mhd.sulhan (m.shulhan@gmail.com)
 */

require_once "../../../json_begin.php";

try {
	$dapil_id		= $_GET["dapil_id"];
	$kecamatan_id	= $_GET["kecamatan_id"];
	$kelurahan_id	= $_GET["kelurahan_id"];
	$qwhere			= "";

	if ($dapil_id !== null && $dapil_id > 0) {
		$qwhere .= " and A.dapil_id = ". $dapil_id;
	}
	if ($kecamatan_id !== null && $kecamatan_id > 0) {
		$qwhere .= " and A.kecamatan_id = ". $kecamatan_id;
	}
	if ($kelurahan_id !== null && $kelurahan_id > 0) {
		$qwhere .= " and A.kelurahan_id = ". $kelurahan_id;
	}

		$q	=
"
select	X.partai_id
,		X.partai_nama
,		X.hasil
from (
	select	P.id		as partai_id
	,		P.nama		as partai_nama
	,		ifnull((
				select	sum(A.hasil)
				from	hasil_dprd	A
				,		caleg_dprd	C
				where	A.partai_id = P.id
				and		A.status	= 1
				and		A.caleg_id	= C.id
				and		C.type		= 1
				and		A.partai_id	= C.partai_id
						". $qwhere ."
				group by A.partai_id
			),0) as hasil
	from	partai P
) X
order by X.hasil desc
";

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

require_once "../../../json_end.php";