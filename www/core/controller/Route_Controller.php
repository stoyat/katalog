<?php 
class Route_Controller extends Base_Controller {	
 // нужен только 1 уникальный обьект класса, поэтому класс по шаблону singleton
 static $_instance;
 
 //метод создания обьекта класса
 static function get_instance () {
    if (self::$_instance instanceof self) {
        return self ::$_instance;
    }
    return self::$_instance = new self;
 }
 
 private function __construct () {
	 //нужно распарсить адрессную строку получить контроллер и его параметры
	 //получение адрессной строку без доменна (путь к скрипту)
    $zapros = $_SERVER['REQUEST_URI'];
    $path = substr($_SERVER['PHP_SELF'],0,strpos($_SERVER['PHP_SELF'],'index.php'));
    
    if ($path === SITE_URL) {
        $this->request_url = substr($zapros,strlen(SITE_URL));
			//разбиваем строку на массив
			$url = explode('/',rtrim($this->request_url,'/'));
			//в 0 -ячейке имя контроллера, в нечетных имя параметра в четных значение парам.]
			if (!empty($url[0])) {
				$this->controller = ucfirst($url[0]).'_Controller';
			}
            //контроллер по умолчанию
			else {
				$this->controller = "Index_Controller";
			}
			$count = count($url);
			if(!empty($url[1])) {
				$key = array();
				$value = array();
				for($i = 1;$i < $count; $i++) {	
					if($i%2 != 0) {
						$key[] = $url[$i];
					}
					else {
						$value[] = $url[$i];
					}
				}
				$this->params = array_combine($key,$value);
				}
			}
		}
}