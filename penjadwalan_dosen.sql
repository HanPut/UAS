CREATE DATABASE penjadwalan_dosen;

USE penjadwalan_dosen;

-- Tabel untuk user
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'dosen') DEFAULT 'dosen'
);

-- Tabel untuk dosen (tambahkan user_id sebagai relasi)
CREATE TABLE dosen (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,  -- Kolom untuk menghubungkan dengan tabel users
    nama VARCHAR(100) NOT NULL,
    nip VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL,
    telepon VARCHAR(50) NOT NULL,
    matkul VARCHAR(50) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE  -- Relasi dengan tabel users
);

-- Tabel untuk jadwal
CREATE TABLE jadwal (
    id INT AUTO_INCREMENT PRIMARY KEY,
    dosen_id INT NOT NULL,
    tanggal DATE NOT NULL,
    mulai TIME NOT NULL,
    selesai TIME NOT NULL,
    ruangan VARCHAR(50) NOT NULL,
    FOREIGN KEY (dosen_id) REFERENCES dosen(id) ON DELETE CASCADE,
    CONSTRAINT unique_jadwal UNIQUE (dosen_id, tanggal, mulai, selesai)  -- Memastikan tidak ada jadwal tumpang tindih berdasarkan dosen, tanggal, mulai, selesai
);

