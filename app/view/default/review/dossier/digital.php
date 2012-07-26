 <!-- Anything Slider optional plugins -->
 <script src="/script/slider/js/jquery.easing.1.2.js"></script>

 <!-- Anything Slider -->
<!-- <link href="/script/slider/css/anythingslider.css" rel="stylesheet">-->
 <link href="/script/slider/css/theme-office.css" rel="stylesheet">
 <script src="/script/slider/js/jquery.anythingslider.min.js"></script>

 <!-- Anything Slider optional FX extension -->
 <script src="/script/slider/js/jquery.anythingslider.fx.min.js"></script>
 <style>
<!--

-->
 #slider3 {
  width: 350px;
  height: 600px;
  list-style: none;
 }
 /* images with caption */
 #slider3 img {
  width: 100%;
  height: 100%;
 }
 /* position the panels so the captions appear correctly */
 #slider3 .panel { position: relative; }
 /* captions */
 #slider3 .caption-top, #slider3 .caption-right,
 #slider3 .caption-bottom, #slider3 .caption-left {
  background: #000;
  color: #fff;
  padding: 10px;
  margin: 0;
  position: relative;
  z-index: 10;
  opacity: .8;
  filter: alpha(opacity=80);
 }
 /* Top caption - padding is included in the width (480px here, 500px in the script), same for height */
 #slider3 .caption-top {
  left: 0;
  top: 0;
  width: 480px;
  height: 30px;
 }
 /* Right caption - padding is included in the width (130px here, 150px in the script), same for height */
 #slider3 .caption-right {
  right: 0;
  bottom: 0;
  width: 130px;
  height: 180px;
 }
 /* Bottom caption - padding is included in the width (480px here, 500px in the script), same for height */
 #slider3 .caption-bottom {
  left: 0;
  bottom: 0;
  width: 98%;
  height: 30px;
 }
 /* Left caption - padding is included in the width (130px here, 150px in the script), same for height */
 #slider3 .caption-left {
  left: 0;
  bottom: 0;
  width: 130px;
  height: 180px;
 }
 /* Caption close button */
 .caption-top .close, .caption-right .close,
 .caption-bottom .close, .caption-left .close {
  font-size: 80%;
  cursor: pointer;
  float: right;
  display: inline-block;
 }
 
form {
	width:400px;
	margin:auto;
	padding:10px;
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
 <h1>پرونده دیجیتال</h1>
<?php if (isset($this->Result))
	ViewResultPlugin::Show($this->Result,$this->Error);
 if (!isset($_REQUEST['Cotag'])){?>

<form method='post'>
	<a href='/help/#digital'>
	<img src='/img/web/icon/help32.png' style='border:0px solid gray; float:right;' />
	</a>
<div>
	<label>شماره کوتاژ</label>
	<input type='text' name='Cotag' />
	
</div>

<input type='submit' value='مشاهده پرونده دیجیتال' />
</form>
<?php }else{?>
<div class="" style="height: 1000px; width=400px">
  <ul id="slider3">
  <?php foreach ($this->Images as $I) {?>
  <li>
   <img src='<?php echo jURL::Root();?>/image?name=<?php echo $I->path();?>.jpg' alt='<?php echo $I->PID()->Title()?>'>
   <div class="caption-top">
   <?php echo $I->PID()->Summary()?>
   </div>
  </li>
  		
 <?php }?>
	</ul>
 
 </div>
 <?php }?>
 <script>
 $(function(){
	 $('#slider3').anythingSlider({
		 	easing: "easeOutBack",
		  	theme:"office",
			expand				: true,
			resizeContents      : false,  
			startText           : "شروع"

		  })
	  /* this "custom" code is the equivalent of the base caption functions */
	  .anythingSliderFx({
	   inFx: {
	    '.caption-top'    : { top: 0,right: 0, opacity: 0.8, duration: 400 },
	    '.caption-right'  : { right: 0, opacity: 0.8, duration: 400 },
	    '.caption-bottom' : { bottom: 0, opacity: 0.8, duration: 400 },
	    '.caption-left'   : { left: 0, opacity: 0.8, duration: 400 }
	   },
	   outFx: {
	    '.caption-top'    : { top: -50, opacity: 0, duration: 350 },
	    '.caption-right'  : { right: -150, opacity: 0, duration: 350 },
	    '.caption-bottom' : { bottom: -50, opacity: 0, duration: 350 },
	    '.caption-left'   : { left: -150, opacity: 0, duration: 350 }
	   }
	  })
	  /* add a close button (x) to the caption */
	  .find('div[class*=caption]')
	    .css({ position: 'absolute' })
	    .prepend('<span class="close">x</span>')
	    .find('.close').click(function(){
	     var cap = $(this).parent(),
	       ani = { bottom : -50 }; // bottom
	      if (cap.is('.caption-top')) { ani = { top: -50 }; }
	      if (cap.is('.caption-left')) { ani = { left: -150 }; }
	      if (cap.is('.caption-right')) { ani = { right: -150 }; }
	      cap.animate(ani, 400, function(){ cap.hide(); } );
	    });
	});
	
 
 
 </script>