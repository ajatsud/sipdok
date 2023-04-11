<?php

class DatabaseTable {

	private PDO $pdo;
	private string $table;
	private array $pk;

	public function __construct(PDO $pdo, string $table, array $pk) {
		$this->pdo = $pdo;
		$this->table = $table;
		$this->pk = $pk;
	}

	public function find(array $param): array {
		$sql = 'SELECT * FROM ' . $this->table;
		$i = 0;
		foreach ($this->pk as $value) {
			$i++;
			if ($i == 1)
				$sql .= ' WHERE ' . $value . ' = :' . $value;
			else
				$sql .= '   AND ' . $value . ' = :' . $value;
		}
		try {
			$statement = $this->pdo->prepare($sql);
			$statement->execute($param);
			$result = $statement->fetch(PDO::FETCH_ASSOC);
			if ($result)
				return $result;
		} catch (PDOException $e) {
			echo $e->getMessage();
		}
		return [];
	}

	public function findAll(): array {
		$sql = 'SELECT * FROM ' . $this->table;
		try {
			$statement = $this->pdo->prepare($sql);
			$statement->execute();
			$result = $statement->fetchAll(PDO::FETCH_ASSOC);
			if ($result)
				return $result;
		} catch (PDOException $e) {
			echo $e->getMessage();
		}
		return [];
	}

	public function search(array $param): array {
		$sql = 'SELECT * FROM ' . $this->table;
		$i = 0;
		foreach ($param as $key => $value) {
			$i++;
			if ($i == 1) {
				$sql .= ' WHERE ' . $key . ' LIKE :' . $key;
			} else {
				$sql .= ' OR ' . $key . ' LIKE :' . $key;
			}
		}
		foreach ($param as $key => $value) {
			$param[$key] = '%' . $value . '%';
		}
		try {
			$statement = $this->pdo->prepare($sql);
			$statement->execute($param);
			$result = $statement->fetchAll(PDO::FETCH_ASSOC);
			if ($result)
				return $result;
		} catch (PDOException $e) {
			echo $e->getMessage();
		}
		return [];
	}

	public function insert(array $param): bool {
		$sql = 'INSERT INTO ' . $this->table . ' (';
		$i = 0;
		foreach ($param as $key => $val) {
			$i++;
			if ($i == 1)
				$sql .= $key . ',';
			else
				$sql .= ' ' . $key . ',';
		}
		$sql  = rtrim($sql, ',');
		$sql .= ') VALUES (';
		$i = 0;
		foreach ($param as $key => $val) {
			$i++;
			if ($i == 1)
				$sql .= ':' . $key . ',';
			else
				$sql .= ' :' . $key . ',';
		}
		$sql  = rtrim($sql, ',');
		$sql .= ')';
		try {
			$this->pdo->beginTransaction();
			$statement = $this->pdo->prepare($sql);
			$statement->execute($param);
			$this->pdo->commit();
		} catch (PDOException $e) {
			$this->pdo->rollBack();
			echo $e->getMessage();
			return false;
		}
		return true;
	}

	public function update(array $param): bool {
		$sql = 'UPDATE ' . $this->table;
		$i = 0;
		foreach ($param as $key => $val) {
			if (in_array($key, $this->pk))
				continue;
			$i++;
			if ($i == 1)
				$sql .= ' SET ' . $key . ' = :' . $key . ',';
			else
				$sql .= ' ' . $key . ' = :' . $key . ',';
		}
		$sql  = rtrim($sql, ',');
		$i = 0;
		foreach ($this->pk as $value) {
			$i++;
			if ($i == 1)
				$sql .= ' WHERE ' . $value . ' = :' . $value;
			else
				$sql .= '   AND ' . $value . ' = :' . $value;
		}
		try {
			$this->pdo->beginTransaction();
			$statement = $this->pdo->prepare($sql);
			$statement->execute($param);
			$this->pdo->commit();
		} catch (PDOException $e) {
			$this->pdo->rollBack();
			echo $e->getMessage();
			return false;
		}
		return true;
	}

	public function delete(array $param): bool {
		$sql = 'DELETE FROM ' . $this->table;
		$i = 0;
		foreach ($this->pk as $value) {
			$i++;
			if ($i == 1)
				$sql .= ' WHERE ' . $value . ' = :' . $value;
			else
				$sql .= '   AND ' . $value . ' = :' . $value;
		}
		try {
			$this->pdo->beginTransaction();
			$statement = $this->pdo->prepare($sql);
			$statement->execute($param);
			$this->pdo->commit();
		} catch (PDOException $e) {
			$this->pdo->rollBack();
			echo $e->getMessage();
			return false;
		}
		return true;
	}
}
