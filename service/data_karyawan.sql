create database if not exists data_karyawan;

use data_karyawan;

drop table karyawan;
create table karyawan (
    id_karyawan int,
    nama_karyawan varchar(255),
    primary key(id_karyawan)
);

create table absensi (
    id_absensi int AUTO_INCREMENT,
    id_karyawan int,
    nama_karyawan varchar(255),
    tanggal_masuk date,
    waktu_masuk TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    primary key(id_absensi),
    foreign key(id_karyawan) references karyawan(id_karyawan) on delete cascade on update cascade
);

create table absensi_pulang (
    id_absensi int AUTO_INCREMENT,
    id_karyawan int,
    nama_karyawan varchar(255),
    tanggal_masuk date,
    waktu_masuk TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY(id_absensi),
    Foreign Key (id_karyawan) REFERENCES karyawan(id_karyawan) on delete cascade on update cascade
);

SELECT nama_karyawan AS 'Nama Karyawan', id_karyawan AS 'ID Karyawan','Hadir Masuk' 
        AS Status, tanggal_masuk AS 'Tanggal', waktu_masuk AS Waktu 
        FROM absensi WHERE nama_karyawan LIKE '%$nama%' ORDER BY tanggal_masuk DESC;

drop table izin;
create table izin (
    id_izin int AUTO_INCREMENT,
    id_karyawan int,
    nama_karyawan varchar(255),
    jenis_izin varchar(255),
    tanggal_masuk date,
    waktu_masuk TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    primary key(id_izin),
    foreign key(id_karyawan) references karyawan(id_karyawan) on delete cascade on update cascade
);

SELECT VERSION();

drop table absensi;
drop table karyawan;
create table admin (
    id_admin int primary key,
    username_admin varchar(255),
    password_admin varchar(255),
    remember_token varchar(255) NULL
);

ALTER TABLE admin ADD remember_token VARCHAR(64) NULL;

