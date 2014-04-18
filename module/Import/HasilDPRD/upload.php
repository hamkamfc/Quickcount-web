<?php
/*
 * Copyright 2014 - Mhd Sulhan
 * Authors:
 *   - mhd.sulhan (m.shulhan@gmail.com)
 */

$filePath		= "";
$import_table	= "hasil_dprd";
$import_type	= 2;

require_once "../../json_begin.php";
require_once "../upload.php";
require_once "../process.php";

process_import_suara ($filePath, $import_type, $import_table);

$r["success"]	= true;

require_once "../../json_end.php";
