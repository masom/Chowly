<div id="ribbon">
	<span>Offers</span>
</div>
<div id="content-wrapper">
	<div style="margin:20px;">
		<?=$this->html->link("{$this->html->image('silk/add.png')} Create a new Offer", array('action'=>'add','admin'=>true),array('escape'=>false));?>
		<?=$this->html->link("{$this->html->image('silk/cog.png')} Rebuild Inventory", array('action'=>'rebuild_inventory','admin'=>true),array('escape'=>false));?>
		<table>
			<thead>
				<tr><th>Name</th><th>Starts</th><th>Ends</th><th>Actions</th></tr>
			</thead>
		<?php foreach($offers as $offer):?>
			<tr>
				<td><?=$offer->name;?></td>
				<td><?=date('Y-m-d H:i:s',$offer->starts->sec);?></td>
				<td><?=date('Y-m-d H:i:s',$offer->ends->sec);?></td>
				<td><?php echo ($offer->state == 'published')? $this->html->link('Unpublish',array('Offers::unpublish','id'=>$offer->_id,'admin'=>true)) : $this->html->link('Publish',array('Offers::publish','id'=>$offer->_id,'admin'=>true)); ?>
			</tr>
		<?php endforeach;?>
		</table>
		<div>
			<?php if($page > 1):?>
				<?=$this->html->link('< Previous', array('Offers::index', 'admin'=>true, 'page'=> $page - 1));?>
			<?php endif;?>
			<?php if($total > ($limit * $page)):?>
				<?=$this->html->link('Next >', array('Offers::index', 'admin'=>true, 'page'=> $page + 1));?>
			<?php endif;?>
		</div>
	</div>
</div>
