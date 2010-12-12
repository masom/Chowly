<?=$this->form->create($venue, array('type' => 'file')); ?>
	<?=$this->form->field('name'); ?>
	<?=$this->form->field('description', array('type'=>'textarea')); ?>
	<?=$this->form->field('address');?>
	<?php if (!$venue->image):?>
		<?=$this->form->field('image', array('type' => 'file')); ?>
	<?php endif; ?>
	<?php if (!$venue->logo):?>
		<?=$this->form->field('logo', array('type' => 'file')); ?>
	<?php endif; ?>
	<?=$this->form->submit('Save'); ?>
<?=$this->form->end(); ?>