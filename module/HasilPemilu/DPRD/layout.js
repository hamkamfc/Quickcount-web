/*
 * Copyright 2014 - Mhd Sulhan
 * Authors:
 *   - mhd.sulhan (m.shulhan@gmail.com)
 */

function JxHasilPemilu_DPRD ()
{
	this.id		= "HasilPemilu_DPRD";
	this.dir	= Jx.generateModDir(this.id);

	this.sDapil		= Ext.create ("Jx.StorePaging", {
		url			:Jx.generateModDir ("Wilayah_Dapil")
	,	singleApi	:false
	,	fields		:
		[
			"id"
		,	"nama"
		]
	});

	this.cbDapil		= Ext.create ("Jx.ComboPaging", {
		store			:this.sDapil
	,	fieldLabel		:"Dapil"
	,	valueField		:"id"
	,	displayField	:"nama"
	});

	this.sKecamatan = Ext.create ("Jx.StorePaging", {
		url			:Jx.generateModDir ("Wilayah_Kecamatan")
	,	singleApi	:false
	,	fields		:
		[
			"id"
		,	"nama"
		]
	});

	this.cbKecamatan	= Ext.create ("Jx.ComboPaging", {
		store			:this.sKecamatan
	,	fieldLabel		:"Kecamatan"
	,	valueField		:"id"
	,	displayField	:"nama"
	});

	this.sKelurahan = Ext.create ("Jx.StorePaging", {
		url			:Jx.generateModDir ("Wilayah_Kelurahan")
	,	singleApi	:false
	,	fields		:
		[
			"id"
		,	"nama"
		]
	});

	this.cbKelurahan	= Ext.create ("Jx.ComboPaging", {
		store			:this.sKelurahan
	,	fieldLabel		:"Kelurahan"
	,	valueField		:"id"
	,	displayField	:"nama"
	});

	this.sTPS		= Ext.create ("Jx.StorePaging", {
		url			:Jx.generateModDir ("Wilayah_TPS")
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

	this.sSaksi		= Ext.create ("Jx.StorePaging", {
		url			:Jx.generateModDir ("Wilayah_Saksi")
	,	singleApi	:false
	,	fields		:
		[
			"id"
		,	"kode"
		]
	});

	this.cbSaksi		= Ext.create ("Jx.ComboPaging", {
		store			:this.sSaksi
	,	fieldLabel		:"Saksi"
	,	valueField		:"id"
	,	displayField	:"kode"
	,	hiddenName		:"kode"
	});

	this.cbDapilOnSelect = function (cb, r, e)
	{
		this.sKecamatan.clearFilter (true);
		this.sKecamatan.filter ("dapil_id", r[0].get("id"));
		this.sKelurahan.loadData ([]);
		this.sTPS.loadData ([]);
		this.sSaksi.loadData ([]);
	};

	this.cbKecamatanOnSelect = function (cb, r, e)
	{
		this.sKelurahan.clearFilter (true);
		this.sKelurahan.filter ("kecamatan_id", r[0].get("id"));
		this.sTPS.loadData ([]);
		this.sSaksi.loadData ([]);
	};

	this.cbKelurahanOnSelect = function (cb, r, e)
	{
		this.sTPS.clearFilter (true);
		this.sTPS.filter ("kelurahan_id", r[0].get("id"));
		this.sSaksi.loadData ([]);
	};

	this.cbTpsOnSelect = function (cb, r, e)
	{
		this.sSaksi.clearFilter (true);
		this.sSaksi.filter ("no_tps", r[0].get("no"));
	};

	this.bReload	= Ext.create ("Ext.button.Button", {
		text		:"Muat ulang"
	});

	this.form = Ext.create ("Ext.form.Panel", {
		region	:"center"
	,	defaults:
		{
			labelAlign	:"right"
		,	labelWidth	:200
		,	width		:400
		}
	,	items	:
		[
			this.cbDapil
		,	this.cbKecamatan
		,	this.cbKelurahan
		,	this.cbTPS
		,	this.cbSaksi
		]
	,	buttons	:
		[	"->"
		,	this.bReload
		,	"->"
		]
	});

	this.sHasil		= Ext.create ("Jx.Store", {
		url			:Jx.generateModDir ("HasilPemilu_DPRD")
	,	singleApi	:false
	,	remoteFilter:true
	,	groupField	:"partai_nama"
	,	fields		:
		[
			"caleg_id"
		,	"partai_id"
		,	"partai_nama"
		,	"dapil_id"
		,	"caleg_no_urut"
		,	"caleg_nama"
		,	"hasil"
		]
	});

	this.grid	= Ext.create ("Ext.grid.Panel", {
		store	:this.sHasil
	,	title	:"Hasil Pemilu"
	,	selType	:"cellmodel"
    ,	plugins	:
		[
			Ext.create('Ext.grid.plugin.CellEditing', {
				clicksToEdit: 1
			})
		]
	,	features:
		[{
			ftype			:"grouping"
		,	hideGroupHeader	:true
		}]
	,	columns	:
		[{
			header		:"Partai"
		,	dataIndex	:"partai_nama"
		,	width		:300
		,	hidden		:true
		},{
			header		:"No. Urut"
		,	dataIndex	:"caleg_no_urut"
		},{
			header		:"Nama"
		,	dataIndex	:"caleg_nama"
		,	flex		:1
		},{
			header		:"Hasil"
		,	dataIndex	:"hasil"
		,	editor		:
			{
				xtype			:"numberfield"
			,	allowDecimals	:false
			,	minValue		:0
			,	hideTrigger		:true
			}
		}]
	});

	this.sRekap		= Ext.create ("Jx.Store", {
		url			:Jx.generateModDir ("HasilPemilu_RekapDPRD")
	,	singleApi	:false
	,	remoteFilter:true
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
		]
	});

	this.formRekap = Ext.create ("Ext.form.Panel", {
		title		:"Rekap Suara"
	,	defaultType	:"numberfield"
	,	defaults	:
			{
				allowDecimals	:false
			,	hideTrigger		:true
			,	value			:0
			,	minValue		:0
			,	labelAlign		:"right"
			,	labelWidth		:200
			,	listeners	:{
					change	:{
						fn		:function (f, n, o) {
							HasilPemilu_DPRD.updateRekap();
						}
					,	scope	:this
					}
				}
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
		}]
	});

	this.panel	= Ext.create ("Ext.container.Container", {
		layout	:"border"
	,	items	:
		[
			this.form
		,{
			xtype	:"tabpanel"
		,	region	:"east"
		,	width	:"50%"
		,	split	:true
		,	items	:
			[
				this.grid
			,	this.formRekap
			]
		}]
	});

	this.reloadDetail = function (b)
	{
		var filters = [
			{property:"dapil_id"		, value:this.cbDapil.getValue ()}
		,	{property:"kecamatan_id"	, value:this.cbKecamatan.getValue ()}
		,	{property:"kelurahan_id"	, value:this.cbKelurahan.getValue ()}
		,	{property:"tps_id"			, value:this.cbTPS.getValue ()}
		,	{property:"kode_saksi"		, value:this.cbSaksi.getValue ()}
		];

		this.sHasil.clearFilter (true);
		this.sHasil.filter (filters);

		this.sRekap.clearFilter (true);
		this.sRekap.filter (filters);
	};

	this.updateHasil = function (ed, newv, oldv)
	{
		this.sHasil.getProxy ().extraParams = {
			dapil_id		: this.cbDapil.getValue ()
		,	kecamatan_id	: this.cbKecamatan.getValue ()
		,	kelurahan_id	: this.cbKelurahan.getValue ()
		,	tps_id			: this.cbTPS.getValue ()
		,	kode_saksi		: this.cbSaksi.getRawValue ()
		};
		this.sHasil.sync ();
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
			,	kode_saksi		: this.cbSaksi.getRawValue ()
			,	jumlah : 0
			,	rusak : 0
			,	sisa : 0
			,	sah : 0
			,	tidak_sah : 0
			};

			this.sRekap.add (o);
			this.formRekap.loadRecord (o);
		}
	};

	this.updateRekap = function (ed, newv, oldv)
	{
		console.log ("update rekap");

//		this.formRekap.getForm ().updateRecord ();

		this.formRekap.submit ({
			url		:this.sRekap.url +"/update.php"
		,	params	:{
				dapil_id		: this.cbDapil.getValue ()
			,	kecamatan_id	: this.cbKecamatan.getValue ()
			,	kelurahan_id	: this.cbKelurahan.getValue ()
			,	tps_id			: this.cbTPS.getValue ()
			,	kode_saksi		: this.cbSaksi.getRawValue ()
			}
		});
	};

	this.doRefresh = function (perm)
	{
		this.sDapil.load ();
	};

	this.cbDapil.on ("select", this.cbDapilOnSelect, this);
	this.cbKecamatan.on ("select", this.cbKecamatanOnSelect, this);
	this.cbKelurahan.on ("select", this.cbKelurahanOnSelect, this);
	this.cbTPS.on ("select", this.cbTpsOnSelect, this);

	this.bReload.on ("click", this.reloadDetail, this);
	this.grid.on ("edit", this.updateHasil, this);

	this.sRekap.on("load", this.sRekapLoaded, this);
}

var HasilPemilu_DPRD = new JxHasilPemilu_DPRD();