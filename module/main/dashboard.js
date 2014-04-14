/*
 * Copyright 2014 - Mhd Sulhan
 * Authors:
 *   - mhd.sulhan (m.shulhan@gmail.com)
 */

function JxDashboard ()
{
	this.id		= "Dashboard";
	this.dir	= Jx.generateModDir (this.id);

	this.sStatus		= Ext.create ("Jx.Store", {
			url			:Jx.generateModDir ("Dashboard_Status")
		,	singleApi	:false
		,	fields		:
			[
				"jumlah_tps"
			,	"jumlah_suara"
			]
		});

	this.vStatus	= Ext.create ("Ext.panel.Panel", {
			title	:"Status"
		,	store	:this.sStatus
		,	width	:200
		,	height	:200
		,	tpl		:new Ext.XTemplate (
					"<div> Jumlah TPS   : {jumlah_tps} </div>"
			,		"<div> Jumlah Suara : {jumlah_suara} </div>"
			)
		});

	this.panel	= Ext.create ("Ext.panel.Panel", {
			region		:"center"
		,	margin		:"5 0 0 0"
		,	padding		:"0 5 0 5"
		,	layout		:
			{
				type		:"table"
			,	column		:2
			}
		,	defaults	:
			{
				bodyStyle	: 'padding:20px'
			}
		,	hidden		:true
		,	items		:
			[
				this.vStatus
			]
		});

	this.hide = function ()
	{
		this.panel.hide ();
	}

	this.show = function ()
	{
		this.doRefresh ();
		this.panel.show ();
	};

	this.doRefresh = function ()
	{
		this.sStatus.load ();
	};

	this.updateStatus = function (s, r, success)
	{
		if (!success || r.length <= 0) {
			return;
		}

		this.vStatus.update ({
			jumlah_tps : r[0].get ("jumlah_tps")
		,	jumlah_suara : r[0].get ("jumlah_suara")
		});
	};

	this.sStatus.on ("load", this.updateStatus, this);
}