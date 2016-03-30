<?php
abstract class Base_Admin extends Base_Controller {
    //обьек модели
    protected $ob_m;
    protected $content;
    
    protected function input () {
        //получим обьект модели
        $this->ob_m = Model::get_instance();
        
    }
    
    protected function output () {
         $page = $this->render(VIEW.'admin/index',array(
												
												'content' => $this->content
												));
		return $page;										
    }  
}

?>