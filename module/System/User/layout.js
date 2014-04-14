/*
	Copyright 2013 Mhd Sulhan
	Authors:
		- mhd.sulhan (m.shulhan@gmail.com)
*/

function JxSystemUser ()
{
	this.id		= "System_User";
	this.dir	= Jx.generateModDir (this.id);

	this.store	= Ext.create ("Jx.StorePaging", {
		url			:this.dir
	,	singleApi	:false
	,	fields		:
		[
			"id"
		,	"name"
		,	"realname"
		,	"password"
		,	"old_password"
		]
	});

	this.panel	= Ext.create ("Jx.GridPaging.FormEditor", {
		id			:this.id
	,	panelConfig	:
		{
			title		:"System User"
		,	closable	:true
		}
	,	store		:this.store
	,	columns		:
		[{
			header		:"ID"
		,	dataIndex	:"id"
		,	hidden		:true
		,	editor		:
			{
				xtype		:"textfield"
			,	hidden		:true
			}
		},{
			header		:"User ID"
		,	dataIndex	:"name"
		,	flex		:1
		,	editor		:
			{
				xtype		:"textfield"
			,	vtype		:"alphanum"
			,	allowBlank	:false
			}
		},{
			header		:"User name"
		,	dataIndex	:"realname"
		,	flex		:1
		,	editor		:
			{
				xtype		:"textfield"
			,	allowBlank	:false
			}
		},{
			header		:"Current Password"
		,	dataIndex	:"old_password"
		,	hidden		:true
		,	editor		:
			{
				xtype		:"textfield"
			,	vtype		:"alphanum"
			,	inputType	:"password"
			,	hidden		:"true"
			}
		},{
			header		:"Password"
		,	dataIndex	:"password"
		,	hidden		:true
		,	editor		:
			{
				xtype		:"textfield"
			,	vtype		:"alphanum"
			,	inputType	:"password"
			}
		}]
	});

	this.doRefresh	= function (perm)
	{
		this.panel.doRefresh (perm);
	}
};

/* moduleName = className */
var System_User = new JxSystemUser ();
