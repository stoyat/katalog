<?php 
class Model {
  static $instance; 
  
  public $ins_driver;
  
  static function get_instance () {
    if(self::$instance instanceof self ) {
        return self::$instance;
    }
    return self::$instance = new self;
  }
  // создадим обьек Model_Draiver 
  protected function __construct() {	
		$this->ins_driver = Model_Driver::get_instance();
	}
   
  public function get_catalog_brands () {
    $result = $this->ins_driver->select( 
                                        array('brand_id','brand_name','parent_id'),
                                        'brands'
                                       );
     $arr = array();   
    foreach ($result as $item){
     if($item['parent_id'] == 0) {
        //родительская
        $arr[$item['brand_id']][] = $item['brand_name'];
     }
     else { 
        //дочерняя
        $arr[$item['parent_id']]['next_lvl'][$item['brand_id']] = $item['brand_name'];
     }   
    }
    return $arr; 
    }
 
 public function get_tovar($id) {
		$result = $this->ins_driver->select(
											array('tovar_id','title','anons','img','genre','price','author'),
											'tovar',
											array('tovar_id' => $id,'publish' => 1)			
											);
 
		return $result[0];									
	}
    
    public function get_child($id) {
		$result = $this->ins_driver->select(
											array('brand_id'),
											'brands',
											array('parent_id' => $id)
									);
		if($result) {
			$row = array();
			foreach	($result as $item) {
				$row[] = $item['brand_id'];
			}
			$row[] = $id;
			
			$res = implode(",",$row);	
											
			return $res;	
		}
		else {
			return FALSE;
		}									
	}
	
    
  /** ----Админка*/  
  
	public function add_goods($id,
										$title,
										$anons,
										$img,
										$publish,
										$price,
										$author,
										$genre) {
		$result = $this->ins_driver->insert(
											'tovar',
											array('title','anons','img','brand_id','publish','price','author','genre'),
											array($title,$anons,$img,$id,$publish,$price,$author,$genre)
											);
		return $result;																		
										}
                                        
	public function get_tovar_adm($id) {
		$result = $this->ins_driver->select(
											array('tovar_id','title','img','author','genre','anons','brand_id','publish','price'),
											'tovar',
											array('tovar_id' => $id)			
											);
		return $result[0];	
	}	
	
	public function edit_goods($id,$title,$anons,$img,$publish,$price,$category,$author,$genre) {
		if($img) {
			$result = $this->ins_driver->update(
											'tovar',
											array('title','anons','img','publish','price','brand_id','author','genre'),
											array($title,$anons,$img,$publish,$price,$category,$author,$genre),
											array('tovar_id' => $id)
											);
											
		}
		else {
			$result = $this->ins_driver->update(
											'tovar',
											array('title','anons','publish','price','brand_id','author','genre'),
											array($title,$anons,$publish,$price,$category,$author,$genre),
											array('tovar_id' => $id)
											);
		}
		return $result;
	}
	
	public function delete_tovar($id) {
		$result = $this->ins_driver->delete(
											'tovar',
											array('tovar_id'=>$id)
											);
		return $result;									
	}
    
} 





?>