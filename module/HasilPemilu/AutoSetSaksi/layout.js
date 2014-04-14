/*
 * Copyright 2014 - Mhd Sulhan
 * Authors:
 *   - mhd.sulhan (m.shulhan@gmail.com)
 */

function JxHasilPemilu_AutoSetSaksi ()
{
	this.id		= "HasilPemilu_AutoSetSaksi";
	this.dir	= Jx.generateModDir(this.id);

	this.store		= Ext.create ("Jx.Store", {
			url		:this.dir
		,	singleApi:false
		,	fields	:
					[
						"tps_id"
					,	"kode_saksi"
					]
		});

	this.bProcess	= Ext.create ("Ext.button.Button",
		{
			text	:"Process"
		});

	this.panel				= Ext.create ("Jx.GridPaging", {
			title			:"Raw Data > Auto Set Saksi"
		,	itemId			:this.id
		,	closable		:true
		,	buttonBarList	:[]
		,	showSearchField	:false
		,	addButtons		:
				[
					this.bProcess
				]
		,	columns		:
				[{
					header		:"Id TPS"
				,	dataIndex	:"tps_id"
				},{
					header		:"Kode Saksi"
				,	dataIndex	:"kode_saksi"
				}]
		});

	this.doRefresh = function (perm)
	{
	};

	this.callProcess = function ()
	{
		Jx.showMask ();

		Ext.Ajax.request ({
			url		:this.dir +"/process.php"
		,	failure	:function (response, opts)
			{
				Jx.msg.error ("Auto process failed!");
				Jx.hideMask ();
			}
		,	success	:function (response, opts)
			{
				var r = Ext.JSON.decode (response.responseText);

				this.store.add (r.data);

				Jx.msg.info ("Auto process succeded!");
				Jx.hideMask ();
			}
		,	scope	:this
		});
	}

	this.doProcess = function (b)
	{
		var me = this;

		Jx.msg.confirmMsg ("Otomatis pengaturan saksi akan dilakukan. <br/> Lanjutkan?"
			, this.callProcess
			, this);
	}

	this.bProcess.on ("click", this.doProcess, this);
}

var HasilPemilu_AutoSetSaksi = new JxHasilPemilu_AutoSetSaksi();