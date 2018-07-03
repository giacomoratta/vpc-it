<?php

include("AjaxEmail.class.php");
$ajaxemail = new AjaxEmail();

/* lista campi form  =>  0/1 se facoltativo/obbligatorio */
$fields = array('name'=>1,'email'=>1,'text'=>1);

/* Messaggi */
$_msg_emptyfield = "Questo campo &egrave; obbligatorio. Inserisci i dati richiesti.";
$_msg_emailformat = "Inserisci un indirizzo email corretto.";


if(!isset($_POST)) return;


/* CAMPI VUOTI */
$empty = $ajaxemail->check_empty_fields($_POST,$fields);
if($ajaxemail->error_empty)
{
	$json = array();
	$keys = array_keys($fields);
	foreach($keys as $k)
	{
		if($fields[$k]==1 && $empty[$k]==1) $json[] = "\"$k\" : \"$_msg_emptyfield\"";
	}
	echo " { \"exeCode\":1 ,   \"errorMsg\": { ".implode(",",$json)." } } ";
	return;
}


/* COPIA */
$name = $_POST['name'];
$email = $_POST['email'];
$text = $_POST['text'];
$to = $_POST['to'];
$act_link = $_POST['act_link'];


/* ERRORI */
$error_msg = array('email_format'=>"");
$error = array('email_format'=>0);
$error_check = 0;

if(!$ajaxemail->is_email($email)) { $error_msg['email_format']=$_msg_emailformat; $error['email_format']=1; $error_check=1; }

if($error_check)
{
	$json = array();
	$keys = array_keys($error);

	foreach($keys as $k) { $json[]="\"$k\" : \"".$error_msg[$k]."\""; }
	echo " { \"exeCode\":2 ,   \"errorMsg\": { ".implode(",",$json)." } } ";
	return;
}



/* INVIO EMAIL */
$message = "";

$text = stripslashes($text);

$message .= "\n".trim($text)."\n\n__________________________________________________\n\n";
$message .=	'Email inviata da VisitPortoCesareo.it tramite il modulo della pagina della tua attività:'."\n$act_link\n\n";
$message .= 'Mittente: '.$name." ($email)\nDestinatari: ".$to;
$message = str_ireplace("\r","",$message);
$message = str_ireplace("\n","\r\n",$message);
$subject = "Richiesta informazioni (da VisitPortoCesareo.it)";


		
$result = $ajaxemail->send_email($to,$name,$email,$subject,$message);

if($result)
echo " { \"exeCode\":0 ,   \"errorMsg\": { \"msg\" : \"Messaggio inviato correttamente!\" } } ";

else
echo	" { \"exeCode\":3 ,   \"errorMsg\": { \"msg\" : \"<p><strong>Si &egrave; verificato un errore durante l'invio del messaggio.</strong></p>".
		"<p>Fai qualche altro tentativo oppure utilizza i contatti riportati nella pagina<br />per contattare direttamente l'attivit&agrave;.</p>\" } } ";














?>