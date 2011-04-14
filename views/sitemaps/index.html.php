<div id="ribbon">
	<span>Sitemap</span>
</div>
<div id="content-wrapper">
	<div class="sitemap" style="margin-left: 10px; margin-right: 10px; margin-top: 30px; margin-bottom: 30px;">
		<ul>
			<li><?=$this->html->link("Main Page", "/");?>
				<ul>
					<?php foreach($sitemap as $controller => $collections):?>
					<li>
						<?=$this->html->link($controller, "{$controller}::index");?>
							<ul>
								<?php foreach($collections as $collection): 
									foreach($collection as $item): ?>
								<li>
									<?=$this->html->link($item->name, array("{$controller}::view", "id"=>$item->_id));?>
								</li>
								<?php endforeach; endforeach;?>	
							</ul>
					</li>
					<?php endforeach;?>
				</ul>
			</li>
			<li><?=$this->html->link('How It Works', array('Pages::view','args'=>'howitworks'));?></li>
			<li><?=$this->html->link('Your Restaurant Here', array('Tickets::add','args'=>'restaurant'));?></li>
			<li><?=$this->html->link('About Us', array('Pages::view','args'=>'about'));?></li>
			<li><?=$this->html->link('Contact Us', array('Tickets::add'));?></li>
			<li><?=$this->html->link('FAQ', array('Pages::view','args'=>'faq'));?></li>
			<li><?=$this->html->link('Our Promise To You', array('Pages::view','args'=>'guarantee'));?></li>
			<li><?=$this->html->link('Privacy Policy', array('Pages::view','args'=>'privacy'));?></li>
			<li><?=$this->html->link('Terms Of Service', array('Pages::view','args'=>'terms'));?></li>

		</ul>
	</div>
</div>