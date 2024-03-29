<?php  
include(dirname(__FILE__).'/header.php');
?>
<style>
.bbody {
    color: #000;
    overflow: hidden;
    padding-bottom: 20px;
    text-align: center;
    background: -moz-linear-gradient(#ffffff, #f2f2f2);
    background: -ms-linear-gradient(#ffffff, #f2f2f2);
    background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #ffffff), color-stop(100%, #f2f2f2));
    background: -webkit-linear-gradient(#ffffff, #f2f2f2);
    background: -o-linear-gradient(#ffffff, #f2f2f2);
    filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ffffff', endColorstr='#f2f2f2');
    -ms-filter: "progid:DXImageTransform.Microsoft.gradient(startColorstr='#ffffff', endColorstr='#f2f2f2')";
    background: linear-gradient(#ffffff, #f2f2f2);
}
.bbody h2, .info, .error {
    margin: 10px 0;
}
.step2, .error {
    display: none;
}
.error {
    font-size: 18px;
    font-weight: bold;
    color: red;
}
.info {
    font-size: 14px;
}
label {
    margin: 0 5px;
}
input {
    border: 1px solid #CCCCCC;
    border-radius: 10px;
    padding: 4px 8px;
    text-align: center;
    width: 70px;
}
.jcrop-holder {
    display: inline-block;
}
input[type=submit] {
    background: #e3e3e3;
    border: 1px solid #bbb;
    border-radius: 3px;
    -webkit-box-shadow: inset 0 0 1px 1px #f6f6f6;
    box-shadow: inset 0 0 1px 1px #f6f6f6;
    color: #333;
    font: bold 12px/1 "helvetica neue", helvetica, arial, sans-serif;
    padding: 8px 0 9px;
    text-align: center;
    text-shadow: 0 1px 0 #fff;
    width: 150px;
}
input[type=submit]:hover {
    background: #d9d9d9;
    -webkit-box-shadow: inset 0 0 1px 1px #eaeaea;
    box-shadow: inset 0 0 1px 1px #eaeaea;
    color: #222;
    cursor: pointer;
}
input[type=submit]:active {
    background: #d0d0d0;
    -webkit-box-shadow: inset 0 0 1px 1px #e3e3e3;
    box-shadow: inset 0 0 1px 1px #e3e3e3;
    color: #000;
}
.modal-md{
width: 600px !important;
}
#ld-preview-img{
	max-height: 500px;
max-width: 500px;
width: auto;
height: auto;
display: block;
}
</style>
<script>
/* convert bytes into friendly format */
function bytesToSize(bytes) {
    var sizes = ['Bytes', 'KB', 'MB'];
    if (bytes == 0) return 'n/a';
    var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
    return (bytes / Math.pow(1024, i)).toFixed(1) + ' ' + sizes[i];
};
/* check for selected crop region */
function checkForm() {
    if (parseInt(jQuery('#w').val())) return true;
    jQuery('.error').html('Please select a crop region and then press Upload').show();
    return false;
}; 
/* update info by cropping (onChange and onSelect events handler) */
function updateInfo(e) {
    jQuery('#x1').val(e.x);
    jQuery('#y1').val(e.y);
    jQuery('#x2').val(e.x2);
    jQuery('#y2').val(e.y2);
    jQuery('#w').val(e.w);
    jQuery('#h').val(e.h);
};
/* clear info by cropping (onRelease event handler) */
function clearInfo() {
    jQuery('.info #w').val('');
    jQuery('.info #h').val('');
};
/* Create variables (in this scope) to hold the Jcrop API and image size */
var jcrop_api, boundx, boundy;
function fileSelectHandler() {
    
    var oFile = jQuery('#image_file')[0].files[0];
	
    var rFilter = /^(image\/jpeg|image\/png)$/i;
    if (! rFilter.test(oFile.type)) {
        jQuery('.error').html('Please select a valid image file (jpg and png are allowed)').show();
        return;
    }
   
    if (oFile.size > 2500 * 5000) {
        jQuery('.error').html('You have selected too big file, please select a one smaller image file').show();
        return;
    }
   
    var oImage = document.getElementById('ld-preview-img');
   
    var oReader = new FileReader();
        oReader.onload = function(e) {
        
        oImage.src = e.target.result;
        oImage.onload = function () { 
			
			jQuery('#ld-image-upload-popup').modal();
		  
			/* display some basic image info*/
			var sResultFileSize = bytesToSize(oFile.size);
			jQuery('#filesize').val(sResultFileSize);
			jQuery('#filetype').val(oFile.type);
			jQuery('#filedim').val(oImage.naturalWidth + ' x ' + oImage.naturalHeight);
		
			if (typeof jcrop_api != 'undefined') {
				jcrop_api.destroy();
				jcrop_api = null;
				jQuery('#ld-preview-img').width(oImage.naturalWidth);
				jQuery('#ld-preview-img').height(oImage.naturalHeight);
			}
			setTimeout(function(){
				
				jQuery('#ld-preview-img').Jcrop({
					minSize: [32, 32], 
					aspectRatio : 1, 
					bgFade: true, 
					bgOpacity: .3, 
					onChange: updateInfo,
					onSelect: updateInfo,
					onRelease: clearInfo
				}, function(){
					
					var bounds = this.getBounds();
					boundx = bounds[0];
					boundy = bounds[1];
					
					jcrop_api = this;
				});
			},3000);
        };
    };
   
    oReader.readAsDataURL(oFile);
}
</script>
<div class="bbody">
 
    <form id="upload_form" enctype="multipart/form-data" method="post" action="upload.php" onsubmit="return checkForm()">
        
        <input type="hidden" id="x1" name="x1" />
        <input type="hidden" id="y1" name="y1" />
        <input type="hidden" id="x2" name="x2" />
        <input type="hidden" id="y2" name="y2" />
        <h2>Step1: Please select image file</h2>
        <div><input type="file" name="image_file" id="image_file" onchange="fileSelectHandler()" /></div>
       <label class="lda-error">Please enter phone number</label>
          
			<div id="ld-image-upload-popup" class="modal fade" tabindex="-1" role="dialog">
				<div class="vertical-alignment-helper">
					<div class="modal-dialog modal-md vertical-align-center">
						<div class="modal-content">
							<div class="modal-header">
								<input type="submit" value="Upload" />
								<button type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true">
							</div>
							<div class="modal-body">
								<img id="ld-preview-img" />
							</div>
							<div class="modal-footer">
								<div class="info">
									<label>File size</label> <input type="text" id="filesize" name="filesize" />
									<label>Type</label> <input type="text" id="filetype" name="filetype" />
								   <label>Image dimension</label> <input type="text" id="filedim" name="filedim" />
									<label>W</label> <input type="text" id="w" name="w" />
									<label>H</label> <input type="text" id="h" name="h" /> 
								</div>
							</div>							
						</div>		
					</div>			
				</div>			
			</div>			
			
    </form>
	
</div>	
<div id="ld-imagse-preview-bg"></div>
	
<?php  
	include(dirname(__FILE__).'/footer.php');
?>	