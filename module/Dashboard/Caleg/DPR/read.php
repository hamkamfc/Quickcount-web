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
$q =
"
select	Y.caleg_id	as id
,		caleg.nama	as nama
,		Y.hasil		as hasil
from (
	select	X.caleg_id
	,		sum(X.hasil) as hasil
	from (
		select	A.caleg_id
		,		B.hasil
		from	". $table_caleg ."	A
		,		". $table_hasil ."	B
		where	A.id		= B.caleg_id
		and		A.type		!= 1
		and		B.status	= 1
	) X
	group by X.caleg_id
) Y
, caleg
where Y.caleg_id = caleg.id
order by hasil desc
limit 0,10
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