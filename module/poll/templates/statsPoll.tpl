{config_load file=$smarty.session.config}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>
{literal}
<style type="text/css">
	html,body{
		font-family:Arial, Helvetica, sans-serif;
		font-size:12px;
		background:url(/images/donganh_images/background/bg-pollstats.jpg) top repeat-x;
	}
</style>
{/literal}
<body>

<div style=" font-size:13px; text-transform:uppercase; font-weight:bold; margin-top:10px; color:#006600" align="center">{#poll_title#}</div><br /><br />
<div align="center">
<table width="550" border="1" bordercolor="#666666" style=" border-collapse:collapse;text-align:left; font-size:11px;">
  <tr>
    <td colspan="2" align="left" style="background-color:#0066FF; color:#FFFFFF; padding:5px;"><b>{$pollQuestion.question}</b></td>    
  </tr>
 	{foreach from = $pollAnswer item=answer}
	<tr>
    	<td style="padding:5px;">{$answer.title}</td>		
	    <td width="400" style="padding:5px;"><span style=" float:left; margin-right:5px;width:{$answer.width+2}px; background-color:#6699FF; border:1px solid #333333">&nbsp;</span><span style="color:#FF0000">{$answer.percent|default:0}%</span></td>
    </tr>
	 {/foreach}
</table><br />
<div align="center"><label style="text-decoration:underline; color:#003366; cursor:pointer"  onclick="window.close();">{#close_window#}</label></div>
</div>
</body>
</html>