<style>
ul#panel{
	padding-top: 30px;
	float: left;
	width: 100px;
	background-color: #fafafa; 
	padding-left: 20px;
	padding-bottom: 30px;
	list-style: none;
}
ul#panel li{
	margin: 10px;
	margin-left: 0;
}
ul#panel li a{
	font-weight: bold;
	color: #3399ff;
}
ul#panel li a:hover{
	color: #000000;
}
</style>
<div id="ribbon">
	<span>Dashboard</span>
</div>
<div id="content-wrapper">
		<ul id="panel">
			<li style="font-weight: bold; font-size: 16px;">Menu</li>
		<?php if($user->role == 'admin'):?>
			<li><?=$this->html->link('Purchases', array('Purchases::index', 'admin'=>true));?></li>
			<li><?=$this->html->link('Tickets', array('Tickets::index', 'admin'=>true));?></li>
			<li><?=$this->html->link('Venues', array('Venues::index', 'admin'=>true));?></li>
		<?php endif;?>
		<li><?=$this->html->link('Offers', array('Offers::index'));?></li>
		<li><?=$this->html->link('Logout', array('Users::logout'));?></li>
		</ul>
	<div style="width: 500px; margin-left: auto; margin-right: auto; margin-top: 30px;">
		<h2>Welcome <?=$user->name;?></h2>
	</div>
</div>