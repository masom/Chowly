<?php foreach($sitemap as $controller => $collections):?>
	<url>
		<loc><?=$this->url(array("{$controller}::index"), array('absolute'=>true));?></loc>
		<lastmod><?=date('Y-m-d\TH:i:sP');?></lastmod>
		<changefreq>daily</changefreq>
		<priority>0.8</priority>
	</url>
	<?php foreach($collections as $collection): 
		foreach($collection as $item): ?>
	<url>
		<loc><?=$this->url(array("{$controller}::view", "id"=>$item->_id),array('absolute'=>true));?></loc>
		<lastmod><?=date('Y-m-d\TH:i:sP', $item->created->sec)?></lastmod>
	</url>
	<?php endforeach; endforeach;?>
<?php endforeach;?>