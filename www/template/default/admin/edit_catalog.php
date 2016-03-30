<td class="content">
<? if($option == 'view') :?>				
						<h1>
							Редактирование каталога 
						</h1>
<p><?=$mes;?></p>
<? if($category) :?>						
<div class="button-catalog-adm">
<a href="<?=SITE_URL;?>admin/option/add/id/<?=$category;?>"><img src="<?=SITE_URL.VIEW;?>admin/images/add_produkt.jpg" alt="Добавить продукт в категорию" /></a>
</div>
<? endif;?>	
<? if($goods) :?>					
	<? foreach($goods as $item) :?>
		<div class="adm-product-cat-main">
			<div class="adm-product-cat">
				<p><?=$item['title']?></p>
				<img src="<?=SITE_URL.UPLOAD_DIR.$item['img'];?>" alt="<?=$ietm['title']?>" />
				<p>
					<a href="<?=SITE_URL;?>admin/option/edit/tovar/<?=$item['tovar_id']?>">
						Изменить
					</a>  |  
					<a href="<?=SITE_URL;?>admin/option/delete/tovar/<?=$item['tovar_id']?><?=$previous;?>">
						Удалить
					</a>
				</p>
			</div>
			<div class="adm-bord-bot"></div>
		</div>	
	<? endforeach;?>
	<div style="clear:both"></div>
					<? if($navigation) :?>
							<ul class="pager">
								<? if($navigation['last_page']) :?>
									<li>
										<a href="<?=SITE_URL;?>admin/page/<?=$navigation['last_page']?><?=$previous;?>">&lt;</a>
									</li>
								<? endif; ?>
								<? if($navigation['previous']) :?>
									<? foreach($navigation['previous'] as $val) :?>
										<li>
											<a href="<?=SITE_URL;?>admin/page/<?=$val;?><?=$previous;?>"><?=$val;?></a>
										</li>
									<? endforeach; ?>
								<? endif; ?>
							
							<? if($navigation['current']) :?>
									<li>
										<span><?=$navigation['current'];?></span>
									</li>
								<? endif; ?>
								
							<? if($navigation['next']) :?>
									<? foreach($navigation['next'] as $v) :?>
										<li>
											<a href="<?=SITE_URL;?>admin/page/<?=$v;?><?=$previous;?>"><?=$v;?></a>
										</li>
									<? endforeach; ?>
								<? endif; ?>
							<? if($navigation['next_pages']) :?>
									<li>
										<a href="<?=SITE_URL;?>admin/page/<?=$navigation['next_pages']?><?=$previous;?>">&gt;</a>
									</li>
								<? endif; ?>		
							</ul>
							<? endif;?>					
						

<? elseif($category && !$goods) :?>
		<p>Книг нет</p>				
<? else :?>
	<p>Выберите категорию</p>
<? endif;?>	

<? elseif($option == 'add') :?>
	<h1>
		Добавление новой книги
	</h1>
	<p><?=$mes;?></p>
	
	<!--Форма добавления-->
	<form enctype="multipart/form-data" action="<?=SITE_URL;?>admin/option/add/id/<?=$category?>" method="POST">
			<p><span>Название Книги: &nbsp;
			</span><input class="txt-zag" type="text" name="title"></p>
			<input type="hidden" name="MAX_FILE_SIZE" value="2097152">
			<p><span>Картинка : 
			</span><input class="txt-zag" type="file" value="" name="img">
			<p><span>Краткое описание:</span></p>
			<textarea name="anons" cols="60" rows="15"></textarea><br /><br />			
			<p><span>Автор: &nbsp;
						</span><input class="txt-zag" type="text" name="author"></p>
			<p><span>Жанр книги: &nbsp;
						</span><input class="txt-zag" type="text" name="genre"></p>
			<p>Публиковать книгу:<br />
			<input type="radio" name="publish" value="1" checked>Да
			<input type="radio" name="publish" value="0">Нет</p>
			<p><span>Цена: &nbsp;
			</span><input class="txt-zag" type="text" name="price"></p>
						
			<input type="image" src="<?=SITE_URL.VIEW;?>admin/images/save_btn.jpg" name="submit_add_cat">				
		</form>
	<!--and-->
<? elseif($option == 'edit') :?>
<h1>
		Изменение книги - <?=$tovar['title'];?>
	</h1>
	<p><?=$mes;?></p>
	
	<!--Форма Изменение книги-->
	<form enctype="multipart/form-data" action="<?=SITE_URL;?>admin/option/edit" method="POST">
			<p><span>Название: &nbsp;
			</span><input class="txt-zag" type="text" name="title" value="<?=$tovar['title'];?>"></p>
			<input type="hidden" name="id" value="<?=$tovar['tovar_id']?>">
			<input type="hidden" name="MAX_FILE_SIZE" value="2097152">
			<p><span>Картинка : 
			</span><input class="txt-zag" type="file" value="" name="img">
			<p><span>Краткое описание:</span></p>
			<textarea name="anons" cols="60" rows="15"><?=$tovar['anons']?></textarea><br /><br />
			<p><span>Автор: &nbsp;
						</span><input class="txt-zag" type="text" name="author" value="<?=$tovar['author']?>"></p>
			<p><span>Жанр книги: &nbsp;
						</span><input class="txt-zag" type="text" value="<?=$tovar['genre']?>" name="genre"></p>
			
			<p><span>Выберите категорию:</span></p>
			<? if($brands) :?>
				<select name="category">
				<? if($tovar['brand_id'] == 0) :?>
					<option selected value="0">Без категории</option>
				<? endif;?>
				
				<? foreach($brands as $key => $item) :?>
					<? if($key == $tovar['brand_id']) :?>
						<option selected value="<?=$key;?>"><?=$item[0];?></option>
					<? else :?>
						<option value="<?=$key;?>"><?=$item[0];?></option>
					<? endif;?>
					<? if($item['next_lvl']) :?>
						<? foreach($item['next_lvl'] as $k => $val) :?>
							<? if($k == $tovar['brand_id']) :?>
							<option selected value="<?=$k;?>">--<?=$val;?></option>
							<? else :?>
							<option  value="<?=$k;?>">--<?=$val;?></option>
							<? endif;?>
						<? endforeach;?>
					<? endif?>
				<? endforeach;?>
				</select>
			<? else :?>
			<p>Категорий нет</p>
			<? endif;?>
			<p>Публиковать Книгу:<br />
			<? if($tovar['publish'] === '1') :?>
				<input type="radio" name="publish" value="1" checked>Да
				<input type="radio" name="publish" value="0">Нет</p>
			<? else :?>
				<input type="radio" name="publish" value="1">Да
				<input type="radio" name="publish" value="0" checked>Нет</p>
			<? endif;?>
			<p><span>Цена: &nbsp;
			</span><input class="txt-zag" type="text" value="<?=$tovar['price']?>" name="price"></p>
						
			<input type="image" src="<?=SITE_URL.VIEW;?>admin/images/update_btn.jpg" name="submit_add_cat">
						
		</form>
	<!--and-->
<? endif;?>								
				</td>
				<td class="rightbar-adm">
					<h1>
						Книжный каталог
					</h1>
			<? if($brands) :?>
			<ul>
				<? foreach($brands as $key=>$item) :?>
					<? if($item['next_lvl']) :?>
						<li>
							<a href="<?=SITE_URL;?>admin/parent/<?=$key;?>">
								<?=$item[0];?>
							</a>
							<ul>
							<? foreach($item['next_lvl'] as $k=>$val) :?>
								<li>
									<a href="<?=SITE_URL;?>admin/brand/<?=$k?>">
										<?=$val;?>
									</a>
								</li>
							<? endforeach;?>
							</ul>
						</li>
					<? else :?>
						<li>
							<a href="<?=SITE_URL;?>admin/brand/<?=$key;?>">
								<?=$item[0];?>
							</a>
						</li>		
					<? endif;?>	
				<? endforeach;?>	
			</ul>	
			<? else :?>
				<p>Категорий нет</p>
			<? endif;?>
	