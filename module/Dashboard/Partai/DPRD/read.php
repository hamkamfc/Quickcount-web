<?php

/*
 * Copyright 2014 - Mhd Sulhan
 * Authors:
 *   - mhd.sulhan (m.shulhan@gmail.com)
 */

require_once "../../../json_begin.php";

$table_hasil	= "hasil_dprd";
$table_caleg	= "caleg_dprd";

try {
$q =
"
select	X.partai_id		as id
,		X.partai_nama	as nama
,		X.hasil			as hasil
,		((X.hasil / (
			select	sum(hasil)	as pembagi
			from	". $table_hasil ."
			where	status = 1
		)) * 100)		as persentase
from (
	select	P.id		as partai_id
	,		P.nama		as partai_nama
	,		ifnull((
				select	sum(A.hasil)
				from	". $table_hasil ."	A
				where	A.partai_id = P.id
				and		A.status	= 1
				group by A.partai_id
			),0) as hasil
	from	partai P
) X
order by X.hasil asc
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