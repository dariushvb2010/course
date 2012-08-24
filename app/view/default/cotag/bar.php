<style>
#persianCotag{
 font-family: titr;
    left: 186px;
    position: absolute;
    text-align: center;
    top: 213px;
    font-size:30pt;
}
#barcode{
	margin-top: 100px;
	margin-left: 100px;	
}
</style>
<div id='barcode'>
	<img src='<?php echo jURL::Root()."/barcode?number=".urlencode($this->cotag)."&width=3&height=100&font=15"?>'   />
	
</div>



<script>
window.print();
window.close();
</script>