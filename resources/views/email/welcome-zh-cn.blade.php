<!DOCTYPE html>
<html>
<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<title>{{Lang::get('mowork.email_verify_subject')}}</title>
</head>
<body style="font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #000000;" bgcolor="#CCCCCC">
<table align="center" border="0" cellpadding="0" cellspacing="0" style="background-color: #CCCCCC" width="100%">
  <tbody>
	<tr>
	  <td align="center" style="background-color: #CCCCCC; vertical-align: middle" valign="middle">
		<table align="center" border="0" cellpadding="0" cellspacing="0" style="background-color: #FFFFFF" width="600">
		  <tbody>
			<tr class="spacer" height="15" style="background-color:#CCCCCC;"><td>&nbsp;</td></tr>
			<tr>
			  <td align="left" height="40" style="background-color: #CCCCCC; font-size: 0; line-height: 0; vertical-align: middle" valign="middle" width="82"><a href="https://www.memyth.com" target="_blank"><img border="0" height="40" src="{{Config::get('app.imageCachePath')}}/asset/img/email-logo.gif" style="display:block; border:none; outline:none; text-decoration:none;" width="82"></a></td>
			</tr>
			<tr class="spacer" height="15" style="background-color:#CCCCCC;"><td>&nbsp;</td></tr>
			<tr>
			  <td align="center" height="50" style="background-color: #4e4e4e; font-size: 0; line-height: 0; vertical-align: middle;" valign="middle" width="600"><img border="0" height="50" src="{{Config::get('app.imageCachePath')}}/asset/img/activate-img01.gif" style="display:block; border:none; outline:none; text-decoration:none;" width="600"></td>
			</tr>
		  </tbody>
		</table>
		<table align="center" border="0" cellpadding="0" cellspacing="0" style="background-color: #FFFFFF" width="600">
		  <tbody>
			<tr>
			  <td class="spacer" height="15">&nbsp;</td>
			  <td align="left" style="line-height:22px;vertical-align:top; font-size:15px; color:#333;font-weight:normal;-webkit-text-size-adjust:none;" valign="top" width="570">
			  <table align="center" border="0" cellpadding="0" cellspacing="0" style="background-color: #FFFFFF" width="570">
			    <tbody>
				  <tr>
					<td height="196" style="vertical-align:middle;line-height:0;font-size:0;" valign="middle" width="570"><img border="0" height="196" src="{{Config::get('app.imageCachePath')}}/asset/img/activate-img02.gif" style="display:inline; border:none; outline:none; text-decoration:none;" width="570"></td>
				  </tr>
				  <tr>
					<td style="line-height:15px;vertical-align:middle; font-size:15px; color:#333;font-weight:normal;-webkit-text-size-adjust:none;" valign="middle">{{$uid}}   (Email: {{$email}})</td>
				  </tr>
				  <tr>
					<td height="80" style="vertical-align:middle;line-height:0;font-size:0;" valign="middle" width="570"><img border="0" height="80" src="{{Config::get('app.imageCachePath')}}/asset/img/activate-img03.gif" style="display:inline; border:none; outline:none; text-decoration:none;" width="570"></td>
				  </tr>
				  <tr>
					<td style="vertical-align:middle;line-height:0;font-size:0;" valign="middle" width="570"><a href="http://www.{{$domain}}/email-verify/{{$token}}/{{$uid}}" style="display:block;line-height:22px;vertical-align:top; word-break: break-all; font-size:15px; color:#125dab; -webkit-text-size-adjust:none;" target="_blank"><br>http://www.{{$domain}}/email-verify/{{$token}}/{{$uid}}<br></a></td>
				  </tr>
				  <tr>
					<td height="173" style="vertical-align:middle;line-height:0;font-size:0;" valign="middle" width="570"><img border="0" height="173" src="{{Config::get('app.imageCachePath')}}/asset/img/activate-img04.gif" style="display:inline; border:none; outline:none; text-decoration:none;" width="570"></td>
				  </tr>
				</tbody>
			  </table>
			<tr class="spacer" height="15"><td>&nbsp;</td></tr>
		  </tbody>
		</table>
		<img border="0" height="141" src="/asset/img/email-footer.gif" style="display:inline; border:none; outline:none; text-decoration:none;" width="420">
	  </td>
	</tr>
  </tbody>
</table>
</body>
</html>
