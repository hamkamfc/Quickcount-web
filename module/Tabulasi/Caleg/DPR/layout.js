/*
 * Copyright 2014 - Mhd Sulhan
 * Authors:
 *   - mhd.sulhan (m.shulhan@gmail.com)
 */

function JxTabulasi_Caleg_DPR ()
{
	this.id		= "Tabulasi_Caleg_DPR";
	this.dir	= Jx.generateModDir(this.id);

	this.sDapil		= Ext.create ("Jx.StorePaging", {
		url			:Jx.generateModDir ("Tabulasi_Wilayah_Dapil")
	,	singleApi	:false
	,	fields		:
		[
			"id"
		,	"nama"
		]
	});

	this.cbDapil		= Ext.create ("Jx.ComboPaging", {
		store			:this.sDapil
	,	name			:"dapil_id"
	,	fieldLabel		:"Dapil"
	,	valueField		:"id"
	,	displayField	:"nama"
	});

	this.sKecamatan = Ext.create ("Jx.StorePaging", {
		url			:Jx.generateModDir ("Tabulasi_Wilayah_Kecamatan")
	,	singleApi	:false
	,	fields		:
		[
			"id"
		,	"nama"
		]
	});

	this.cbKecamatan	= Ext.create ("Jx.ComboPaging", {
		store			:this.sKecamatan
	,	name			:"kecamatan_id"
	,	fieldLabel		:"Kecamatan"
	,	valueField		:"id"
	,	displayField	:"nama"
	});

	this.sKelurahan = Ext.create ("Jx.StorePaging", {
		url			:Jx.generateModDir ("Tabulasi_Wilayah_Kelurahan")
	,	singleApi	:false
	,	fields		:
		[
			"id"
		,	"nama"
		]
	});

	this.cbKelurahan	= Ext.create ("Jx.ComboPaging", {
		store			:this.sKelurahan
	,	name			:"kelurahan_id"
	,	fieldLabel		:"Kelurahan"
	,	valueField		:"id"
	,	displayField	:"nama"
	});

	this.sTPS		= Ext.create ("Jx.StorePaging", {
		url			:Jx.generateModDir ("Tabulasi_Wilayah_TPS")
	,	singleApi	:false
	,	fields		:
		[
			"id"
		,	"no"
		,	"nama"
		,	"alamat"
		]
	});

	this.cbTPS			= Ext.create ("Jx.ComboPaging", {
		store			:this.sTPS
	,	name			:"tps_id"
	,	fieldLabel		:"TPS"
	,	valueField		:"id"
	,	displayField	:"nama"
    ,	tpl				: Ext.create('Ext.XTemplate'
				,'<tpl for=".">'
				,	'<div class="x-boundlist-item">{no} - {alamat}</div>'
				,'</tpl>'
			)
	,	displayTpl		: Ext.create('Ext.XTemplate'
				,	'<tpl for=".">'
				,	'{no} - {alamat}'
				,	'</tpl>'
			)
	});

	this.bReload	= Ext.create ("Ext.button.Button", {
			text	:"Muat ulang"
		});

	this.form	= Ext.create ("Ext.form.Panel", {
		title	:"Filter Data"
	,	region	:"west"
	,	width	:"35%"
	,	split	:true
	,	defaults:
		{
			width		:400
		,	labelAlign	:"right"
		}
	,	items	:
		[
			this.cbDapil
		,	this.cbKecamatan
		,	this.cbKelurahan
		,	this.cbTPS
		]
	,	buttons	:
		["->"
		,	this.bReload
		,"->"
		]
	});

	this.store		= Ext.create ("Jx.Store", {
		url			:this.dir
	,	singleApi	:false
	,	groupField	:"partai_nama"
	,	fields		:
		[
			"partai_nama"
		,	"caleg_nama"
		,{
			name:"hasil"
		,	type:"int"
		}
		,{
			name:"persentase"
		,	type:"float"
		}]
	});

	this.grid	= Ext.create ("Ext.grid.Panel", {
		title	:"Tabulasi Perolehan Suara DPR RI"
	,	region	:"center"
	,	store	:this.store
	,	features:
		[{
			ftype			:"groupingsummary"
		,	hideGroupHeader	:true
		,	startCollapsed	:true
		}]
	,	columns	:
		[{
			header		:"Partai"
		,	dataIndex	:"partai_nama"
		,	hidden		:true
		},{
			header		:"Nama Caleg"
		,	dataIndex	:"caleg_nama"
		,	flex		:1
		,	summaryRenderer	:function (v)
			{
				return "Total suara partai dan caleg : ";
			}
		},{
			header		:"Hasil"
		,	dataIndex	:"hasil"
		,	width		:200
		,	summaryType	:"sum"
		},{
			header		:"Persentase"
		,	dataIndex	:"persentase"
		,	summaryType	:"sum"
		,	renderer	:function (v)
			{
				return (Math.round (v * 100) / 100) +" %";
			}
		}]
	});

	this.sRekap		= Ext.create ("Jx.Store", {
		url			:Jx.generateModDir ("Tabulasi_RekapDPR")
	,	singleApi	:false
	,	fields		:
		[
			"dapil_id"
		,	"kecamatan_id"
		,	"kelurahan_id"
		,	"tps_id"
		,	"kode_saksi"
		,	"jumlah"
		,	"rusak"
		,	"sisa"
		,	"sah"
		,	"tidak_sah"
		,	"jumlah_tps"
		]
	});

	this.formRekap = Ext.create ("Ext.form.Panel", {
		title		:"Rekap Suara"
	,	defaultType	:"displayfield"
	,	region		:"east"
	,	width		:"30%"
	,	split		:true
	,	defaults	:
		{
			labelAlign	:"right"
		,	labelWidth	:200
		}
	,	items		:
		[{
			fieldLabel	:"Jumlah Surat Suara"
		,	name		:"jumlah"
		},{
			fieldLabel	:"Surat Suara Rusak"
		,	name		:"rusak"
		},{
			fieldLabel	:"Surat Suara Sisa"
		,	name		:"sisa"
		},{
			fieldLabel	:"Surat Suara Sah"
		,	name		:"sah"
		},{
			fieldLabel	: "Surat Suara Tidak Sah"
		,	name		:"tidak_sah"
		},{
			fieldLabel	:"Total Jumlah TPS"
		,	name		:"jumlah_tps"
		}]
	});

	this.panel	= Ext.create ("Ext.container.Container", {
		itemId	:this.id
	,	title	:"Hasil Pemilu > DPR"
	,	closable:true
	,	layout	:"border"
	,	items	:
		[
			this.form
		,	this.grid
		,	this.formRekap
		]
	});

	this.cbDapilOnSelect = function (cb, r, e)
	{
		this.cbKecamatan.reset();
		this.cbKelurahan.reset();
		this.cbTPS.reset ();

		this.sKecamatan.clearFilter (true);
		this.sKecamatan.filter ("dapil_id", r[0].get("id"));
		this.sKelurahan.loadData ([]);
		this.sTPS.loadData ([]);
	};

	this.cbKecamatanOnSelect = function (cb, r, e)
	{
		this.cbKelurahan.reset();
		this.cbTPS.reset ();

		this.sKelurahan.clearFilter (true);
		this.sKelurahan.filter ("kecamatan_id", r[0].get("id"));
		this.sTPS.loadData ([]);
	};

	this.cbKelurahanOnSelect = function (cb, r, e)
	{
		this.cbTPS.reset ();

		this.sTPS.clearFilter (true);
		this.sTPS.filter ("kelurahan_id", r[0].get("id"));
	};

	this.doRefresh = function (perm)
	{
		this.sDapil.load ();
	};

	this.storeWilOnLoad = function (store, r, s)
	{
		if (!s) {
			return;
		}

		// inject record 'Semua'
		var all = { id : 0, nama : "Semua" };

		store.insert (0, all);
	};

	this.storeTPSOnLoad = function (store, r, s)
	{
		if (!s) {
			return;
		}

		// inject record 'Semua'
		var all = { id : 0, no:'0', nama : "Semua", alamat:"Semua" };

		store.insert (0, all);
	};

	this.reloadGrid = function (b)
	{
		var p = {
			dapil_id		: this.cbDapil.getValue ()
		,	kecamatan_id	: this.cbKecamatan.getValue ()
		,	kelurahan_id	: this.cbKelurahan.getValue ()
		,	tps_id			: this.cbTPS.getValue ()
		};

		this.store.getProxy ().extraParams = p;
		this.store.load ();

		this.sRekap.getProxy ().extraParams = p;
		this.sRekap.load ();
	};

	this.sRekapLoaded = function (store, records, s)
	{
		if (!s) {
			return;
		}
		if (records.length > 0) {
			this.formRekap.loadRecord (records[0]);
		} else {
			var o = {
				dapil_id		: this.cbDapil.getValue ()
			,	kecamatan_id	: this.cbKecamatan.getValue ()
			,	kelurahan_id	: this.cbKelurahan.getValue ()
			,	tps_id			: this.cbTPS.getValue ()
			,	kode_saksi		: '-'
			,	jumlah			: 0
			,	rusak			: 0
			,	sisa			: 0
			,	sah				: 0
			,	tidak_sah		: 0
			,	jumlah_tps		: 0
			};

			this.sRekap.add (o);
			this.formRekap.loadRecord (o);
		}

		this.onload = false;
	};

	this.bReload.on ("click", this.reloadGrid, this);

	this.sDapil.on ("load", this.storeWilOnLoad, this);
	this.sKecamatan.on ("load", this.storeWilOnLoad, this);
	this.sKelurahan.on ("load", this.storeWilOnLoad, this);
	this.sTPS.on ("load", this.storeTPSOnLoad, this);

	this.cbDapil.on ("select", this.cbDapilOnSelect, this);
	this.cbKecamatan.on ("select", this.cbKecamatanOnSelect, this);
	this.cbKelurahan.on ("select", this.cbKelurahanOnSelect, this);

	this.sRekap.on("load", this.sRekapLoaded, this);
}

var Tabulasi_Caleg_DPR = new JxTabulasi_Caleg_DPR();