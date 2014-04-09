<?php

/*
 * Copyright 2014 - Mhd Sulhan
 * Authors:
 *   - mhd.sulhan (m.shulhan@gmail.com)
 */

require_once "../../json_begin.php";

try {
	$query = $_GET["query"];

	// Get total row
	$q	="	select		COUNT(id) as total"
		."	from		dapil"
		."	where		nama		like ?";

	$ps = Jaring::$_db->prepare ($q);
	$ps->bindValue (1, "%". $query ."%");
	$ps->execute ();
	$rs = $ps->fetchAll (PDO::FETCH_ASSOC);
	$ps->closeCursor ();

	if (count ($rs) > 0) {
		$t = (int) $rs[0]["total"];
	}

	// Get data
	$q	="	select		id"
		."	,			nama"
		."	from		dapil"
		."	where		nama like ?"
		."	order by	id"
		."	limit		". (int) $_GET["start"] .",". (int) $_GET["limit"];

	$ps = Jaring::$_db->prepare ($q);
	$i	= 1;
	$ps->bindValue ($i++, "%". $query ."%");
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

require_once "../../json_end.php";

