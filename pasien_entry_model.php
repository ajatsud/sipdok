<?php

// coding pass

class pasien_entry_model
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
        try {
    
            $st = $this->pdo->prepare('
                select id,
                       nama,
                       jenkel,
                       umur,
                       alamat
                  from pasien 
                 where id = :id
            ');

            $st->execute([
                'id' => $id
            ]);

            $record = $st->fetch(PDO::FETCH_ASSOC);
            
            return new self($this->pdo, $record, $this->errors, ['Data pasien ' . $record['nama'] . ' dimuat']);
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
            
            $st = $this->pdo->prepare('
                insert into pasien (
                    id,
                    nama,
                    jenkel,
                    umur,
                    alamat,
                    insert_dt,
                    update_dt
                ) 
                values 
                (
                    :id,
                    :nama,
                    :jenkel,
                    :umur,
                    :alamat,
                    :insert_dt,
                    :update_dt
                )
            ');

            $st->execute([
                'id' => $record['id'],
                'nama' => $record['nama'],
                'jenkel' => $record['jenkel'],
                'umur' => $record['umur'],
                'alamat' => $record['alamat'],
                'insert_dt' => $current_dt,
                'update_dt' => $current_dt
            ]);

            $this->pdo->commit();
            
            return new self($this->pdo, $record, $this->errors, ['Data pasien ' . $record['nama'] . ' berhasil ditambahkan']);
        }catch (PDOException $e){
            $this->pdo->rollBack();
            return new self($this->pdo, $record, [$e->getMessage()], $this->messages);
        }
    }

    function auto_id()
    {	
		// PS2303300001
        
        $prefix = 'PS' . date('ymd');
		$number = 0;
		
		try{
			
            $st = $this->pdo->prepare('
                select max(substring(id, 9, 4)) as total
                  from pasien 
                 where substring(id, 1, 8) = :prefix
            ');

			$st->execute([
                'prefix' => $prefix
            ]);

			$rs = $st->fetch(PDO::FETCH_ASSOC);
			
			$number = $rs['total'] ? $rs['total'] : 0;
			
		}catch (PDOException $e){
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
            
            $st = $this->pdo->prepare('
                update pasien 
                   set nama = :nama,
                       jenkel = :jenkel,
                       umur = :umur,
                       alamat = :alamat,
                       update_dt = :update_dt
                 where id = :id
            ');

            $st->execute([
                'nama' => $record['nama'],
                'jenkel' => $record['jenkel'],
                'umur' => $record['umur'],
                'alamat' => $record['alamat'],
                'update_dt' => $current_dt,
                'id' => $record['id']
            ]);

            $this->pdo->commit();
            
            return new self($this->pdo, $record, $this->errors, ['Data pasien ' . $record['nama'] . ' berhasil diperbarui']);
        }catch (PDOException $e){
            $this->pdo->rollBack();
            return new self($this->pdo, $record, [$e->getMessage()], $this->messages);
        }
    }
}
