<?php

class Pager {
	//номер текущей страницы
	protected $page;
	protected $tablename;
	protected $where;
	protected $order;
	protected $napr;
	protected $operand;
	protected $match;
	//кол-во записей для вывода на 1 страницу
	protected $post_number;
	//кол-во ссылок по обе тороны от текущей стр. 
	protected $number_link;
	//обьект класса MD
	protected $db;
	protected $total_count;
	
	public function __construct(
								$page,
								$tablename,
								$where = array(),
								$order = '',
								$napr = '',
								$post_number,
								$number_link,
								$operand  = "=",
								$match = array()
								) {
		$this->page = $page;
		$this->tablename = $tablename;	
		$this->where = $where;	
		$this->order = $order;
		$this->napr = $napr;
		$this->post_number = $post_number;	
		$this->number_link = $number_link;	
		$this->operand = $operand;
		$this->match = $match;
		
		
		$this->db = Model_Driver::get_instance();									
	}
	//общее количество данных в бд
	public function get_total() {
		if(!$this->total_count) {
		
			$result = $this->db->select(
										array("COUNT(*) as count"),
										$this->tablename,
										$this->where,
										$this->order,
										$this->napr,
										FALSE,
										$this->operand,
										$this->match
										);
  
			$this->total_count = $result[0]['count'];	
		}
		
		return $this->total_count;		
								
	}
	// возврат массива данных для вывода
	public function get_posts() {
		$total_post = $this->get_total();
		
		$number_pages = (int)($total_post/$this->post_number);
        
		if(($total_post%$this->post_number) != 0) {
			$number_pages++;
		}
		if($this->page <= 0 || $this->page > $number_pages) {
			return FALSE;
		}
		$start = ($this->page-1)*$this->post_number;
		
		$result = $this->db->select(
									array('*'),
									$this->tablename,
									$this->where,
									$this->order,
									$this->napr,
									$start.','.$this->post_number,
									$this->operand,
									$this->match
									);
		
		return $result;
	}
	//формирования массива навигации
	public function get_navigation() {
		$total_post = $this->get_total();
		
		$number_pages = (int)($total_post/$this->post_number);
		
		if(($total_post%$this->post_number) != 0) {
			$number_pages++;
		}
		
		if($total_post < $this->post_number || $this->page > $number_pages) {
			return FALSE;
		}
		
		$result = array();
		
		if($this->page != 1) {
		  //перавая - надпись--------
			
			$result['last_page'] = $this->page - 1;
		}
        //первые 2 ссылки перед текущей
		if($this->page > $this->number_link + 1) {
			for($i = $this->page-$this->number_link;$i < $this->page; $i++) {
				$result['previous'][] = $i;
			}
		}
		else {
			for($i = 1; $i < $this->page;$i++) {
				$result['previous'][] = $i;
			}
		}
        
		$result['current'] = $this->page;
				
		if($this->page+$this->number_link < $number_pages) {
			for($i = $this->page+1;$i <= $this->page + $this->number_link;$i++) {
				$result['next'][] = $i;
			}
		}
		else {
			for($i = $this->page+1; $i <= $number_pages;$i++) {
				$result['next'][] = $i;
			}
		}
		
		if($this->page != $number_pages) {
			$result['next_pages'] = $this->page + 1;
            //последняя
		}
		
		return $result;
	}
    
}

?>