<h1><?=$offer->name;?></h1>
<ul>
	<li><?=$this->html->image("/images/{$offer->image}.jpg");?></li>
	<li><?=$offer->description;?></li>
	<li><?=$offer->availability;?></li>
	<li><?php echo date('Y-m-d H:i:s', $offer->starts->sec);?></li>
	<li><?php echo date('Y-m-d H:i:s',$offer->ends->sec);?></li>
</ul>
<ul style="list-style: none; border: 1px dashed #eee; width: 350px; padding:10px;">
	<li><?=$this->html->image("/images/{$venue->logo}.jpg")?></li>
	<li><?=$venue->name;?></li>
	<li><?=$venue->address;?></li>
</ul>