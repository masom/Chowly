<div id="ribbon">
	<span>Analytics</span>
</div>
<div id="content-wrapper">
	<div style="margin-left: 20px; margin-right: 20px; margin-top: 20px;">
		<h1>Choose one of the following:</h1>
		
		<ul style="margin-left: 20px; margin-right: 20px; margin-top: 20px;">
			<?php foreach($types as $key => $name):?>
				<li><?=$this->html->link($name, array('action'=>'view', 'class' => $key,'admin'=>true))?>
			<?php endforeach;?>
		</ul>
	</div>
</div>