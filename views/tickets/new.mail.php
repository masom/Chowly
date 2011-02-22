A new ticket has been created.

=============================================
Email: <?=$ticket->email;?>
Zip: <?=$ticket->zip;?>
<?php if(!empty($ticket->restaurant)):?>
Restaurant Name: <?=$ticket->restaurant;?>
<?php endif;?>
<?php if(!empty($ticket->phone)):?>
Phone Number: <?=$ticket->phone;?>
<?php endif;?>
=============================================

<?=$ticket->content;?>