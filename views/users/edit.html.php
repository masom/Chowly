<h1>Profile Modifications</h1>
<?=$this->form->create($user);?>
	<?=$this->form->field('name');?>
	<?=$this->form->submit('Update');?>
<?=$this->form->end();?>