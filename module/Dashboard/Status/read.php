<?php

/*
 * Copyright 2014 - Mhd Sulhan
 * Authors:
 *   - mhd.sulhan (m.shulhan@gmail.com)
 */

require_once "../../json_begin.php";

try {
$q =
"
select	count(TPS.tps_id)	as jumlah_tps
,		SUARA.v				as jumlah_suara
from (
	select	distinct
			HD.tps_id
	from	hasil_dpr HD
	left join (
		select	distinct
				tps_id
		from	hasil_dprd
	) HDD on HD.tps_id = HDD.tps_id
) TPS
, (
	select	DPR.v + DPRD.v as v
	from (
		select	ifnull(sum(hasil),0) as v
		from	hasil_dpr
		where	status = 1
	) DPR
	, (
		select	ifnull(sum(hasil),0) as v
		from	hasil_dprd
		where	status = 1
	) DPRD
) SUARA
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

require_once "../../json_end.php";