<?php

/*
 * Copyright 2014 - Mhd Sulhan
 * Authors:
 *   - mhd.sulhan (m.shulhan@gmail.com)
 */

require_once "../../json_begin.php";

$rs	= [];
$t	= 0;

function processImportHasil ($dir, $table, $type)
{
	global $rs, $t;

	$ls = glob($dir.'/*');

	if (!$ls) {
		die ("{ success : false }");
	}

	Jaring::$_db->beginTransaction ();

	foreach ($ls as $file) {
		$data = [];

		$f = fopen($file, "r");
		if ($f !== FALSE) {
			$r = fgetcsv($f, 0, ";");
			while ($r !== FALSE) {
				$data[]	= $r;
				$r		= fgetcsv($f, 0, ";");
			}

			fclose ($f);
		}

		$q	="	delete	from ". $table
			."	where	dapil_id		= ?"
			."	and		kecamatan_id	= ?"
			."	and		kelurahan_id	= ?"
			."	and		tps_id			= ?"
			."	and		kode_saksi		= ?"
			."	and		partai_id		= ?"
			."	and		caleg_id		= ?";

		$ps1 = Jaring::$_db->prepare ($q);

		foreach ($data as $in) {
			$i = 1;
			$ps1->bindValue ($i++, $in[0], PDO::PARAM_INT);
			$ps1->bindValue ($i++, $in[1], PDO::PARAM_INT);
			$ps1->bindValue ($i++, $in[2], PDO::PARAM_INT);
			$ps1->bindValue ($i++, $in[3], PDO::PARAM_INT);
			$ps1->bindValue ($i++, $in[4], PDO::PARAM_STR);
			$ps1->bindValue ($i++, $in[5], PDO::PARAM_INT);
			$ps1->bindValue ($i++, $in[6], PDO::PARAM_INT);
			$ps1->execute ();
		}

		$ps1->closeCursor ();

		$q	=
"
	insert into ". $table ." (
	  dapil_id
	, kecamatan_id
	, kelurahan_id
	, tps_id
	, kode_saksi
	, caleg_id
	, partai_id
	, hasil
	) values
";

		$qv = " ( ?, ?, ?, ?, ?, ?, ?, ? ) ";
		$bv = array ();

		$first = true;
		foreach ($data as $in) {
			if ($first) {
				$q .= $qv;
				$first = false;
			} else {
				$q .= ", ". $qv;
			}
			$bv = array_merge ($bv, $in);
		}

		$ps2 = Jaring::$_db->prepare ($q);
		$ps2->execute ($bv);
		$ps2->closeCursor ();

		// insert into log
		$q		= " insert into imported (type, filename) values ( ? , ? )";
		$ps3	= Jaring::$_db->prepare ($q);
		$i		= 1;
		$ps3->bindValue ($i++, $type, PDO::PARAM_INT);
		$ps3->bindValue ($i++, $file, PDO::PARAM_STR);
		$ps3->execute ();
		$ps3->closeCursor ();

		$rs[]	= array (
					'filename'	=> $file
				,	'_when'		=> 0
				);
		$t++;
	}

	Jaring::$_db->commit ();
}

function processImportRekap ($dir, $table, $type)
{
	global $rs, $t;

	$ls = glob($dir.'/*');

	if (!$ls) {
		die ("{ success : false }");
	}

	Jaring::$_db->beginTransaction ();

	foreach ($ls as $file) {
		$data = [];

		$f = fopen($file, "r");
		if ($f !== FALSE) {
			$r = fgetcsv($f, 0, ";");
			while ($r !== FALSE) {
				$data[]	= $r;
				$r		= fgetcsv($f, 0, ";");
			}

			fclose ($f);
		}

		$q	="	delete	from ". $table
			."	where	dapil_id		= ?"
			."	and		kecamatan_id	= ?"
			."	and		kelurahan_id	= ?"
			."	and		tps_id			= ?"
			."	and		kode_saksi		= ?";

		$ps1 = Jaring::$_db->prepare ($q);

		foreach ($data as $in) {
			$i = 1;
			$ps1->bindValue ($i++, $in[0], PDO::PARAM_INT);
			$ps1->bindValue ($i++, $in[1], PDO::PARAM_INT);
			$ps1->bindValue ($i++, $in[2], PDO::PARAM_INT);
			$ps1->bindValue ($i++, $in[3], PDO::PARAM_INT);
			$ps1->bindValue ($i++, $in[4], PDO::PARAM_STR);
			$ps1->execute ();
		}

		$ps1->closeCursor ();

		$q	=
"
	insert into ". $table ." (
	  dapil_id
	, kecamatan_id
	, kelurahan_id
	, tps_id
	, kode_saksi
	, jumlah
	, rusak
	, sisa
	, sah
	, tidak_sah
	) values
";
		$qv = " ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ? ) ";
		$bv = array ();

		$first = true;
		foreach ($data as $in) {
			if ($first) {
				$q		.= $qv;
				$first	= false;
			} else {
				$q	.= ", ". $qv;
			}
			$bv = array_merge ($bv, $in);
		}

		$ps2 = Jaring::$_db->prepare ($q);
		$ps2->execute ($bv);
		$ps2->closeCursor ();

		// insert into log
		$q		= " insert into imported (type, filename) values ( ? , ? )";
		$ps3	= Jaring::$_db->prepare ($q);
		$i		= 1;
		$ps3->bindValue ($i++, $type, PDO::PARAM_INT);
		$ps3->bindValue ($i++, $file, PDO::PARAM_STR);
		$ps3->execute ();
		$ps3->closeCursor ();

		$rs[]	= array (
					'filename'	=> $file
				,	'_when'		=> date('Y-m-d H:i:s')
				);
		$t++;
	}

	Jaring::$_db->commit ();
}

try {
	// Process uploads/DPR
	processImportHasil ("../../../uploads/DPR", "hasil_dpr", 1);

	// Process uploads/DPRD
	processImportHasil ("../../../uploads/DPR", "hasil_dprd", 2);

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

	echo json_encode ($r);

	die ();
}

require_once "../../json_end.php";