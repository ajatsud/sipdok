<?php

// coding pass

class pendaftaran_entry_model
{
    private $pdo;
    private $record;
    private $errors;
    private $messages;

    function __construct($pdo, $record = [], $errors = [], $messages = [])
    {
        $this->pdo = $pdo;
        $this->record = $record;
        $this->errors = $errors;
        $this->messages = $messages;
    }

    function load($id)
    {
        try{

            $st = $this->pdo->prepare('
                select a.id,
                       a.id_pasien,
					   a.keluhan,
                        ( select b.nama
                            from pasien b
                           where b.id = a.id_pasien ) nama,
                        ( select b.jenkel
                            from pasien b
                           where b.id = a.id_pasien ) jenkel,
                        ( select b.umur 
                            from pasien b
                           where b.id = a.id_pasien ) umur,
                        ( select b.alamat
                            from pasien b
                           where b.id = a.id_pasien ) alamat
                  from pendaftaran a
                 where a.id = :id
            ');

            $st->execute([
                'id' => $id
            ]);

            $record = $st->fetch(PDO::FETCH_ASSOC);
            
            return new self($this->pdo, $record, $this->errors, ['Data pendaftaran ' . $record['nama'] . ' dimuat']);
        }catch(PDOException $e){
            return new self($this->pdo, $this->record, [$e->getMessage()], $this->messages);
        }
    }
	
    function get_record()
    {
        return $this->record;
    }

    function get_errors()
    {
        return $this->errors;
    }

    function get_messages()
    {
        return $this->messages;
    }

    function save($record)
    {
        $errors = $this->validate($record);
		
        if($errors)
            return new self($this->pdo, $record, $errors, $this->messages);

        if(empty($record['id']))
			return $this->insert($record);
        else
            return $this->update($record);
    }
    
    function validate($record)
    {
        $errors = [];

        if(empty($record['id_pasien']))
            $errors[] = 'ID Pasien tidak boleh kosong';
        
        if(empty($record['nama']))
            $errors[] = 'Nama tidak boleh kosong';

        if(empty($record['jenkel']))
            $errors[] = 'Jenkel harus dipilih';

        if(empty($record['umur']) || $record['umur'] == '0')
            $errors[] = 'Umur harus diisi';

        if(empty($record['alamat']))
            $errors[] = 'Alamat tidak boleh kosong';

        return $errors;
    }

    function insert($record)
    {
        $record['id'] = $this->auto_id();
        $current_dt   = date('Y-m-d');

        try{
			
            $this->pdo->beginTransaction();
            
            $st_insert_pendaftaran = $this->pdo->prepare('
                insert into pendaftaran (
                    id,
                    id_pasien,
                    keluhan,
                    insert_dt,
                    update_dt
                ) 
                values 
                (
                    :id, 
                    :id_pasien, 
                    :keluhan,
                    :insert_dt,
                    :update_dt
                )'
            );

            $st_insert_pendaftaran->execute([
				'id' => $record['id'],
				'id_pasien' => $record['id_pasien'],
				'keluhan' => $record['keluhan'],
                'insert_dt' => $current_dt,
                'update_dt' => $current_dt
			]);
            
            
            $st_update_pasien = $this->pdo->prepare('
                update pasien 
                   set nama = :nama, 
                       jenkel = :jenkel, 
                       umur = :umur, 
                       alamat = :alamat,
                       update_dt = :update_dt
                where  id = :id
            ');

            $st_update_pasien->execute([
				'nama' => $record['nama'],
                'jenkel' => $record['jenkel'],
                'umur' => $record['umur'],
                'alamat' => $record['alamat'],
                'update_dt' => $current_dt,
                'id' => $record['id_pasien']
			]);
             
            $this->pdo->commit();
			
            return new self($this->pdo, $record, $this->errors, ['Data pendaftaran ' . $record['nama'] . ' berhasil ditambahkan']);
        }catch(PDOException $e){
            $this->pdo->rollBack();
            return new self($this->pdo, $record, [$e->getMessage()], $this->messages);
        }
    }

    function auto_id()
    {	
		// RG2303300001
        
        $prefix = 'RG' . date('ymd');
		$number = 0;
		
		try{
		    
			$st = $this->pdo->prepare('
                select max(substring(id, 9, 4)) as total 
                  from pendaftaran 
                 where substring(id, 1, 8) = :prefix'
            );

			$st->execute([
                'prefix' => $prefix
            ]);
			
            $rs = $st->fetch(PDO::FETCH_ASSOC);
            
			$number = $rs['total'] ? $rs['total'] : 0;
			
		}catch(PDOException $e){
			$this->errors[] = $e->getMessage();
		}
		
        $number++;
		$auto_id = $prefix . sprintf("%04s", $number);

		return $auto_id;
    }
	
	function update($record)
	{
        $current_dt = date('Y-m-d');

        try{
        
            $this->pdo->beginTransaction();

            $st_update_pendaftaran = $this->pdo->prepare('
                update pendaftaran 
                   set id_pasien = :id_pasien, 
                       keluhan = :keluhan,
                       update_dt = :update_dt
                 where id = :id
            ');

            $st_update_pendaftaran->execute([
				'id_pasien' => $record['id_pasien'],
				'keluhan' => $record['keluhan'],
                'update_dt' => $current_dt,
				'id' => $record['id']
			]);

            
            $st_update_pasien = $this->pdo->prepare('
                update pasien 
                   set nama = :nama, 
                       jenkel = :jenkel, 
                       umur = :umur, 
                       alamat = :alamat,
                       update_dt = :update_dt
                 where id = :id
            ');

            $st_update_pasien->execute([
				'nama' => $record['nama'],
                'jenkel' => $record['jenkel'],
                'umur' => $record['umur'],
                'alamat' => $record['alamat'],
                'update_dt' => $current_dt,
                'id' => $record['id_pasien']
			]);

            $this->pdo->commit();
            
            return new self($this->pdo, $record, $this->errors, ['Data pendaftaran ' . $record['nama'] . ' berhasil diperbarui']);
        }catch(PDOException $e){
            $this->pdo->rollBack();
            return new self($this->pdo, $record, [$e->getMessage()], $this->messages);
        }
    }
}
