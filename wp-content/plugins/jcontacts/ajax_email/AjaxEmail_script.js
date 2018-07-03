
var jae = $("#jae");
var jae_name = $("#jae_name");
var jae_email = $("#jae_email");
var jae_subject = $("#jae_subject");
var jae_text = $("#jae_text");
var jae_send = $("#jae_send");
var jae_php_url = "wp-content/plugins/jcontacts/ajax_email/AjaxEmail_server.php";

var jae_email_list = $("#jae_email_list");
jae_email_list.css({'display':'block'});
jae_email_list.html(jae_email_list.html()+" "+jae_emails_text);

jae_send.bind('click',function()
{	
	jae_send.attr("disabled","disabled");
	var msgbox = $('#jae_msgbox');
	var panel = $('#jae_panel');
	msgbox.html("<strong>Invio in corso...</strong>");
	msgbox.css({ 'opacity':1, 'display':'block', 'height':panel.height()+'px', 'width':panel.width()+'px'});
	panel.css({'opacity':0});
	
	$("#jae").css({ 'overflow':'hidden', 'display':'block', 'height':panel.height()+'px', 'width':panel.width()+'px'});
	$("#jae_title").css({'display':'block'});
	
	$.ajax({
		url: jae_php_url,
		success: function(data) {  jae_success(data); },
		error: function() { jae_success(" { \"exeCode\":-1 ,   \"errorMsg\": { \"msg\" : \"Impossibile comunicare con il server.\" } } "); },
		
		type: "POST",
		data: ({ name:jae_name.val(), email:jae_email.val(), subject:jae_subject.val(),
				 text:jae_text.val(), to:jae_emails, act_link:jea_act_link })
	});
});

function jae_success(data)
{
	var msgbox = $('#jae_msgbox');
	var panel = $('#jae_panel');

	
	jd = JSON.parse(data);
	code = jd['exeCode'];
	
	jae.find(".jae_error").css({'display':'none'});
	
	// MSGBOX
	if(code==3 || code==0)
	{
		msgbox.html(jd['errorMsg']['msg']);
		
		msgbox.css({ 'opacity':1, 'display':'block', 'height':panel.height()+'px', 'width':panel.width()+'px'});
		panel.css({'opacity':0});
		
		if(code!=0) /* resume */
		{
			msgbox.delay(3000).animate({'opacity':0},1000,function(){ msgbox.css({'display':'none'}); jae_send.removeAttr("disabled"); });
			panel.delay(3000).animate({'opacity':1},1000);
		}
	}
	else
	{
		msgbox.css({'opacity':1, 'display':'none'});
		panel.css({'opacity':1, 'display':'block'});
		jae_send.removeAttr("disabled","disabled");
	}
	
	// Empty Errors
	if(code==1)
	{
		msgArray = jd['errorMsg'];
		for(var key in msgArray)
		{
			
			eMX = jae.find("#jae_error_"+key);
			eMX.css({'display':'block'});
			eMX.html(msgArray[key]);
		}
	}
	
	
	// Format Errors
	else if(code==2)
	{
		msgArray = jd['errorMsg'];
		jae.find("#jae_error_email").css({'display':'block'}).html(msgArray['email_format']);
	}
}

