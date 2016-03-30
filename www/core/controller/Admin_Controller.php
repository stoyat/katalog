<?php

class Admin_Controller extends Base_Admin {
    
	protected $option = 'view';
	protected $brands;
	protected $id;
	protected $catalog;
	protected $navigation;
	protected $message;
	protected $tovar;
	protected $tovar_id;
	protected $type_img;
	
	protected function input($param = array()) {
		parent::input();
		$this->brands = $this->ob_m->get_catalog_brands();
        
		if(isset($param['page'])) {
			$page = $this->clear_int($param['page']);
			if(!$page) {
				$page = 1;
			}
		}
		else {
			$page = 1;
		}
		
		if(isset($param['brand'])) {
			$this->id = $this->clear_int($param['brand']);
			$this->type = 'brand';
			
			$pager = new Pager(
								$page,
								'tovar',
								array('brand_id'=>$this->id),
								'tovar_id',
								'ASC',
								QUANTITY,
								QUANTITY_LINKS
								);
			if(is_object($pager)) {
				$this->catalog = $pager->get_posts();
				$this->navigation = $pager->get_navigation();
			}					
		}
		elseif(isset($param['parent'])) {
			$this->type = 'parent';
			$this->id = $this->clear_int($param['parent']);
			
			if(!$this->id) {
				return;
			}
			$ids = $this->ob_m->get_child($this->id);
			if(!$ids) {
				return;
			}
			$pager = new Pager(
								$page,
								'tovar',
								array('brand_id' => $ids),
								'tovar_id',
								'ASC',
								QUANTITY,
								QUANTITY_LINKS,
								array("IN")
								);
			if(is_object($pager)) {
				$this->catalog = $pager->get_posts();
				$this->navigation = $pager->get_navigation();
			}						
		}

		if($param['option'] == 'add') {
			$this->option = 'add';
			
			
			if($param['id']) {
				$this->id = $this->clear_int($param['id']);
			}
		}
		
		if($param['option'] == 'edit') {
			
			if($param['tovar']) {
				$this->tovar_id = $this->clear_int($param['tovar']);
				$this->tovar = $this->ob_m->get_tovar_adm($this->tovar_id);
				$this->option = 'edit';
			}
		}
		
		if($param['option'] == 'delete') {
			if($param['tovar']) {
				$this->tovar_id = $this->clear_int($param['tovar']);
				
				$result = $this->ob_m->delete_tovar($this->tovar_id);
				
				if($result === TRUE) {
						echo "Книга успешно удалена";
					}
					else {
						echo "Ошибка удаления книги";
					}
					
					if(array_key_exists('brand',$param)) {
						return;
					}
					elseif(array_key_exists('parent',$param)) {
						return;
					}
					
					exit();	
			}
		}
		
		if($this->is_post()) {
			
			$id = $this->clear_int($_POST['id']);
			$title = $_POST['title'];
			$anons = $_POST['anons'];
			$publish = $_POST['publish'];
			$price = $this->clear_int($_POST['price']);
			$author = $_POST['author'];
			$genre = $_POST['genre'];
			$category = $_POST['category'];
			
			if(!empty($title) && !empty($anons) && !empty($author) && !empty($genre) && !empty($price)) {
				
				
				if(empty($_FILES['img']['tmp_name'])) {
					$img = NOIMAGE;
				}
				else {	
				if(!empty($_FILES['img']['error'])) {
					echo "Слишком большое изображение";
					return;
					exit();
				}
				$img_types = array('jpeg' => 'image/jpeg');
				$this->type_img = array_search($_FILES['img']['type'],$img_types);
				if(!$this->type_img) {
					echo "Не правильный формат изображения";
					return;
					exit();
				}
					if($_FILES['img']['size'] > (2 * 1024* 1024)) {
					echo "Слишком большое изображение";
					return;
					exit();
					}
					if(!move_uploaded_file($_FILES['img']['tmp_name'],UPLOAD_DIR.$_FILES['img']['name'])) {
					echo "Ошибка копиррования изображения";
					return;
					exit();
				}
				$res_img = $this->img_resize(UPLOAD_DIR.$_FILES['img']['name'],$this->type_img);
				
				if($res_img !== FALSE) {
					$img = $res_img;
					unlink(UPLOAD_DIR.$_FILES['img']['name']);
				}
				else {
					echo "Ошибка изменения размера изображения";
					return;
					exit();
				}
			}
				if($this->option == 'add') {
					$result = $this->ob_m->add_goods(
										$this->id,
										$title,
										$anons,									
										$img,				
										$publish,
										$price,
										$author,
										$genre
										);
					if($result === TRUE) {
						echo "Книга успешно добавлена";
					}
					else {
						echo "Ошибка добавления книгиы";
					}
					return;
				}
				
				if($this->option = 'edit') {
					
					if($img == NOIMAGE) {
						$img = FALSE;
					}
					$result = $this->ob_m->edit_goods(
										$id,
										$title,
										$anons,
										$img,
										$publish,
										$price,
										$category,
										$author,
										$genre
										);
					if($result === TRUE) {
						echo "Изменения успешно выполнены";
					}
					else {
						echo "Ошибка изменения книги";
					}
					return	;				
				}
			}
			else {
				echo "Заполните обязательные поля";
				return;
			}
		}
	}
	
	protected function output() {
	
	$previous = '/'.$this->type.'/'.$this->id;
	
	$this->content = $this->render(VIEW.'admin/edit_catalog',array(
														'option' => $this->option,
														'brands' => $this->brands,
														'category'=>$this->id,
														'goods' => $this->catalog,
														'navigation'=>$this->navigation,
														'previous'=>$previous,
														'mes'=>$this->message,
														'tovar' => $this->tovar
														));
	$this->page = parent::output();
	return $this->page;		
	}
}
?>