<?php

// coding pass

class periksa_entry_model
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
		           a.id_pendaftaran,
		           
		           ( select p.nama
		               from pasien p
		              where p.id = ( select b.id_pasien
		                               from pendaftaran b
		                              where b.id = a.id_pendaftaran
		                            )
		           ) nama,
		           
		           ( select p.jenkel
		               from pasien p
		              where p.id = ( select b.id_pasien
		                               from pendaftaran b
		                              where b.id = a.id_pendaftaran
		                            )
		           ) jenkel,
		           
		           ( select p.umur
		               from pasien p
		              where p.id = ( select b.id_pasien
		                               from pendaftaran b
		                              where b.id = a.id_pendaftaran
		                            )
		           ) umur,
		           
		           ( select p.alamat
		               from pasien p
		              where p.id = ( select b.id_pasien
		                               from pendaftaran b
		                              where b.id = a.id_pendaftaran
		                            )
		           ) alamat,
		           
		           a.anamnesa,
		           a.pemeriksaan,
		           a.diagnosa,
		           a.terapi,
		           a.biaya
		           
		      from periksa a where a.id = :id
		  
            ');

            $st->execute([
                'id' => $id
            ]);

            $record = $st->fetch(PDO::FETCH_ASSOC);
            
            return new self($this->pdo, $record, $this->errors, ['Data periksa ' . $record['nama'] . ' dimuat']);
        }catch(PDOException $e){
            return new self($this->pdo, $this->record, [$e->getMessage()], $this->messages);
        }
    }

	function next()
	{
		$today = date('Y-m-d');
		$id_pendaftaran = null;

		try{

			$st = $this->pdo->prepare('
				select min(a.id) as id
				  from pendaftaran a
				 where a.insert_dt = :insert_dt
				   and a.id not in (
				   					select b.id_pendaftaran
				   					  from periksa b
				   					 where b.insert_dt = :insert_dt
				   					)
			');

			$st->execute([
				'insert_dt' => $today
			]);

			$rs = $st->fetch(PDO::FETCH_ASSOC);

			$id_pendaftaran = $rs['id'];

		}catch(PDOException $e){
			return new self($this->pdo, $this->record, [$e->getMessage], $this->messages);
		}


		if($id_pendaftaran){
		    
			$st = $this->pdo->prepare('
				select a.id as id_pendaftaran,
						a.id_pasien,
						a.keluhan,
						( select b.nama from pasien b where b.id = a.id_pasien ) nama,
						( select b.jenkel from pasien b where b.id = a.id_pasien ) jenkel,
						( select b.umur from pasien b where b.id = a.id_pasien ) umur,
						( select b.alamat from pasien b where b.id = a.id_pasien ) alamat

				from pendaftaran a
				where a.id = :id
			');

			$st->execute([
				'id' => $id_pendaftaran
			]);

			$record = $st->fetch(PDO::FETCH_ASSOC);
			
			return new self($this->pdo, $record, $this->errors, $this->messages);
		}else{
		    
		    $date_register = strtotime($today);
		    $date_register_display = date('j M Y', $date_register);
		    
			return new self($this->pdo, $this->record, ['Pendaftaran tanggal '. $date_register_display .' yang belum diperiksa tidak ditemukan'], $this->messages);
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

        if(empty($record['anamnesa']))
            $errors[] = 'Anamnesa tidak boleh kosong';

        return $errors;
    }

    function insert($record)
    {
        $record['id'] = $this->auto_id();
        $current_dt   = date('Y-m-d');

        try{
            
            $this->pdo->beginTransaction();
            
            $st = $this->pdo->prepare('
                insert into periksa 
                (
                	id,
                	id_pendaftaran,
                	anamnesa,
                	pemeriksaan,
                	diagnosa,
                	terapi,
                	biaya,
                	insert_dt,
                	update_dt
                )
                values
                (
                    :id,
                    :id_pendaftaran,
                    :anamnesa,
                    :pemeriksaan,
                    :diagnosa,
                    :terapi,
                    :biaya,
                    :insert_dt,
                    :update_dt
                )
            ');

            $st->execute([
                'id' => $record['id'],
                'id_pendaftaran' => $record['id_pendaftaran'],
                'anamnesa' => $record['anamnesa'],
                'pemeriksaan' => $record['pemeriksaan'],
                'diagnosa' => $record['diagnosa'],
                'terapi' => $record['terapi'],
                'biaya' => $record['biaya'],
                'insert_dt' => $current_dt,
                'update_dt' => $current_dt
            ]);

            $this->pdo->commit();
            
            return new self($this->pdo, $record, $this->errors, ['Data pasien ' . $record['nama'] . ' berhasil ditambahkan']);
        }catch(PDOException $e){
            $this->pdo->rollBack();
            return new self($this->pdo, $record, [$e->getMessage()], $this->messages);
        }
    }

    function auto_id()
    {	
		// PS2303300001
        
        $prefix = 'RM' . date('ymd');
		$number = 0;
		
		try{
			
            $st = $this->pdo->prepare('
                select max(substring(id, 9, 4)) as total
                  from periksa 
                 where substring(id, 1, 8) = :prefix
            ');

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
            
            $st = $this->pdo->prepare('
                update periksa 
                   set id_pendaftaran = :id_pendaftaran,
                       anamnesa = :anamnesa,
                       pemeriksaan = :pemeriksaan,
                       diagnosa = :diagnosa,
                       terapi = :terapi,
                       biaya = :biaya,
                       update_dt = :update_dt
                 where id = :id
            ');

            $st->execute([
                'id_pendaftaran' => $record['id_pendaftaran'],
                'anamnesa' => $record['anamnesa'],
                'pemeriksaan' => $record['pemeriksaan'],
                'diagnosa' => $record['diagnosa'],
                'terapi' => $record['terapi'],
                'biaya' => $record['biaya'],
                'update_dt' => $current_dt,
                'id' => $record['id']
            ]);

            $this->pdo->commit();
            
            return new self($this->pdo, $record, $this->errors, ['Data pasien ' . $record['nama'] . ' berhasil diperbarui']);
        }catch(PDOException $e){
            $this->pdo->rollBack();
            return new self($this->pdo, $record, [$e->getMessage()], $this->messages);
        }
    }
}
