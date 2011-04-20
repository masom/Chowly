<div id="ribbon">
	<span>Profile Modifications</span>
</div>
<div id="content-wrapper">
	<div style="width: 500px; margin-left: auto; margin-right: auto;">
		<?=$this->form->create($user);?>
			<?=$this->form->field('name');?>
			<?=$this->form->submit('Update');?>
		<?=$this->form->end();?>
	</div>
</div>