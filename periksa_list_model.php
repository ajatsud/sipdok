<?php

// coding pass

class periksa_list_model
{
	private $pdo;
	private $keyword;
	private $jenkel;
	private $period;
	private $errors;
    private $messages;
	
	function __construct($pdo, $keyword = '', $jenkel = '%', $period = '', $errors = [], $messages = [])
	{
		$this->pdo = $pdo;
		$this->keyword = $keyword;
		$this->jenkel = $jenkel;
		
	    if(empty($period))
	        $this->period = date('d');
	    else
	        $this->period = $period;
	
		$this->errors = $errors;
        $this->messages = $messages;
	}
	
	function search($keyword, $jenkel, $period)
	{
		return new self($this->pdo, $keyword, $jenkel, $period, $this->errors, $this->messages);
	}
	
	function get_keyword()
	{
		return $this->keyword;
	}
    
	function get_jenkel()
	{
		return $this->jenkel;
	}
	
	function get_period()
	{
		return $this->period;
	}
	
    function get_errors()
    {
        return $this->errors;
    }

    function get_messages()
    {
        return $this->messages;
    }
    
	function get_records()
	{    
		$param = [];
		$sql   = '
		    select a.id,
		           a.id_pendaftaran,
		           
		           ( select p.id
		               from pasien p
		              where p.id = ( select b.id_pasien
		                               from pendaftaran b
		                              where b.id = a.id_pendaftaran
		                            )
		           ) id_pasien,
		           
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
		           
		      from periksa a';


		// PS2303310001

	   	$where = ' where  a.insert_dt between :from_dt and :to_dt';

		if(strlen($this->period) == 2){ // dd hari ini
			
			$from_dt = date('Y-m-d');
			$to_dt   = date('Y-m-d');

		}elseif(strlen($this->period) == 4){ // mmdd bulan ini

			$from_dt = date('Y-m') . '-01';
			$to_dt   = date('Y-m-t'); // tanggal terakhir

		}elseif(strlen($this->period) == 6){ // yyyymmdd tahun ini

			$from_dt = date('Y') . '-01-01';
			$to_dt   = date('Y-m-d'); // sampai hari ini aja

		}else{

			try{

				$st = $this->pdo->prepare('
					select min(insert_dt) as min_dt,
					       max(insert_dt) as max_dt
					  from pendaftaran
				');

				$st->execute();
				$rs = $st->fetch(PDO::FETCH_ASSOC);

				$from_dt = $rs['min_dt'];
				$to_dt   = $rs['max_dt'];

			}catch(PDOException $e){

				// set today when error

				$from_dt = date('Y-m-d');
				$to_dt   = date('Y-m-d');

				$this->errors[] = $e->getMessage();
			}
		}

		$param['from_dt'] = $from_dt;
		$param['to_dt']   = $to_dt;
		
		$where .= ' and ( select p.jenkel
		                    from pasien p
		                   where p.id = ( select b.id_pasien
		                                    from pendaftaran b
		                                   where b.id = a.id_pendaftaran
		                                )
		                ) like :jenkel';
		                

		if($this->jenkel == '%')
			$param['jenkel'] = $this->jenkel;
		else
			$param['jenkel'] = '%' . $this->jenkel . '%';
		

		if($this->keyword){
		    
			$where .= ' and (
				((select p.nama from pasien p where p.id = ( select b.id_pasien from pendaftaran b where b.id = a.id_pendaftaran)) like :keyword ) or
				((select p.nama from pasien p where p.id = ( select b.id_pasien from pendaftaran b where b.id = a.id_pendaftaran)) like :keyword )
			)';

			$param['keyword'] = '%' . $this->keyword . '%';
		}
		
		$where .= ' order by insert_dt desc';
		
		try{    

			$st = $this->pdo->prepare($sql . $where);
            
            if($param)
				$st->execute($param);
			else
				$st->execute();
			
			return $st->fetchAll(PDO::FETCH_ASSOC);	
			
		}catch(PDOException $e){
			$this->errors[] = $e->getMessage();
		}
		
		return [];
	}
	
	function delete($id)
	{
		$st = $this->pdo->prepare('select count(*) as total from periksa where id = :id');
		$st->execute(['id' => $id]);
		
		$rs = $st->fetch(PDO::FETCH_ASSOC);
		
		if($rs['total'] == 0)
            return new self($this->pdo, $this->keyword, $this->jenkel, $this->period, ['Data periksa dengan ID ' . $id . ' tidak ditemukan'], $this->messages);
		
        try{
            
            $st = $this->pdo->prepare('delete from periksa where id = :id');
            $st->execute(['id' => $id]);
            
            return new self($this->pdo, $this->keyword, $this->jenkel, $this->period, $this->errors, ['Data periksa dengan ID ' . $id . ' berhasil dihapus']);
        }catch (PDOException $e){
            return new self($this->pdo, $this->keyword, $this->jenkel, $this->period, [$e->getMessage()], $this->messages);
        }
    }
}
