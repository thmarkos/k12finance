<!-- DEMO PANEL Files Start -->
<?php
$tmp_path = $_GET['template_dir'];
?>
<link href="<?php echo $tmp_path; ?>/demo/demo.css" rel="stylesheet"/>
<script type="text/javascript" src="<?php echo $tmp_path; ?>/demo/demo.js"></script> 
<script type="text/javascript" src="<?php echo $tmp_path; ?>/demo/jscolor/jscolor.js"></script> 
<script type="text/javascript">
(function($){ 
	var styleTags = '<style id="body_background_style" type="text/css"></style>'
	+ '<style id="body_text_color_style" type="text/css"></style>'
	+ '<style id="content_color_style" type="text/css"></style>'
	+ '<style id="links_color_style" type="text/css"></style>'
	+ '<style id="bottom_color_style" type="text/css"></style>'
	+ '<style id="footer_color_style" type="text/css"></style>'
	+ '<style id="color_suggestions_style" type="text/css"></style>';
	$('head').append(styleTags);
})(jQuery);
</script>
<div id="demopanel">
    <div id="panel">
		<h3>Options</h3>


<table width="100%">
	<tr>
        <td width="100%">
        <div class="heading">Page Background:</div>
		<div class="selector">
        	<input class="color {pickerPosition:'right', hash:true}" value="FFFFFF" id="body_background" name="body_background" type="text" size="18" />
        </div>
        </td>
    </tr>
	<tr>
    	<td width="100%">
        <div class="heading">Body text color:</div>
		<div class="selector">
        	<input class="color {pickerPosition:'right', hash:true}" value="353535" id="body_text_color" name="body_text_color" type="text" size="18" />
        </div>
        </td>
	</tr>
    <tr>
        <td width="100%">
        <div class="heading">Links Color:</div>
		<div class="selector">
        	<input class="color {pickerPosition:'right', hash:true}" value="7aa908" id="links_color" name="links_color" type="text" size="18" />
        </div>
        </td>
	</tr>
    <tr>
    	<td width="100%">
        <div class="heading">CONTENT Color:</div>
		<div class="selector">
        	<input class="color {pickerPosition:'right', hash:true}" value="7aa908" id="content_color" name="content_color" type="text" size="18" />
        </div>
        </td>
	</tr>
	<tr>
    	<td width="100%">
        <div class="heading">Bottom Color:</div>
		<div class="selector">
        	<input class="color {pickerPosition:'right', hash:true}" value="252525" id="bottom_color" name="bottom_color" type="text" size="18" />
        </div>
        </td>
	</tr>
    <tr>
    	<td width="100%">
        <div class="heading">Footer Color:</div>
		<div class="selector">
        	<input class="color {pickerPosition:'right', hash:true}" value="252525" id="footer_color" name="footer_color" type="text" size="18" />
        </div>
        </td>
	</tr>
    <tr>
        <td width="100%">
        <div class="heading">Color Suggestion:</div>
		<div class="selector">
        	<select name="color_suggestions" id="color_suggestions" class="big_select">
            	<option value="">-- Select --</option>
                <option value="#B71010" style="background-color:#B71010">B71010</option>
                <option value="#74AB00" style="background-color:#74AB00">74AB00</option>
                <option value="#C8C8C8" style="background-color:#C8C8C8">C8C8C8</option>
                <option value="#1592CC" style="background-color:#1592CC">1592CC</option>
                <option value="#C72647" style="background-color:#C72647">C72647</option>
                <option value="#ebeb1e" style="background-color:#ebeb1e">ebeb1e</option>
                <option value="#13D7FD" style="background-color:#13D7FD">13D7FD</option>
                <option value="#AD00B4" style="background-color:#AD00B4">AD00B4</option>
                <option value="#FFF712" style="background-color:#FFF712">FFF712</option>
                <option value="#EB540A" style="background-color:#EB540A">EB540A</option>
        	</select>
        </div>
        </td>
	</tr>
</table>
    
	<span class="note">This panel is build only for demo purposes. It does not work well on IE</span>
        

    </div><!-- close #panel -->
    
    <div style="display: block;" class="panel_button"><img alt="expand" src="<?php echo $tmp_path; ?>/demo/expand.png" /></div> <!-- close #panel_button -->
    <div style="display: none;" class="panel_button hide_button"><img alt="collapse" src="<?php echo $tmp_path; ?>/demo/collapse.png" /> </div> <!-- close #panel_button -->
</div>
<!-- DEMO PANEL End -->