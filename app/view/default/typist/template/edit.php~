<style>
form{
	margin:10,auto;
}
#formedit{
	//float:left;
}
#fieldlist{
	//float:left;
	border:1px solid red;
	width:100px;
}
#elm1{
	width: 100%;
}
#elmwrap{
	width:800px;
	margin:auto;
}
</style>

<script type="text/javascript" src="/script/tiny_mce/jquery.tinymce.js"></script>
<script type="text/javascript">
	$().ready(function() {
		$('.fieldelement').click(function(){
				var text=$(this).text();
				$('#elm1').tinymce().execCommand('mceInsertContent',false,text);
			});
		
		$('textarea.tinymce').tinymce({
			// Location of TinyMCE script
			//TODO: location is not good
			script_url : '/bazbini/script/tiny_mce/tiny_mce.js',

			// General options
			theme : "advanced",
			plugins : "autolink,lists,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,advlist",

			// Theme options
			theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
			theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
			theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
			theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak",
			theme_advanced_toolbar_location : "top",
			theme_advanced_toolbar_align : "left",
			theme_advanced_statusbar_location : "bottom",
			theme_advanced_resizing : true,

			// Example content CSS (should be your site CSS)
			content_css : "css/content.css",

			// Drop lists for link/image/media/template dialogs
			template_external_list_url : "lists/template_list.js",
			external_link_list_url : "lists/link_list.js",
			external_image_list_url : "lists/image_list.js",
			media_external_list_url : "lists/media_list.js",

			// Replace values for the template plugin
			template_replace_values : {
				username : "Some User",
				staffid : "991234"
			}
		});
	});
</script>
<!-- /TinyMCE -->

<h1><img src="/img/h/h1-review-50.png"/>
ویرایش قالب ها</h1>

<p>
عنوان قالب:
<?php echo $this->Template->Title();?>
</p>
<div id="fieldlist">
<?php
if (count($this->AllFields)){
	foreach ($this->AllFields as $v){
?>
	<div class="fieldelement">[<?php echo $v;?>]</div>
<?php }
} 
?>
</div>
<form id="formedit" method="post">
	<div>

		<!-- Gets replaced with TinyMCE, remember HTML in a textarea should be encoded -->
		<input type="hidden" name="ID" value="<?php echo $this->Template->ID();?>">
		<div id="elmwrap">
			<textarea id="elm1" name="Html" rows="15" cols="80" class="tinymce">
				<?php echo $this->Template->Html();?>
			</textarea>
		</div>

		<!-- Some integration calls -->
		<a href="javascript:;" onclick="$('#elm1').tinymce().execCommand('mceInsertContent',false,'<b>Hello world!!</b>');return false;">[Insert HTML]</a>
		<a href="javascript:;" onclick="$('#elm1').tinymce().execCommand('mceReplaceContent',false,'<b>{$selection}</b>');return false;">[Replace selection]</a>

		<br />
		<input type="submit" name="save" value="Submit" />
		<input type="reset" name="reset" value="Reset" />
	</div>
</form>