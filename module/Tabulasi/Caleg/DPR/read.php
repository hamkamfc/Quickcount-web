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
	$table_hasil	= "hasil_dpr";
	$table_caleg	= "caleg_dpr";
	$qwhere			= "";

	if ($dapil_id !== null && $dapil_id > 0) {
		$qwhere .= " and dapil_id = ". $dapil_id;
	}
	if ($kecamatan_id !== null && $kecamatan_id > 0) {
		$qwhere .= " and kecamatan_id = ". $kecamatan_id;
	}
	if ($kelurahan_id !== null && $kelurahan_id > 0) {
		$qwhere .= " and kelurahan_id = ". $kelurahan_id;
	}

	$q=
"
	select	ifnull(sum(hasil), 0)	as pembagi
	from	". $table_hasil ."
	where	status = 1
".	$qwhere;

	$ps = Jaring::$_db->prepare ($q);
	$ps->execute ();
	$rs = $ps->fetchAll (PDO::FETCH_ASSOC);
	$ps->closeCursor ();

	$pembagi = $rs[0]["pembagi"];

	$q	=
"
select	A.nama	as caleg_nama
,		(
			select	P.nama
			from	partai P
			where	P.id = A.partai_id
		) as partai_nama
,		ifnull((
			select	sum(hasil)
			from	". $table_hasil ."	H
			where	H.caleg_id	= A.id
			and		H.partai_id	= A.partai_id
			and		H.status	= 1
			". $qwhere ."
		), 0) as hasil
from	". $table_caleg ."	A
where	1 = 1
";

	if ($dapil_id !== null && $dapil_id > 0) {
		$q .= " and A.dapil_id = ". $dapil_id;
	}

$q .="
group by A.nama
order by A.partai_id, A.no_urut, A.nama
";

	$q =
"
select	X.caleg_nama
,		X.partai_nama
,		X.hasil
,		((X.hasil / ". $pembagi .") * 100) as persentase
from (". $q .") X
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