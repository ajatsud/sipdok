<?php

// coding pass

class pasien_popup_model
{
    private $pdo;
    private $nama;
    private $records;

    function __construct($pdo, $nama = '', $records = [])
    {
        $this->pdo = $pdo;
        $this->nama = $nama;
        $this->records = $records;
    }

    function get_nama()
    {
        return $this->nama;
    }

    function get_records()
    {
        return $this->records;
    }

    function search($nama)
    {
        try{
            
            $st = $this->pdo->prepare('
                select  id,
                        nama,
                        jenkel,
                        umur,
                        alamat
                  from  pasien 
				 where ( ( id like :keyword ) or
				         ( nama like :keyword ) or 
						 ( alamat like :keyword) ) limit 1000');
            
            $st->execute([
                'keyword' => '%' . $nama . '%'
            ]);

            $records = $st->fetchAll(PDO::FETCH_ASSOC);
            
            return new self($this->pdo, $nama, $records);
        }catch(PDOException $e){
            return new self($this->pdo, $nama, []);
        }
    }
}
