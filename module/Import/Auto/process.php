<?php

/*
 * Copyright 2014 - Mhd Sulhan
 * Authors:
 *   - mhd.sulhan (m.shulhan@gmail.com)
 */

require_once "../../json_begin.php";
require_once "../process.php";

$rs	= [];
$t	= 0;

function process_import_fail ($file, $type)
{
	$q		="
				insert into imported (
				  type
				, filename
				, status
				) values (
					? , ? , 0
				) on duplicate key update
				  status	= 0
				, _when		= NOW()
			";
	$ps	= Jaring::$_db->prepare ($q);
	$i		= 1;
	$ps->bindValue ($i++, $type, PDO::PARAM_INT);
	$ps->bindValue ($i++, $file, PDO::PARAM_STR);
	$ps->execute ();
	$ps->closeCursor ();
}

function processImportHasil ($dir, $table, $type)
{
	global $rs, $t;

	$ls = glob($dir.'/*');

	if (!$ls) {
		return;
	}

	foreach ($ls as $file) {
		try {
			process_import_suara ($file, $type, $table);
		} catch (Exception $e) {
			process_import_fail ($file, $type);
		}
	}
}

function processImportRekap ($dir, $table, $type)
{
	global $rs, $t;

	$ls = glob($dir.'/*');

	if (!$ls) {
		return;
	}

	foreach ($ls as $file) {
		$data = [];

		try {
			process_import_suara ($file, $type, $table);
		} catch (Exception $e) {
			process_import_fail ($file, $type);
		}
	}
}

try {
	// Process uploads/DPR
	processImportHasil ("../../../uploads/DPR", "hasil_dpr", 1);

	// Process uploads/DPRD
	processImportHasil ("../../../uploads/DPRD", "hasil_dprd", 2);

	// Process uploads/REKAP_DPR
	processImportRekap ("../../../uploads/REKAP_DPR", "rekap_suara_dpr", 3);

	// Process uploads/REKAP_DPRD
	processImportRekap ("../../../uploads/REKAP_DPRD", "rekap_suara_dprd", 4);

	$r = array (
		'success'	=> true
	,	'data'		=> $rs
	,	'total'		=> $t
	);
} catch (Exception $e) {
	$r['data'] = $e->getMessage ();
}

require_once "../../json_end.php";
