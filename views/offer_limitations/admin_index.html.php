<div id="ribbon">
	<span>Offer Limitations</span>
</div>
<div id="content-wrapper">
	<div style="margin:20px;">
		<?=$this->html->link('New limitation.', array('action'=>'add','admin'=>true));?>
		<table>
			<thead>
				<tr><th>Name</th><th>Actions</th></tr>
			</thead>
		<?php foreach($limitations as $limitation):?>
			<tr>
				<td><?=$limitation->name;?></td>
				<td>
					<?=$this->html->link('Edit', array('action'=>'edit','id'=>$limitation->_id,'admin'=>1));?>
				</td>
			</tr>
		<?php endforeach;?>
		</table>
		<div>
			<?php if($page > 1):?>
				<?=$this->html->link('< Previous', array('OfferLimitations::index', 'admin'=>true, 'page'=> $page - 1));?>
			<?php endif;?>
			<?php if($total > ($limit * $page)):?>
				<?=$this->html->link('Next >', array('OfferLimitations::index', 'admin'=>true, 'page'=> $page + 1));?>
			<?php endif;?>
		</div>
	</div>
</div>
