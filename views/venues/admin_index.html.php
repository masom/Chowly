<div id="ribbon">
	<span>Venues</span>
</div>
<div id="content-wrapper">
	<div style="margin:20px;">
		<?=$this->html->link("{$this->html->image('silk/add.png', array('style'=>'vertical-align: text-top;'))} Add a venue", array('Venues::add','admin'=>true), array('escape'=>false));?>
		<table>
			<thead>
				<tr><th>Name</th><th>Address</th><th>Status</th><th>Actions</th></tr>
			</thead>
		<?php foreach($venues as $venue):?>
			<tr>				<td><?=$venue->name;?></td>
				<td><?=$venue->address;?></td>
				<td><?=$venue->state;?></td>
				<td>
					<?=$this->html->link('View', array('Venues::view','id'=>$venue->_id,'admin'=>true));?>
					<?=$this->html->link('Edit', array('Venues::edit','id'=>$venue->_id,'admin'=>true));?>
				</td>
			</tr>
		<?php endforeach;?>
		
		</table>
		
		<div>
			<?php if($page > 1):?>
				<?=$this->html->link('< Previous', array('Venues::index', 'admin'=>true, 'page'=> $page - 1));?>
			<?php endif;?>
			<?php if($total > ($limit * $page)):?>
				<?=$this->html->link('Next >', array('Venues::index', 'admin'=>true, 'page'=> $page + 1));?>
			<?php endif;?>
		</div>
	</div>
</div>