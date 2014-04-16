<?php

/*
 * Copyright 2014 - Mhd Sulhan
 * Authors:
 *   - mhd.sulhan (m.shulhan@gmail.com)
 */

require_once "../../../json_begin.php";

$table_hasil	= "hasil_dpr";
$table_caleg	= "caleg_dpr";

try {
	$q=
"
(
	select	D.nama					as dapil_nama
	,		P.nama					as partai_nama
	,		CD.nama					as caleg_nama
	,		ifnull(sum(H.hasil),0)	as hasil
	from	hasil_dprd		H
	,		caleg_dprd		CD
	,		dapil			D
	,		partai			P
	where	H.status		= 1
	and		H.dapil_id		= D.id
	and		H.caleg_id		= CD.id
	and		CD.partai_id	= P.id
	and		CD.type			= 0
	and		H.dapil_id		= 2
	group by D.id, CD.caleg_id
	order by D.id asc, hasil desc
	limit 0,15
)
union all
(
	select	D.nama					as dapil_nama
	,		P.nama					as partai_nama
	,		CD.nama					as caleg_nama
	,		ifnull(sum(H.hasil),0)	as hasil
	from	hasil_dprd		H
	,		caleg_dprd		CD
	,		dapil			D
	,		partai			P
	where	H.status		= 1
	and		H.dapil_id		= D.id
	and		H.caleg_id		= CD.id
	and		CD.partai_id	= P.id
	and		CD.type			= 0
	and		H.dapil_id		= 3
	group by D.id, CD.caleg_id
	order by D.id asc, hasil desc
	limit 0,15
)
union all
(
	select	D.nama					as dapil_nama
	,		P.nama					as partai_nama
	,		CD.nama					as caleg_nama
	,		ifnull(sum(H.hasil),0)	as hasil
	from	hasil_dprd		H
	,		caleg_dprd		CD
	,		dapil			D
	,		partai			P
	where	H.status		= 1
	and		H.dapil_id		= D.id
	and		H.caleg_id		= CD.id
	and		CD.partai_id	= P.id
	and		CD.type			= 0
	and		H.dapil_id		= 9
	group by D.id, CD.caleg_id
	order by D.id asc, hasil desc
	limit 0,15
)
union all
(
	select	D.nama					as dapil_nama
	,		P.nama					as partai_nama
	,		CD.nama					as caleg_nama
	,		ifnull(sum(H.hasil),0)	as hasil
	from	hasil_dprd		H
	,		caleg_dprd		CD
	,		dapil			D
	,		partai			P
	where	H.status		= 1
	and		H.dapil_id		= D.id
	and		H.caleg_id		= CD.id
	and		CD.partai_id	= P.id
	and		CD.type			= 0
	and		H.dapil_id		= 10
	group by D.id, CD.caleg_id
	order by D.id asc, hasil desc
	limit 0,15
)
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