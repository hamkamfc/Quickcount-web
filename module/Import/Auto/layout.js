/*
 * Copyright 2014 - Mhd Sulhan
 * Authors:
 *   - mhd.sulhan (m.shulhan@gmail.com)
 */

function JxImport_Auto ()
{
	this.id		= "Import_Auto";
	this.dir	= Jx.generateModDir (this.id);

	this.store		= Ext.create ("Jx.Store", {
		singleApi	:true
	,	url			:this.dir +"/process.php"
	,	fields		:
		[
			"filename"
		,	"_when"
		]
	});

	this.bProcess	= Ext.create ("Ext.button.Button", {
		text		:"Process"
	});

	this.panel			= Ext.create ("Jx.GridPaging", {
		itemId			:this.id
	,	title			:"Auto Import"
	,	store			:this.store
	,	buttonBarList	:["refresh"]
	,	addButtons		:
		[
			this.bProcess
		]
	,	columns			:
		[{
			header		:"Filename"
		,	dataIndex	:"filename"
		,	flex		:1
		},{
			header		:"Time"
		,	dataIndex	:"_when"
		,	width		:200
		}]
	});

	this.doRefresh = function ()
	{
	};

	this.doAutoImport = function (b)
	{
		Jx.showMask ();

		Ext.Ajax.request ({
			url		:this.dir +"/process.php"
		,	failure	:function (response, opts)
			{
				Jx.msg.error ("Auto import failed!");
				Jx.hideMask ();
			}
		,	success	:function (response, opts)
			{
				var r = Ext.JSON.decode (response.responseText);

				this.store.add (r.data);

				Jx.msg.info ("Auto import success!");
				Jx.hideMask ();
			}
		,	scope	:this
		});
	};

	this.bProcess.on ("click", this.doAutoImport, this);
}

var Import_Auto = new JxImport_Auto ();