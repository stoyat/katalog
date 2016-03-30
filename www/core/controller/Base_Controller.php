<?php
abstract class Base_Controller {
   //запрос в адрессной строке
   protected $reques_url;
   //имя контролера который запрашивает пользователь
    protected $controller;
    //массив параметров (в адрессной строке)
    protected $params;
    //готовая страница
    protected $page; 
    
    //передача массива параметров
     public function route() {
		$obj = new $this->controller;
		$obj->request($this->params); 
	}
    //возврат данных по контролеру
    protected function get_controller () {
        return $this->controller;
    }
    
    //возврат массив параметров
    protected function get_params () {
        return $this->params;
    }
    
    //формирует входные данные (в классах наследниках переопределить)
    protected function input () {
        
    }
    
    //выводит готовую страницу (в классах наследниках переопределить)
    protected function output (){
        
    }
    //базовый метод
    public function request ($param=array()) {
        $this->input($param);
        $this->output();
        $this->get_page();
    }
      // вывод готовой страницы
  public function get_page ()  {
    echo $this->page;
  }
  //шаблонизатор сайта
 protected function render($path,$param = array()) {	
		extract($param);//функция которая формируем из массива перем.
		ob_start(); //функция открывает буфер обмена
        include($path.'.php');
		return ob_get_clean(); //возврат данных из буфера 
	}
 //очистка строк и массив данных
 public function clear_str ($var) {
    if(is_array($var)){
        $row=array();
        foreach ($var as $key=>$item){
         $row[$key] = trim (strip_tags($var));   
        }
        return $row;
    }
    return trim (strip_tags($var));
 }
 //очистка числовых данных 
 public function clear_int($var) {
    return (int)$var;
 }
 //проверяем прищли ли данные методом POST.
 public function is_post () {
    if ($_SERVER['REQUEST_METHOD']== 'POST') {
        return true;
    }
	return false;
 }
 }
 