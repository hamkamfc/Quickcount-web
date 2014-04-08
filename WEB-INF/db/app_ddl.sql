CREATE TABLE partai
(
	id		INTEGER PRIMARY KEY NOT NULL
,	nama	TEXT NOT NULL
);

CREATE TABLE kelurahan
(
    id				INTEGER NOT NULL
,	nama			TEXT NOT NULL
,	kelurahan_id	INTEGER
,	kotamadya_id	INTEGER
,	kodepos			INTEGER
);

CREATE TABLE kecamatan
(
	id			INTEGER	NOT NULL
,	nama		TEXT	NOT NULL
,	dapil_id	INTEGER	NOT NULL
);

CREATE TABLE dapil
(
	id		INTEGER
,	nama	TEXT
);

CREATE TABLE kotamadya
(
	id		INTEGER NOT NULL
,	nama	TEXT NOT NULL
);

CREATE TABLE users
(
	id				INTEGER NOT NULL
,	nama			TEXT NOT NULL
,	password		TEXT NOT NULL
,	dapil_id		INTEGER
,	kecamatan_id	INTEGER
,	kelurahan_id	INTEGER
);

CREATE TABLE saksi
(
	id				INTEGER PRIMARY KEY
,	kode			TEXT
,	no_tps			TEXT
,	kelurahan_id	INTEGER
);

CREATE TABLE tps
(
	id				INTEGER	primary key
,	no				text
,	nama			text
,	alamat			text
,	kelurahan_id	INTEGER
);

CREATE TABLE caleg_dpr
(
	id			NUMERIC		not null primary key
,	dapil_id	NUMERIC		not null
,	partai_id	NUMERIC		not null
,	no_urut		NUMERIC		not null
,	nama		VARCHAR(64)	not null
);

CREATE TABLE caleg_dprd
(
	id			integer primary key
,	dapil_id	integer
,	partai_id	integer
,	no_urut		integer
,	nama		varchar(128)
);

CREATE TABLE hasil_dprd
(
	dapil_id		INTEGER
,	kecamatan_id	INTEGER
,	kelurahan_id	INTEGER
,	tps_id			INTEGER
,	kode_saksi		text
,	caleg_id		INTEGER
,	partai_id		INTEGER
,	hasil			INTEGER
);

CREATE TABLE hasil_dpr
(
	dapil_id		INTEGER
,	kecamatan_id	INTEGER
,	kelurahan_id	INTEGER
,	tps_id			INTEGER
,	kode_saksi		text
,	caleg_id		INTEGER
,	partai_id		INTEGER
,	hasil			INTEGER
);

CREATE TABLE rekap_suara_dpr
(
	dapil_id		INTEGER
,	kecamatan_id	INTEGER
,	kelurahan_id	INTEGER
,	tps_id			INTEGER
,	kode_saksi		text
,	jumlah			INTEGER default 0
,	rusak			INTEGER default 0
,	sisa			INTEGER default 0
,	sah				INTEGER default 0
,	tidak_sah		INTEGER default 0
);

CREATE TABLE rekap_suara_dprd
(
	dapil_id		INTEGER
,	kecamatan_id	INTEGER
,	kelurahan_id	INTEGER
,	tps_id			INTEGER
,	kode_saksi		text
,	jumlah			INTEGER default 0
,	rusak			INTEGER default 0
,	sisa			INTEGER default 0
,	sah				INTEGER default 0
,	tidak_sah		INTEGER default 0
);
