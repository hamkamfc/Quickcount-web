<?php
/*
 * Copyright 2014 - Mhd Sulhan
 * Authors:
 *   - mhd.sulhan (m.shulhan@gmail.com)
 */

function insert_hasil_suara ($type, $table, $data)
{
	$q_in	=
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
		) values (
			? , ? , ? , ? , ? , ? , ? , ?
		) on duplicate key update 
			hasil = ?
	";

	$ps	= Jaring::$_db->prepare ($q_in);

	Jaring::$_db->beginTransaction ();

	foreach ($data as $in) {
		$i = 1;
		$ps->bindValue ($i++, $in[0], PDO::PARAM_INT);
		$ps->bindValue ($i++, $in[1], PDO::PARAM_INT);
		$ps->bindValue ($i++, $in[2], PDO::PARAM_INT);
		$ps->bindValue ($i++, $in[3], PDO::PARAM_INT);
		$ps->bindValue ($i++, $in[4], PDO::PARAM_STR);
		$ps->bindValue ($i++, $in[5], PDO::PARAM_INT);
		$ps->bindValue ($i++, $in[6], PDO::PARAM_INT);
		$ps->bindValue ($i++, $in[7], PDO::PARAM_INT);

		$ps->bindValue ($i++, $in[7], PDO::PARAM_INT);

		$ps->execute ();
	}

	Jaring::$_db->commit ();

	$ps->closeCursor ();
	$ps = null;
}

function insert_rekap_suara ($type, $table, $data)
{
	$q_in	="
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
		) values (
			?, ?, ?, ?, ?, ?, ?, ?, ?, ?
		) on duplicate key update
			jumlah		= ?
		,	rusak		= ?
		,	sisa		= ?
		,	sah			= ?
		,	tidak_sah	= ?
		";

	$ps	= Jaring::$_db->prepare ($q_in);

	Jaring::$_db->beginTransaction ();

	foreach ($data as $in) {
		$i = 1;
		$ps->bindValue ($i++, $in[0], PDO::PARAM_INT);
		$ps->bindValue ($i++, $in[1], PDO::PARAM_INT);
		$ps->bindValue ($i++, $in[2], PDO::PARAM_INT);
		$ps->bindValue ($i++, $in[3], PDO::PARAM_INT);
		$ps->bindValue ($i++, $in[4], PDO::PARAM_STR);

		$ps->bindValue ($i++, $in[5], PDO::PARAM_INT);
		$ps->bindValue ($i++, $in[6], PDO::PARAM_INT);
		$ps->bindValue ($i++, $in[7], PDO::PARAM_INT);
		$ps->bindValue ($i++, $in[8], PDO::PARAM_INT);
		$ps->bindValue ($i++, $in[9], PDO::PARAM_INT);

		$ps->bindValue ($i++, $in[5], PDO::PARAM_INT);
		$ps->bindValue ($i++, $in[6], PDO::PARAM_INT);
		$ps->bindValue ($i++, $in[7], PDO::PARAM_INT);
		$ps->bindValue ($i++, $in[8], PDO::PARAM_INT);
		$ps->bindValue ($i++, $in[9], PDO::PARAM_INT);

		$ps->execute ();
	}

	Jaring::$_db->commit ();

	$ps->closeCursor ();
	$ps = null;
}

function insert_hasil_file ($file, $type)
{
	$q	="
		insert into imported (
		  type
		, filename
		, status
		) values (
		". $type ." , ? , 1
		) on duplicate key update
			_when = NOW()
	";

	$ps = Jaring::$_db->prepare ($q);
	$ps->bindValue (1, basename ($file), PDO::PARAM_STR);
	$ps->execute ();
	$ps->closeCursor ();
	$ps = null;
}

function insert_hasil ($file, $type, $table, $data)
{
	switch ($type) {
	case 1:
	case 2:
		insert_hasil_suara ($type, $table, $data);
		break;
	case 3:
	case 4:
		insert_rekap_suara ($type, $table, $data);
		break;
	}

	insert_hasil_file ($file, $type);
}

function process_import_suara ($file, $type, $table)
{
	$data	= [];
	$c		= 0;

	if (($handle = fopen($file, "r")) !== FALSE) {
		while (($r = fgetcsv($handle, 0, ";")) !== FALSE) {
			$data[] = $r;
			$c++;

			if ($c === 1000) {
				insert_hasil ($file, $type, $table, $data);
				$data	= [];
				$c		= 0;
			}
		}
		if (count ($c) > 0) {
			insert_hasil ($file, $type, $table, $data);
		}
	}
}
