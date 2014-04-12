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
		$qwhere .= " and dapil_id = ". $dapil_id;
	}
	if ($kecamatan_id !== null && $kecamatan_id > 0) {
		$qwhere .= " and kecamatan_id = ". $kecamatan_id;
	}
	if ($kelurahan_id !== null && $kelurahan_id > 0) {
		$qwhere .= " and kelurahan_id = ". $kelurahan_id;
	}

	$q	=
"
select	P.nama	as partai_nama
,		A.nama	as caleg_nama
,		ifnull((
			select	sum(hasil)
			from	hasil_dpr	H
			where	H.caleg_id	= A.id
			and		H.partai_id	= A.partai_id
			and		H.kode_saksi in (
						select	SD.kode_saksi
						from	saksi_default	SD
						where	SD.type	= 1
							". $qwhere ."
					)
		), 0) as hasil
from	caleg_dpr	A
,		partai		P
where	A.partai_id = P.id
";

	if ($dapil_id !== null && $dapil_id > 0) {
		$q .= " and A.dapil_id = ". $dapil_id;
	}

$q .="
group by A.nama
order by A.partai_id, A.nama;
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