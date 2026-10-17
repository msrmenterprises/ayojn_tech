<div style="width: 100%; display:block;">
<h2>Welcome to Ayojn</h2>
<p>
	<?php $url = url('verify/'.$user->email_verification_code); ?>
	<strong>Hello,</strong><br>
	Thank you for signing up. Please <a href="{{ $url }}">Verify Email</a>  address to confirm your identity.  If you are not able to click here then copy this below url and paste in browser {{ url('verify/'.$user->email_verification_code) }}<br>
	Once verified by you we will then conduct a validation exercise to confirm your inputs. Upon approval of validation you will then have the access for Ayojn. 
	<br>
	<br>
	See you at Ayojn.
	<br>
	Global Support Team
	<br>
	<strong>Ayojn</strong>
</p>
</div>