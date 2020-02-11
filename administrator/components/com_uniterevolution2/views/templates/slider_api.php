<?php
	$api =  "revapi".$sliderID;
?>
	
	<div id="api_wrapper" class="api_wrapper postbox unite-postbox ">
			<h3 class="box_closed tp-accordion tpa-closed"><div class="postbox-arrow"></div><i style="float:left;margin-top:4px;font-size:14px;" class="eg-icon-tools"></i><span><?php _uge("API Functions",REVSLIDER_TEXTDOMAIN) ?></span></h3>
			<div class="toggled-content tp-closedatstart p20">
					<div class="api-caption"><?php _uge("API Methods",REVSLIDER_TEXTDOMAIN)?>:</div>
					<div class="divide20"></div>
					<div class="api-desc"><?php _uge("Please copy / paste those functions into your functions js file",REVSLIDER_TEXTDOMAIN)?>. </div>
					
					<table class="api-table">
						<tr>
							<td class="api-cell1"><?php _uge("Pause Slider",REVSLIDER_TEXTDOMAIN)?>:</td>
							<td class="api-cell2"><input type="text" readonly  class="api-input" value="<?php echo $api?>.revpause();"></td>
						</tr>
						<tr>
							<td class="api-cell1"><?php _uge("Resume Slider",REVSLIDER_TEXTDOMAIN)?>:</td>
							<td class="api-cell2"><input type="text" readonly class="api-input" value="<?php echo $api?>.revresume();"></td>
						</tr>
						<tr>
							<td class="api-cell1"><?php _uge("Previous Slide",REVSLIDER_TEXTDOMAIN)?>:</td>
							<td class="api-cell2"><input type="text" readonly class="api-input" value="<?php echo $api?>.revprev();"></td>
						</tr>
						<tr>
							<td class="api-cell1"><?php _uge("Next Slide",REVSLIDER_TEXTDOMAIN)?>:</td>
							<td class="api-cell2"><input type="text" readonly class="api-input" value="<?php echo $api?>.revnext();"></td>
						</tr>
						<tr>
							<td class="api-cell1"><?php _uge("Go To Slide",REVSLIDER_TEXTDOMAIN)?>:</td>
							<td class="api-cell2"><input type="text" readonly class="api-input" value="<?php echo $api?>.revshowslide(2);"></td>
						</tr>
						<tr>
							<td class="api-cell1"><?php _uge("Get Num Slides",REVSLIDER_TEXTDOMAIN)?>:</td>
							<td class="api-cell2"><input type="text" readonly class="api-input" value="<?php echo $api?>.revmaxslide();"></td>
						</tr>
						<tr>
							<td class="api-cell1"><?php _uge("Get Current Slide Number",REVSLIDER_TEXTDOMAIN)?>:</td>
							<td class="api-cell2"><input type="text" readonly class="api-input" value="<?php echo $api?>.revcurrentslide();"></td>
						</tr>
						<tr>
							<td class="api-cell1"><?php _uge("Get Last Playing Slide Number",REVSLIDER_TEXTDOMAIN)?>:</td>
							<td class="api-cell2"><input type="text" readonly class="api-input" value="<?php echo $api?>.revlastslide();"></td>
						</tr>
						<tr>
							<td class="api-cell1"><?php _uge("External Scroll",REVSLIDER_TEXTDOMAIN)?>:</td>
							<td class="api-cell2"><input type="text" readonly class="api-input" value="<?php echo $api?>.revscroll(offset);"></td>
						</tr>
						<tr>
							<td class="api-cell1"><?php _uge("Redraw Slider",REVSLIDER_TEXTDOMAIN)?>:</td>
							<td class="api-cell2"><input type="text" readonly  class="api-input" value="<?php echo $api?>.revredraw();"></td>
						</tr>
						<tr>
							<td class="api-cell1"><?php _uge("Kill and Remove Slider",REVSLIDER_TEXTDOMAIN)?>:</td>
							<td class="api-cell2"><input type="text" readonly  class="api-input" value="<?php echo $api?>.revkill();"></td>
						</tr>
					</table>
					<div class="divide20"></div>
					<hr>
					<div class="divide20"></div>					
					<div class="api-caption"><?php _uge("API Events",REVSLIDER_TEXTDOMAIN)?>:</div>
					<div class="divide20"></div>
					<div class="api-desc"><?php _uge("Copy and Paste the Below listed API Functions into your jQuery Functions for Revslider Event Listening",REVSLIDER_TEXTDOMAIN)?>.</div>
					<textarea id="api_area" readonly>
					
<?php echo $api?>.bind("revolution.slide.onloaded",function (e) {
	//alert("slider loaded");
});
		
<?php echo $api?>.bind("revolution.slide.onchange",function (e,data) {
	//alert("slide changed to: "+data.slideIndex);
	//data.slideIndex <?php _uge('is the index of the li container in this Slider', REVSLIDER_TEXTDOMAIN); ?>
	
	//data.slide <?php _uge('is the current slide jQuery object (the li element)', REVSLIDER_TEXTDOMAIN); ?>
	
});

<?php echo $api?>.bind("revolution.slide.onpause",function (e,data) {
	//alert("timer paused");
});

<?php echo $api?>.bind("revolution.slide.onresume",function (e,data) {
	//alert("timer resume");
});

<?php echo $api?>.bind("revolution.slide.onvideoplay",function (e,data) {
	//alert("video play");
});

<?php echo $api?>.bind("revolution.slide.onvideostop",function (e,data) {
	//alert("video stopped");
});

<?php echo $api?>.bind("revolution.slide.onstop",function (e,data) {
	//alert("slider stopped");
});

<?php echo $api?>.bind("revolution.slide.onbeforeswap",function (e) {
	//alert("before swap");
});

<?php echo $api?>.bind("revolution.slide.onafterswap",function (e) {
	//alert("after swap");
});

<?php echo $api?>.bind("revolution.slide.slideatend",function (e) {
	//alert("slide at end");
});
			
			
			</textarea>
		</div>
	</div>
	
	<script type="text/javascript">
		jQuery(document).ready(function(){
	    	if(!g_revSliderAdmin)
	    		g_revSliderAdmin = new RevSliderAdmin();
		
	    	g_revSliderAdmin.initEditSlideView();
		});
	</script>
