<?php
require_once "../../json_begin.php";

/**
 * upload.php
 *
 * Copyright 2013, Moxiecode Systems AB
 * Released under GPL License.
 *
 * License: http://www.plupload.com/license
 * Contributing: http://www.plupload.com/contributing
 */

#!! IMPORTANT:
#!! this file is just an example, it doesn't incorporate any security checks and
#!! is not recommended to be used in production environment as it is. Be sure to
#!! revise it and customize to your needs.


// Make sure file is not cached (as it happens for example on iOS devices)
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

/*
// Support CORS
header("Access-Control-Allow-Origin: *");
// other CORS headers if any...
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
	exit; // finish preflight CORS requests here
}
*/

// 5 minutes execution time
@set_time_limit(5 * 60);

// Uncomment this one to fake upload time
// usleep(5000);

// Settings
//$targetDir = ini_get("upload_tmp_dir") . DIRECTORY_SEPARATOR . "plupload";
$targetDir = '../../../uploads';
$cleanupTargetDir = false; // Remove old files
$maxFileAge = 5 * 3600; // Temp file age in seconds

// Create target dir
if (!file_exists($targetDir)) {
	@mkdir($targetDir);
}

// Get a file name
if (isset($_REQUEST["name"])) {
	$fileName = $_REQUEST["name"];
} elseif (!empty($_FILES)) {
	$fileName = $_FILES["file"]["name"];
} else {
	$fileName = uniqid("file_");
}

$filePath = $targetDir . DIRECTORY_SEPARATOR . $fileName;

// Chunking might be enabled
$chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
$chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 0;


// Remove old temp files
if ($cleanupTargetDir) {
	if (!is_dir($targetDir) || !$dir = opendir($targetDir)) {
		die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "id"}');
	}

	while (($file = readdir($dir)) !== false) {
		$tmpfilePath = $targetDir . DIRECTORY_SEPARATOR . $file;

		// If temp file is current file proceed to the next
		if ($tmpfilePath == "{$filePath}.part") {
			continue;
		}

		// Remove temp file if it is older than the max age and is not the current file
		if (preg_match('/\.part$/', $file) && (filemtime($tmpfilePath) < time() - $maxFileAge)) {
			@unlink($tmpfilePath);
		}
	}
	closedir($dir);
}


// Open temp file
if (!$out = @fopen("{$filePath}.part", $chunks ? "ab" : "wb")) {
	die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id", "filePath" : "'. $filePath .'"}');
}

if (!empty($_FILES)) {
	if ($_FILES["file"]["error"] || !is_uploaded_file($_FILES["file"]["tmp_name"])) {
		die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
	}

	// Read binary input stream and append it to temp file
	if (!$in = @fopen($_FILES["file"]["tmp_name"], "rb")) {
		die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
	}
} else {
	if (!$in = @fopen("php://input", "rb")) {
		die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
	}
}

while ($buff = fread($in, 4096)) {
	fwrite($out, $buff);
}

@fclose($out);
@fclose($in);

// Check if file has been uploaded
if (!$chunks || $chunk == $chunks - 1) {
	// Strip the temp .part suffix off
	rename("{$filePath}.part", $filePath);
}

$table = "hasil_dpr";
$data = [];
$c		= 0;

if (($handle = fopen($filePath, "r")) !== FALSE) {
	while (($r = fgetcsv($handle, 0, ";")) !== FALSE) {
		$data[] = $r;
		$c++;

		if ($c === 1000) {
			doInsert ($data);
			$data	= [];
			$c		= 0;
		}
	}
	doInsert ($data);
}

$q = " insert into imported (type, filename, status) values ( 1 , ? , 1 )";
$ps = Jaring::$_db->prepare ($q);
$ps->bindValue (1, $fileName, PDO::PARAM_STR);
$ps->execute ();

$r["success"] = true;

require_once "../../json_end.php";

function doInsert ($data)
{
	global $table;

	$q	="	delete	from ". $table
		."	where	dapil_id		= ?"
		."	and		kecamatan_id	= ?"
		."	and		kelurahan_id	= ?"
		."	and		tps_id			= ?"
		."	and		kode_saksi		= ?"
		."	and		partai_id		= ?"
		."	and		caleg_id		= ?";

	Jaring::$_db->beginTransaction ();

	$ps = Jaring::$_db->prepare ($q);

	foreach ($data as $in) {
		$i = 1;
		$ps->bindValue ($i++, $in[0], PDO::PARAM_INT);
		$ps->bindValue ($i++, $in[1], PDO::PARAM_INT);
		$ps->bindValue ($i++, $in[2], PDO::PARAM_INT);
		$ps->bindValue ($i++, $in[3], PDO::PARAM_INT);
		$ps->bindValue ($i++, $in[4], PDO::PARAM_STR);
		$ps->bindValue ($i++, $in[5], PDO::PARAM_INT);
		$ps->bindValue ($i++, $in[6], PDO::PARAM_INT);
		$ps->execute ();
	}

	$ps->closeCursor ();
	$ps = null;

	Jaring::$_db->commit ();
	Jaring::$_db->beginTransaction ();

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

	Jaring::$_db->commit ();
}