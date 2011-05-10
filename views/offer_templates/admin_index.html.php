<div id="ribbon">
	<span>Offer Templates</span>
</div>
<div id="content-wrapper">
	<div style="margin:20px;">
		<?=$this->html->link('New template', array('action'=>'add','admin'=>true));?>
		<table>
			<thead>
				<tr><th>Name</th><th>Actions</th></tr>
			</thead>
		<?php foreach($templates as $template):?>
			<tr>
				<td><?=$template->name;?></td>
				<td>
					<?=$this->html->link('View', array('action'=>'view','id'=>$template->_id,'admin'=>1));?>
					<?=$this->html->link('Edit', array('action'=>'edit','id'=>$template->_id,'admin'=>1));?>
				</td>
			</tr>
		<?php endforeach;?>
		</table>
		<div>
			<?php if($page > 1):?>
				<?=$this->html->link('< Previous', array('OfferTemplates::index', 'admin'=>true, 'page'=> $page - 1));?>
			<?php endif;?>
			<?php if($total > ($limit * $page)):?>
				<?=$this->html->link('Next >', array('OfferTemplates::index', 'admin'=>true, 'page'=> $page + 1));?>
			<?php endif;?>
		</div>
	</div>
</div>
