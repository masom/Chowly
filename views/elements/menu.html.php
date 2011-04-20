<?php $user = $this->session->read('user');?>

<div style="float:right;">
	<?php if(in_array($user['role'], array('admin','staff'))):?>
		<?=$this->html->link('Tickets', array('Tickets::index',$user['role']=>true));?>
		&nbsp;|&nbsp;
		<?=$this->html->link('Purchases', array('Purchases::index',$user['role']=>true));?>
		&nbsp;|&nbsp;
		<?=$this->html->link('Offers', array('Offers::index',$user['role']=>true));?>
		&nbsp;|&nbsp;
		<?=$this->html->link('Users', array('Users::index',$user['role']=>true));?>
		&nbsp;|&nbsp;
		<?=$this->html->link('Venues', array('Venues::index',$user['role']=>true));?>
		&nbsp;|&nbsp;
	<?php endif;?>
<?=$this->html->link('Dashboard',array('Users::dashboard'));?>
&nbsp;|&nbsp;
<?=$this->html->link('Logout',array('Users::logout'));?>
</div>