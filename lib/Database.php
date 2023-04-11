<?php

class Database {

	public static function connection(): PDO {
		try {
			$pdo = new PDO('mysql:host=localhost;dbname=sipdok;charset=utf8mb4', 'root', '', [
				PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION
			]);
		} catch (PDOException $e) {
			exit($e->getMessage());
		}
		return $pdo;
	}
}
