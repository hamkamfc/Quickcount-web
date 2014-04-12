create table imported
(
	type		integer -- 1 : Hasil DPR, 2: Hasil DPRD, 3: Rekap DPR, 4: Rekap DPRD
,	filename	varchar(256)
,	_when		timestamp
);

insert into jaring._menu (
	id	,pid	,type	,label		,icon	,image	,module		, description
) values (
	10	,0		,1		,'Import'	,'menu'	,''		,'Import'	,''
);
insert into jaring._group_menu (_group_id, _menu_id, permission) values (1		,10		,4);

insert into jaring._menu (
	id	,pid	,type	,label					,icon	,image	,module				, description
) values (
	11	,10		,1		,'Hasil Pemilu DPR'		,'menu'	,''		,'Import_HasilDPR'	,''
);
insert into jaring._group_menu (_group_id, _menu_id, permission) values (1		,11		,4);

insert into jaring._menu (
	id	,pid	,type	,label					,icon	,image	,module				, description
) values (
	12	,10		,1		,'Hasil Pemilu DPRD'	,'menu'	,''		,'Import_HasilDPRD'	,''
);
insert into jaring._group_menu (_group_id, _menu_id, permission) values (1		,12		,4);

insert into jaring._menu (
	id	,pid	,type	,label				,icon	,image	,module				, description
) values (
	13	,10		,1		,'Surat Suara DPR'	,'menu'	,''		,'Import_SuratSuaraDPR'	,''
);
insert into jaring._group_menu (_group_id, _menu_id, permission) values (1		,13		,4);

insert into jaring._menu (
	id	,pid	,type	,label				,icon	,image	,module				, description
) values (
	14	,10		,1		,'Surat Suara DPRD'	,'menu'	,''		,'Import_SuratSuaraDPRD'	,''
);
insert into jaring._group_menu (_group_id, _menu_id, permission) values (1		,14		,4);

insert into jaring._menu (
	id	,pid	,type	,label			,icon	,image	,module				, description
) values (
	15	,10		,1		,'Auto Import'	,'menu'	,''		,'Import_Auto'	,''
);
insert into jaring._group_menu (_group_id, _menu_id, permission) values (1		,15		,4);

--
-- Hasil Pemilu
--

insert into jaring._menu (
	id	,pid	,type	,label			,icon	,image	,module				, description
) values (
	20	,0		,1		,'Raw Data'		,'menu'	,''		,'HasilPemilu'		,''
);
insert into jaring._group_menu (_group_id, _menu_id, permission) values (1		,20		,4);

insert into jaring._menu (
	id	,pid	,type	,label		,icon	,image	,module				, description
) values (
	21	,20		,1		,'DPR'		,'menu'	,''		,'HasilPemilu_DPR'		,''
);
insert into jaring._group_menu (_group_id, _menu_id, permission) values (1		,21		,4);

insert into jaring._menu (
	id	,pid	,type	,label		,icon	,image	,module				, description
) values (
	22	,20		,1		,'DPRD'		,'menu'	,''		,'HasilPemilu_DPRD'		,''
);
insert into jaring._group_menu (_group_id, _menu_id, permission) values (1		,22		,4);

drop table saksi_default;

create table saksi_default (
	type			integer
,	dapil_id		integer
,	kecamatan_id	integer
,	kelurahan_id	integer
,	tps_id			integer
,	kode_saksi		varchar(64)
);

insert into jaring._menu (
	id	,pid	,type	,label		,icon	,image	,module				, description
) values (
	30	,0		,1		,'Validasi'	,'menu'	,''		,'Evaluasi'			,''
);
insert into jaring._group_menu (_group_id, _menu_id, permission) values (1		,30		,4);

insert into jaring._menu (
	id	,pid	,type	,label			,icon	,image	,module					, description
) values (
	31	,30		,1		,'TPS - Saksi'	,'menu'	,''		,'Evaluasi_TpsSaksi'	,''
);
insert into jaring._group_menu (_group_id, _menu_id, permission) values (1		,31		,4);

insert into jaring._menu (
	id	,pid	,type	,label			,icon	,image	,module					, description
) values (
	32	,31		,1		,'TPS - Saksi - DPR'	,'menu'	,''		,'Evaluasi_TpsSaksi_DPR'	,''
);
insert into jaring._group_menu (_group_id, _menu_id, permission) values (1 ,32	,4);

insert into jaring._menu (
	id	,pid	,type	,label			,icon	,image	,module					, description
) values (
	33	,31		,1		,'TPS - Saksi - DPRD'	,'menu'	,''		,'Evaluasi_TpsSaksi_DPRD'	,''
);
insert into jaring._group_menu (_group_id, _menu_id, permission) values (1 ,33	,4);

insert into jaring._menu (
	id	,pid	,type	,label			,icon	,image	,module						, description
) values (
	34	,30		,1		,'Surat Suara DPR'	,'menu'	,''		,'Evaluasi_SuratSuara_DPR'	,''
);
insert into jaring._group_menu (_group_id, _menu_id, permission) values (1 ,34	,4);

insert into jaring._menu (
	id	,pid	,type	,label			,icon	,image	,module						, description
) values (
	35	,30		,1		,'Surat Suara DPRD'	,'menu'	,''		,'Evaluasi_SuratSuara_DPRD'	,''
);
insert into jaring._group_menu (_group_id, _menu_id, permission) values (1 ,35	,4);

insert into jaring._menu (
	id	,pid	,type	,label			,icon	,image	,module					, description
) values (
	40	,0		,1		,'Tabulasi'		,'menu'	,''		,'Tabulasi'	,''
);
insert into jaring._group_menu (_group_id, _menu_id, permission) values (1 ,40	,4);

insert into jaring._menu (
	id	,pid	,type	,label					,icon	,image	,module		, description
) values (
	41	,40		,0		,'Per Partai - DPR'		,'menu'	,''		,'Tabulasi_Partai_DPR'	,''
);
insert into jaring._group_menu (_group_id, _menu_id, permission) values (1 ,41	,4);

insert into jaring._menu (
	id	,pid	,type	,label					,icon	,image	,module		, description
) values (
	42	,40		,0		,'Per Partai - DPRD'		,'menu'	,''		,'Tabulasi_Partai_DPRD'	,''
);
insert into jaring._group_menu (_group_id, _menu_id, permission) values (1 ,42	,4);

insert into jaring._menu (
	id	,pid	,type	,label		,icon	,image	,module		, description
) values (
	43	,40		,1		,'DPR'		,'menu'	,''		,'Tabulasi_Caleg_DPR'	,''
);
insert into jaring._group_menu (_group_id, _menu_id, permission) values (1 ,43	,4);

insert into jaring._menu (
	id	,pid	,type	,label		,icon	,image	,module		, description
) values (
	44	,40		,1		,'DPRD'		,'menu'	,''		,'Tabulasi_Caleg_DPRD'	,''
);
insert into jaring._group_menu (_group_id, _menu_id, permission) values (1 ,44	,4);


CREATE TABLE `jaring`.`caleg` (
	`id`	INT NOT NULL AUTO_INCREMENT
,	`nama`	VARCHAR(128) NULL
,	PRIMARY KEY (`id`)
);