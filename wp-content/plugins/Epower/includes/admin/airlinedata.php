<?php 

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


function saveAirlineData()
{
    for($k=0;$k<=count($_FILES['gallery_photo']);$k++){
	$airline_code=$_POST['description'];
	$airline_icon=$_FILES['gallery_photo']['name'];
	$airline_data = array($airline_code  => $airline_icon
                    );
	if(get_option('airline_data') === FALSE){
		add_option('airline_data',  $airline_data );
	}else{
		update_option('airline_data', $airline_data );
	}
	}
}

if(isset($_POST['btn_airlinedata'])){
   saveAirlineData();
} 



// Generate HTML for the menu page
function airLineData() {
?>
<div class="wrap">	
		<div id="icon-options-general" class="icon32"><br /></div>
		<h2>Airline Settings</h2>
		<form method="post" action="" enctype="multipart/form-data">
			<?php settings_fields('airlinedata'); ?>
			<h3>Airline Settings</h3>			
				<p class="submit">
					<input class="button-primary" type="button" value="Add New" name="add_more"  id="add_more" />
				</p>

			<!--<table class="form-table">
				<tr valign="top">
					<th scope="row">
						<label for="airline_code">Airline Code</label>
					</th>
					<td>
						<input type="text" name="airline_code[]" id="airline_code[]" size="50" value="<?php //echo get_option(''); ?>"/>
					</td>
				</tr>					
				<tr valign="top">
					<th scope="row">
						<label for="airline_icon">Airline Icon</label>
					</th>
					<td>
						<input type="file" name="airline_icon[]" id="airline_icon[]" value="<?php //echo get_option('epower_api_url'); ?>" />
					</td>
				</tr>					
									
			</table>-->
						<div id="fields_wrapper">
                                <fieldset class="scheduler-border">
                                    <legend class="scheduler-border"><h4>Airlines Data-1</h4></legend>
                                    <div style="height:10px;"></div>
                                    <div class="form-group">
                                        <label>Description</label>
                                        <input type="text" name="description[]" id="description[]" placeholder="Description" class="form-control">
                                    </div><br>
                                    <div class="form-group">
                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                            <span class="btn btn-default btn-file"><span>Choose file</span><input type="file" name="gallery_photo[]" id="gallery_photo[]" /></span>
                                            <span class="fileinput-filename" id="file_name1">No file chosen</span>
                                        </div>
                                    </div>
                                    
                                </fieldset>
                            </div>

                           

			<p class="submit">
				<input class="button-primary" type="submit" value="Save Changes" name="btn_airlinedata" />
			</p>

		</form>

	</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script type="text/javascript">
  var file_counter = 1;
  var total_file = 1;

  $("#add_more").click(function (e) {
      file_counter++;
      total_file++;
      if (total_file <= 10) {
          $('#fields_wrapper').append('<fieldset class="scheduler-border" id="file_group' + file_counter + '"><legend class="scheduler-border">Property Photo-' + file_counter + '</legend><div style="height:10px;"></div><div class="form-group" id="file' + file_counter + '"><div class="fileinput fileinput-new" data-provides="fileinput"><span class="btn btn-default btn-file"><span>Choose file</span><input type="file" name="gallery_photo[]" id="gallery_photo[]" onchange="javascript: onFileSelect(this,' + file_counter + ');" /></span>&nbsp;<span class="fileinput-filename" id="file_name' + file_counter + '">No file chosen</span></div></div> <div class="form-group"><label>Description</label><textarea name="description" id="description" placeholder="Description" class="form-control"></textarea></div><div class="form-group" onclick="javascript:removeFile(' + file_counter + ');"><button class="btn btn-primary" type="button" name="btn_remove" id="btn_remove" ><span class="glyphicon glyphicon-remove"></span>&nbsp;<strong>Remove File</strong></button></div></fieldset>');
      } else {
          alert('maximum 10 files you can upload in once');
      }
  });

  function onFileSelect(curr_file_ele, file_number) {
      if (curr_file_ele.value == '') {
          $("#file_name" + file_number).html("No file chosen");
      } else {
          $("#file_name" + file_number).html(curr_file_ele.value);
      }
  }

  function upload_photo() {
      var gallery_photo = document.getElementById('gallery_photo');
      var FileUploadPath = gallery_photo.value;

      if (FileUploadPath == '') {
          return false;
      }
  }

  function removeFile(file_number) {
      $('#file_group' + file_number).remove();
      total_file--;
  }

</script>



<?php
}
?>

