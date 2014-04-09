<?php
require_once "../../json_begin.php";

try {
	// Get total row
	$q	="	select		COUNT(filename) as total"
		."	from		imported"
		."	where		type = 2 and filename like ?";

	$ps = Jaring::$_db->prepare ($q);
	$ps->execute (
		array (
			"%". $_GET["query"] ."%"
		)
	);
	$rs = $ps->fetchAll (PDO::FETCH_ASSOC);
	$ps->closeCursor ();

	if (count ($rs) > 0) {
		$t = (int) $rs[0]["total"];
	}

	// Get data
	$q	="	select	filename"
		."	,	_when"
		."	from	imported"
		."	where	type = 2 and filename like ?"
		."	order by filename"
		."	limit ". (int) $_GET["start"] .",". (int) $_GET["limit"];

	$ps = Jaring::$_db->prepare ($q);
	$ps->execute (
		array (
			"%". $_GET["query"] ."%"
		)
	);
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