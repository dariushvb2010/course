<?php ?>
<h1>اطلاعات کوتاژ </h1>
<style>

    form {
        width:60%;
        margin:auto;
        padding:10px;
        margin-bottom:10px;
        border:3px double;
        text-align:center;
    }
    form input[type='submit'] {
        width:200px;
        margin:5px;
    }
    form input[type='text'] {
        width:150px;
    }

</style>

<form method='post'>
    <a href='/help/#archive_new'>
        <img src='/img/web/icon/help32.png' style='border:0px solid gray; float:left;' />
    </a>
    <div>
        <label>شماره کوتاژ</label>
        <input type='text' name='Cotag' value="<?php echo $_POST['Cotag']; ?>" />
    </div>
    <input type='submit' value='نمایش' />
</form>
<?php
if (isset($this->Result))
    ViewResultPlugin::Show($this->Result, $this->Error);
if (isset($this->Asy)) {
    include dirname(__File__) . '/blocks/asydatabox.php';
}
?>
<script>
    $(function(){
	
        $("form input[name='Cotag']").focus();
    });


</script>