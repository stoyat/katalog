<?php

class Model_Driver {
	
	static $instance;
    
    //обьект класса mysqli
    public $ins_db;
	
	static function get_instance() {
		if(self::$instance instanceof self) {
			return self::$instance;
		}
		return self::$instance = new self;
	}
    
     public function __construct() {
        //подкл к БД
		$this->ins_db = new mysqli(HOST,USER,PASSWORD,DB_NAME);
		
		if($this->ins_db->connect_error) {
			echo "Ошибка соединения ";
		}
		// кодировка запросов
		$this->ins_db->query("SET NAMES 'UTF8'");
	}
       //запрос на выборку с БД
    public function select ( //массив параметров
                            $param,
                            //таблица с параметрами
                            $table,
                            //массив для фильтрации
                            $where=array(),
                            //сортировка
                            $order = FALSE,
                            // направление сортировки
                            $napr = "ASC",
                            //лимит
                            $limit = FALSE,
                            //оператор фильтрации
                            $operand = array('='),
                            //полнотекстовый запрос
                            $match = array()
                            ){
    $sql = "SELECT";
		
		foreach($param as $item) {
			$sql .= ' '.$item.',';
		}
		$sql = rtrim($sql,',');
		$sql .= ' '.'FROM'.' '.$table;
		
        
		if(count($where) > 0) {
		$ii = 0;
		foreach($where as $key=>$val) {
			if($ii == 0) {
				if($operand[$ii] == "IN") {
					$sql.= " WHERE ".strtolower($key)." ".$operand[$ii]."(".$val.")";
				}
				else {
					$sql .= ' '.' WHERE '.strtolower($key).' '.$operand[$ii].' '."'".$this->ins_db->real_escape_string($val)."'";
				}
			}
			$ii++;	
		}
	}
		if(count($match) > 0) {
			foreach($match as $k=>$v) {
				if(count($where) > 0) {
					$sql.= " AND MATCH (".$k.") AGAINST('".$this->ins_db->real_escape_string($v)."')";
				}
			}
		}
		if($order) {
			$sql .= ' ORDER BY '.$order." ".$napr.' ';
		}
		if($limit) {
			$sql .= " LIMIT ".$limit;
		} 
		
		$result = $this->ins_db->query($sql);
		
		if(!$result) {
			echo "Ошибка запроса";
		}
		
		if($result->num_rows == 0) {
			return FALSE;
		}
		//количество полей в выборке
		for($i = 0; $i < $result->num_rows; $i++) {
			$row[] = $result->fetch_assoc();
		}
		return $row;					
	}
    
    /** Админка */
    //удаление с бд
    public function delete($table,$where = array(),$operand = array('=')) {
        
		/** по такому шаблону создать выборку
		$sql = "DELETE FROM brands WHERE brand_id=2";
        $where = array('brand_id''=>2) передаем параметр.
        */
		
		$sql = "DELETE FROM ".$table;
        //проверим есть  в where масиив
		if(is_array($where)) {
		  //счетчик кол-ва итераций
			$i = 0;
			foreach($where as $k=>$v) {
				$sql .= ' WHERE '.$k.$operand[$i]."'".$v."'";
				$i++;
				// можно убрать эту часть
				if((count($operand) -1) < $i) {
					$operand[$i] = $operand[$i-1];
				}
			}	
		}
		$result = $this->ins_db->query($sql);
		if(!$result) {
			echo "Ошибка базы данных";
			return FALSE;
		}
		return TRUE;;
        
	}
    
    //вставка данных в таблицу
    public function insert($table, $data = array(),$values = arraY(),$id = FALSE) {
        
		/** $sql = "INSERT INTO brands (brand_name,parent_id) VALUES ('TEST','0')";
         * $data = array(brand_name,parent_id)
         * $values = arraY('TEST','0')
		*/
        
		$sql = "INSERT INTO ".$table." (";
		//разобьем строку data
		$sql .= implode(",",$data).") ";
		$sql .= "VALUES (";
		foreach($values as $val) {
			$sql .= "'".$val."'".",";
		}
		// в конце строки уберем , поставим )
		$sql = rtrim($sql,',').")";
		$result = $this->ins_db->query($sql);
		
		if(!$result) {
			echo "Ошибка базы данных";
			return FALSE;
		}
		// передавал ли пользователь ид и вернем ид записи которой вставили
		if($id) {
			return $this->ins_db->insert_id;
		}
		return TRUE;
        
	}
    
 public function update($table,$data = array(),$values = array(),$where = array()) {
		/** $sql = "UPDATE brands SET brand_name='TES1',parent_id='1' WHERE brand_id = 11";*/
        // в массиве date и value одинаковое колво значений, сольем все в 1 массив..
		$data_res = array_combine($data,$values);
		
		$sql = "UPDATE ".$table." SET ";
		
		foreach($data_res as $key=>$val) {
			$sql .= $key."='".$val."',";
		}
		
		$sql = rtrim($sql,','); 
		
		foreach($where as $k=>$v) {
			$sql .= " WHERE ".$k."="."'".$v."'";
		}
		$result = $this->ins_db->query($sql);
		
		if(!$result) {
			echo "Ошибка базы данных";
			return FALSE;
		}
		return TRUE;
	}  
  }
  ?>