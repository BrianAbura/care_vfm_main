<div class="panel">
	<div class="panel-body text-center">
		<?php 
		$profile = "img\\5.png";
		$vendor = DB::queryFirstRow('SELECT * from vendors where vendor_user_id=%s', $current_user['user_id']);
		if($vendor){
			$attachment = DB::queryFirstRow('SELECT * from vendor_attachments where vendor_id=%s AND description IN %ls', $vendor['vendor_id'], ['Logo','Profile']);
			if(!empty($attachment['document_file'])){
				$profile =  str_replace(dirname(__DIR__), '..', $attachment['document_file']);
			}
		}
		?>
	<img alt="Profile Picture" class="img-lg img-circle mar-btm" src="<?php echo $profile;?>">
		<p class="text-lg text-semibold mar-no text-main"><?php echo $current_user['first_name']." ".$current_user['last_name'];?></p>
		<p class="text-muted"><?php echo $current_user['email_address'];?></p>
	</div>
</div>