<?php 
function html_mails($bodytext)
{
$strmail = ' 
<div width="100%" style="background: #f8f8f8; padding: 0px 0px; font-family:arial; line-height:28px; height:100%;  width: 100%; color: #514d6a;">
  <div style="max-width: 700px; padding:50px 0;  margin: 0px auto; font-size: 14px">
    <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; margin-bottom: 20px">
      <tbody>
        <tr>
          <td style="vertical-align: top; padding-bottom:30px;" align="center"><a href="http://www.c-infosoft.in/aimm/" target="_blank"><img src="http://c-infosoft.in/aimm/main-logo.png" alt="" style="border:none"></a> </td>
        </tr>
      </tbody>
    </table>
    <div style="padding: 40px; background: #fff;">
      <table border="0" cellpadding="0" cellspacing="0" style="width: 100%;">
        <tbody>
          <tr>
            <td>'. $bodytext .'</td>
          </tr>
        </tbody>
      </table>
    </div>
    <div style="text-align: center; font-size: 12px; color: #b2b2b5; margin-top: 20px">
      <p>You are receiving this email because of your relationship with Aimm.in. <a href="mailto:info@aimm.in?Subject=Unsubscribe Me" target="_blank">Unsubscribe</a> if you do not wish to receive newsletter. 
                    	<br>Copyright &copy; '.date('Y').' Aimm.in. All Rights Reserved. Powered by CIS Technologies
                    </p>
	  <p>
    </div>
  </div>
</div>' ;
return $strmail;

}
?>
