<?php 
include(dirname(__FILE__).'/header.php');
include(dirname(dirname(__FILE__))."/objects/class_services.php");
include(dirname(__FILE__).'/user_session_check.php');
$con = new laundry_db();
$conn = $con->connect();
$objservice = new laundry_services();
$objservice->conn = $conn;
?>
<div id="lda-clean-services-panel" class="panel tab-content">
    <div class="panel-body">
        <div class="ld-clean-service-details tab-content col-md-12 col-sm-12 col-lg-12 col-xs-12">
            
            <div class="ld-clean-service-top-header">
                <span class="ld-clean-service-service-name pull-left"><?php echo filter_var($label_language_values['all_services'], FILTER_SANITIZE_STRING);	?>(<?php echo filter_var($objservice->countallservice(), FILTER_SANITIZE_STRING);	?>)</span>
                <div class="pull-right">
                    <table>
                        <tbody>
                        <tr>
                            <td>
                                <?php 
                                $isrecord = $objservice->getalldata();
                                if($isrecord){
                                    $cnt = mysqli_num_rows($isrecord);
                                } else { $cnt=0; }
                                if($cnt > 0){
                                    
                                }
                                ?>
                                
                                <div id="service-front-view" class="modal fade">
                                    <div class="modal-dialog modal-sm modal-md ">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                <h4 class="modal-title"><?php echo filter_var($label_language_values['service_front_view'], FILTER_SANITIZE_STRING);	?></h4>
                                            </div>
                                            <div class="modal-body">
                                                <div class="ld-custom-radio">
                                                    <div class="alert alert-success mymessage_assign_design_service">
                                                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                                        <strong><?php echo filter_var($label_language_values['success'], FILTER_SANITIZE_STRING);	?></strong>
                                                    </div>
                                                    <ul class="ld-radio-list">
                                                        <?php 
                                                        $t = $objservice->get_setting_design("service");
                                                        ?>
                                                        <li class="fln w100">
                                                            <input <?php  if($t == 1){ echo filter_var("checked", FILTER_SANITIZE_STRING);}?> type="radio" id="front-service-box-view" class="lda-radio design_radio_btn"   name="front-service-view-radio" value="1" />
                                                            <label for="front-service-box-view"><span></span> <?php  echo filter_var($label_language_values['front_service_box_view'], FILTER_SANITIZE_STRING);	?></label>
                                                            <img src="<?php echo filter_var(SITE_URL, FILTER_VALIDATE_URL);	?>assets/images/services/default_service.png" style="height: 100px;width: 300px;">
                                                        </li>
                                                        <li class="fln w100">
                                                            <input <?php  if($t == 2){ echo filter_var("checked", FILTER_SANITIZE_STRING);}?> type="radio" id="front-service-dropdown-view" class="lda-radio design_radio_btn" name="front-service-view-radio" value="2" />
                                                            <label for="front-service-dropdown-view"><span></span><?php echo filter_var($label_language_values['front_service_dropdown_view'], FILTER_SANITIZE_STRING);	?></label>
                                                            <img src="<?php echo filter_var(SITE_URL, FILTER_VALIDATE_URL);	?>assets/images/services/default_service.png" style="height: 50px;width: 400px;">
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="modal-footer cb">
                                                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo filter_var($label_language_values['close'], FILTER_SANITIZE_STRING);	?></button>
                                               
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <button id="ld-add-new-service" class="btn btn-success" value="add new service"><i class="fa fa-plus"></i><?php echo filter_var($label_language_values['add_laundry_service'], FILTER_SANITIZE_STRING);	?></button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div id="hr"></div>
            <div class="tab-pane active" id="">
                <div class="tab-content ld-clean-services-right-details">
                    <div class="tab-pane active col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div id="accordion" class="panel-group">
                            <ul class="nav nav-tab nav-stacked my-sortable-services" id="sortable-services"  > 
                                <?php 
                                $i=0;
                                $getservice = $objservice->getalldata();
                                while($arr = @mysqli_fetch_array($getservice))
                                {
                                    $i++;
                                    ?>
                                    <li class="panel panel-default ld-clean-services-panel mysortlist" data-id="<?php echo filter_var($arr['id'], FILTER_SANITIZE_STRING);	?>" id="servicelist<?php  echo filter_var($arr['id'], FILTER_SANITIZE_STRING);	?>" data-position="<?php echo filter_var($arr['position'], FILTER_SANITIZE_STRING);	?>" >
                                        <div class="panel-heading">
                                            <h4 class="panel-title">
                                                <div class="lda-col7">
                                                    <div class="pull-left">
                                                        <i class="fa fa-th-list"></i><span class="badge" id="color_back<?php  echo filter_var($arr['id'], FILTER_SANITIZE_STRING);	?>" style="background-color:<?php echo filter_var($arr['color'], FILTER_SANITIZE_STRING);	?>;" title="<?php echo filter_var($label_language_values['service_color_badge'], FILTER_SANITIZE_STRING);	?>"></span>
                                                    </div>
                                                    <span class="ld-clean-service-title-name" id="title_ser<?php  echo filter_var($arr['id'], FILTER_SANITIZE_STRING);	?>"><?php echo  ucfirst($arr['title']);	?></span>
                                                </div>
                                                <div class="pull-right lda-col5">
                                                    <div class="lda-col4 lda-enabe-disable">
													
                                                        <label for="sevice-endis-<?php echo filter_var($i, FILTER_SANITIZE_STRING);	?>">
														
															<input data-id="<?php echo filter_var($arr['id'], FILTER_SANITIZE_STRING);	?>" class='myservice_status' data-toggle="toggle" data-size="small" type='checkbox' <?php  if($arr['status']=='E'){ echo filter_var("checked", FILTER_SANITIZE_STRING);}else{ echo filter_var("", FILTER_SANITIZE_STRING); }?> id="sevice-endis-<?php echo filter_var($i, FILTER_SANITIZE_STRING);	?>" data-on="<?php echo filter_var($label_language_values['enable'], FILTER_SANITIZE_STRING);	?>" data-off="<?php echo filter_var($label_language_values['disable'], FILTER_SANITIZE_STRING);	?>" data-onstyle='success' data-offstyle='danger' />
														
                                                        </label>
                                                    </div>
                                                    <div class="pull-right">
                                                        <div class="lda-col1">
                                                            <?php 
                                                            $t = $objservice->service_isin_use($arr['id']);
                                                            if($t>0){
                                                                ?>
                                                                <a data-toggle="popover" class="delete-clean-service-btn pull-right btn-circle btn-danger btn-sm" rel="popover" data-placement='top' title="<?php echo filter_var($label_language_values['service_is_booked'], FILTER_SANITIZE_STRING);	?>"> <i class="fa fa-ban"></i></a>
                                                            <?php 
                                                            }
                                                            else
                                                            {
                                                                ?>
                                                                <a id="ld-delete-service" data-toggle="popover" class="delete-clean-service-btn pull-right btn-circle btn-danger btn-sm" rel="popover" data-placement='left' title="<?php echo filter_var($label_language_values['delete_this_service'], FILTER_SANITIZE_STRING);	?>?"> <i class="fa fa-trash" title="<?php echo filter_var($label_language_values['delete_service'], FILTER_SANITIZE_STRING);	?>"></i></a>
                                                                <div id="popover-delete-servicess" style="display: none;">
                                                                    <div class="arrow"></div>
                                                                    <table class="form-horizontal" cellspacing="0">
                                                                        <tbody>
                                                                        <tr>
                                                                            <td>
                                                                                <a data-serviceid="<?php echo filter_var($arr['id'], FILTER_SANITIZE_STRING);	?>" data-imagename="<?php echo filter_var($arr['image'], FILTER_SANITIZE_STRING);	?>" value="Delete" class="btn btn-danger btn-sm service-delete-button" ><?php echo filter_var($label_language_values['yes'], FILTER_SANITIZE_STRING);	?></a>
                                                                                <button id="ld-close-popover-delete-service" class="btn btn-default btn-sm" href="javascript:void(0)"><?php echo filter_var($label_language_values['cancel'], FILTER_SANITIZE_STRING);	?></button>
                                                                            </td>
                                                                        </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            <?php 
                                                            }
                                                            ?>
                                                            
                                                        </div>
                                                        <div class="ld-show-hide pull-right">
                                                            <input type="checkbox" name="ld-show-hide" class="ld-show-hide-checkbox" id="myid<?php  echo filter_var($arr['id'], FILTER_SANITIZE_STRING);	?>" >
                                                            <label class="ld-show-hide-label" for="myid<?php  echo filter_var($arr['id'], FILTER_SANITIZE_STRING);	?>"></label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </h4>
                                        </div>
                                        <div id="detail_myid<?php  echo filter_var($arr['id'], FILTER_SANITIZE_STRING);	?>" class="service_detail panel-collapse collapse">
                                            <div class="panel-body">
                                                <div class="ld-service-collapse-div col-sm-7 col-md-6 col-lg-5 col-xs-12">
                                                    <form id="editform_service<?php  echo filter_var($arr['id'], FILTER_SANITIZE_STRING);	?>" method="post" type="" class="slide-toggle" >
                                                        <table class="ld-create-service-table">
                                                            <tbody>
                                                            <tr>
                                                                <td><label for="ld-service-color-tag"><?php echo filter_var($label_language_values['color_tag'], FILTER_SANITIZE_STRING);	?></label></td>
                                                                <td><input type="text" name="txtedtcolor" id="ld-service-color-tag<?php  echo filter_var($arr['id'], FILTER_SANITIZE_STRING);	?>" class="form-control demo edtservicecolor<?php  echo filter_var($arr['id'], FILTER_SANITIZE_STRING);	?>" data-control="saturation" value="<?php echo filter_var($arr['color'], FILTER_SANITIZE_STRING);	?>"></td>
                                                            </tr>
                                                            <tr>
                                                                <td><label for="ld-service-title"><?php echo filter_var($label_language_values['service_title'], FILTER_SANITIZE_STRING);	?></label></td>
                                                                <td><input type="text" name="txtedtservicetitle" class="form-control edtservicetitle<?php  echo filter_var($arr['id'], FILTER_SANITIZE_STRING);	?>" id="ld-service-title<?php  echo filter_var($arr['id'], FILTER_SANITIZE_STRING);	?>" value="<?php echo filter_var($arr['title'], FILTER_SANITIZE_STRING);	?>" /></td>
                                                            </tr>
                                                            <tr>
                                                                <td><label for="ld-service-desc"><?php echo filter_var($label_language_values['service_description'], FILTER_SANITIZE_STRING);	?></label></td>
                                                                <td><textarea id="ld-service-desc" class="form-control edtservicedesc<?php  echo filter_var($arr['id'], FILTER_SANITIZE_STRING);	?>" ><?php echo $arr['description'];	?></textarea></td>
                                                            </tr>
                                                            <tr>
                                                                <td><label for="ld-service-desc"><?php echo filter_var($label_language_values['service_image'], FILTER_SANITIZE_STRING);	?></label></td>
                                                                <td>
                                                                    <div class="ld-clean-service-image-uploader">
                                                                        <?php 
                                                                        if($arr['image']==''){
                                                                            $imagepath=SITE_URL."assets/images/default_service.png";
                                                                        }else{
                                                                            $imagepath=SITE_URL."assets/images/services/".$arr['image'];
                                                                        }
                                                                        ?>
                                                                        <img data-imagename="" id="pcls<?php  echo filter_var($arr['id'], FILTER_SANITIZE_STRING); ?>serviceimage" src="<?php echo filter_var($imagepath, FILTER_SANITIZE_STRING);	?>" class="ld-clean-service-image br-100" height="100" width="100">
                                                                        <?php 
                                                                        if($arr['image']==''){
                                                                            ?>
                                                                            <label for="ld-upload-imagepcls<?php  echo filter_var($arr['id'], FILTER_SANITIZE_STRING); ?>" class="ld-clean-service-img-icon-label old_cam_ser<?php  echo filter_var($arr['id'], FILTER_SANITIZE_STRING); ?>">
                                                                                <i class="ld-camera-icon-common br-100 fa fa-camera" id="pcls<?php  echo filter_var($arr['id'], FILTER_SANITIZE_STRING); ?>camera"></i>
                                                                                <i class="pull-left fa fa-plus-circle fa-2x" id="ctsc<?php  echo filter_var($arr['id'], FILTER_SANITIZE_STRING); ?>plus"></i>
                                                                            </label>
                                                                        <?php 
                                                                        }
                                                                        ?>
                                                                        <input data-us="pcls<?php  echo filter_var($arr['id'], FILTER_SANITIZE_STRING); ?>" class="hide ld-upload-images" type="file" name="" id="ld-upload-imagepcls<?php  echo filter_var($arr['id'], FILTER_SANITIZE_STRING);	?>" data-id="<?php echo filter_var($arr['id'], FILTER_SANITIZE_STRING);	?>" />
                                                                        <label for="ld-upload-imagepcls<?php  echo filter_var($arr['id'], FILTER_SANITIZE_STRING); ?>" class="ld-clean-service-img-icon-label new_cam_ser ser_cam_btn<?php  echo filter_var($arr['id'], FILTER_SANITIZE_STRING); ?>">
                                                                            <i class="ld-camera-icon-common br-100 fa fa-camera" id="pcls<?php  echo filter_var($arr['id'], FILTER_SANITIZE_STRING); ?>camera"></i>
                                                                            <i class="pull-left fa fa-plus-circle fa-2x" id="ctsc<?php  echo filter_var($arr['id'], FILTER_SANITIZE_STRING); ?>plus"></i>
                                                                        </label>
                                                                        <?php 
                                                                        if($arr['image']!==''){
                                                                            ?>
                                                                            <a id="ld-remove-service-imagepcls<?php  echo filter_var($arr['id'], FILTER_SANITIZE_STRING);	?>" data-pclsid="<?php echo filter_var($arr['id'], FILTER_SANITIZE_STRING);	?>" data-service_id="<?php echo filter_var($arr['id'], FILTER_SANITIZE_STRING);	?>" class="pull-left br-100 btn-danger bt-remove-service-img btn-xs ser_del_icon ser_new_del<?php  echo filter_var($arr['id'], FILTER_SANITIZE_STRING);	?>" rel="popover" data-placement='left' title="<?php echo filter_var($label_language_values['remove_image'], FILTER_SANITIZE_STRING);	?>"> <i class="fa fa-trash" title="<?php echo filter_var($label_language_values['remove_service_image'], FILTER_SANITIZE_STRING);	?>"></i></a>
                                                                        <?php 
                                                                        }
                                                                        ?>
                                                                        <a id="ld-remove-service-imagepcls<?php  echo filter_var($arr['id'], FILTER_SANITIZE_STRING);	?>" data-pclsid="<?php echo filter_var($arr['id'], FILTER_SANITIZE_STRING);	?>" data-service_id="<?php echo filter_var($arr['id'], FILTER_SANITIZE_STRING);	?>" class="pull-left br-100 btn-danger bt-remove-service-img btn-xs new_del_ser del_btn_popup<?php  echo filter_var($arr['id'], FILTER_SANITIZE_STRING);	?>" rel="popover" data-placement='left' title="<?php echo filter_var($label_language_values['remove_image'], FILTER_SANITIZE_STRING);	?>"> <i class="fa fa-trash" title="<?php echo filter_var($label_language_values['remove_service_image'], FILTER_SANITIZE_STRING);	?>"></i></a>
                                                                        <div id="popover-ld-remove-service-imagepcls<?php  echo filter_var($arr['id'], FILTER_SANITIZE_STRING);	?>" style="display: none;">
                                                                            <div class="arrow"></div>
                                                                            <table class="form-horizontal" cellspacing="0">
                                                                                <tbody>
                                                                                <tr>
                                                                                    <td>
                                                                                        <a href="javascript:void(0)" id="" value="Delete" data-service_id="<?php echo filter_var($arr['id'], FILTER_SANITIZE_STRING);	?>" class="btn btn-danger btn-sm delete_image" type="submit"><?php echo filter_var($label_language_values['yes'], FILTER_SANITIZE_STRING);	?></a>
                                                                                        <a href="javascript:void(0)" id="ld-close-popover-service-image" class="btn btn-default btn-sm" href="javascript:void(0)"><?php echo filter_var($label_language_values['cancel'], FILTER_SANITIZE_STRING);	?></a>
                                                                                    </td>
                                                                                </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                    <label class="error_image" ></label>
                                                                    <div id="ld-image-upload-popuppcls<?php  echo filter_var($arr['id'], FILTER_SANITIZE_STRING);	?>" class="ld-image-upload-popup modal fade" tabindex="-1" role="dialog">
                                                                        <div class="vertical-alignment-helper">
                                                                            <div class="modal-dialog modal-md vertical-align-center">
                                                                                <div class="modal-content">
                                                                                    <div class="modal-header">
                                                                                        <div class="col-md-12 col-xs-12">
                                                                                            <a data-us="pcls<?php  echo filter_var($arr['id'], FILTER_SANITIZE_STRING);	?>" class="btn btn-success ld_upload_img1" data-imageinputid="ld-upload-imagepcls<?php  echo filter_var($arr['id'], FILTER_SANITIZE_STRING);	?>" data-id="<?php echo filter_var($arr['id'], FILTER_SANITIZE_STRING); ?>"><?php echo filter_var($label_language_values['crop_and_save'], FILTER_SANITIZE_STRING);	?></a>
                                                                                            <button type="button" class="btn btn-default hidemodal" data-dismiss="modal" aria-hidden="true"><?php echo filter_var($label_language_values['cancel'], FILTER_SANITIZE_STRING);	?></button>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="modal-body">
                                                                                        <img id="ld-preview-imgpcls<?php  echo filter_var($arr['id'], FILTER_SANITIZE_STRING);	?>" style="width: 100%;"  />
                                                                                    </div>
                                                                                    <div class="modal-footer">
                                                                                        <div class="col-md-12 np">
                                                                                            <div class="col-md-12 np">
                                                                                                <div class="col-md-4 col-xs-12">
                                                                                                    <label class="pull-left"><?php echo filter_var($label_language_values['file_size'], FILTER_SANITIZE_STRING);	?></label> <input type="text" class="form-control" id="pclsfilesize<?php  echo filter_var($arr['id'], FILTER_SANITIZE_STRING);	?>" name="filesize" />
                                                                                                </div>
                                                                                                <div class="col-md-4 col-xs-12">
                                                                                                    <label class="pull-left">H</label> <input type="text" class="form-control" id="pcls<?php  echo filter_var($arr['id'], FILTER_SANITIZE_STRING);	?>h" name="h" />
                                                                                                </div>
                                                                                                <div class="col-md-4 col-xs-12">
                                                                                                    <label class="pull-left">W</label> <input type="text" class="form-control" id="pcls<?php  echo filter_var($arr['id'], FILTER_SANITIZE_STRING);	?>w" name="w" />
                                                                                                </div>
                                                                                                
                                                                                                <input type="hidden" id="pcls<?php  echo filter_var($arr['id'], FILTER_SANITIZE_STRING);	?>x1" name="x1" />
                                                                                                <input type="hidden" id="pcls<?php  echo filter_var($arr['id'], FILTER_SANITIZE_STRING);	?>y1" name="y1" />
                                                                                                <input type="hidden" id="pcls<?php  echo filter_var($arr['id'], FILTER_SANITIZE_STRING);	?>x2" name="x2" />
                                                                                                <input type="hidden" id="pcls<?php  echo filter_var($arr['id'], FILTER_SANITIZE_STRING);	?>y2" name="y2" />
                                                                                                <input type="hidden" id="pcls<?php  echo filter_var($arr['id'], FILTER_SANITIZE_STRING);	?>id" name="id" value="<?php echo filter_var($arr['id'], FILTER_SANITIZE_STRING);	?>" />
                                                                                                <input id="pclsctimage<?php  echo filter_var($arr['id'], FILTER_SANITIZE_STRING);	?>" type="hidden" name="ctimage" />
                                                                                                <input type="hidden" id="recordid" value="<?php echo filter_var($arr['id'], FILTER_SANITIZE_STRING);	?>">
                                                                                                <input type="hidden" id="pcls<?php  echo filter_var($arr['id'], FILTER_SANITIZE_STRING);	?>ctimagename" class="pclsimg" name="ctimagename" value="<?php echo filter_var($arr['image'], FILTER_SANITIZE_STRING);	?>" />
                                                                                                <input type="hidden" id="pcls<?php  echo filter_var($arr['id'], FILTER_SANITIZE_STRING);	?>newname" value="service_" />
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
																														<tr>
																															<td>
																																<label><?php echo filter_var($label_language_values['maximum_order_per_day'], FILTER_SANITIZE_STRING);	?></label>
																															</td>
																															<td>
																																<div class="input-group spinner">
																																	<div class="input-group-btn-horizontal">
																																		<button class="btn ld-addition btn-default input-group-addon" data-id="<?php echo filter_var($arr['id'], FILTER_SANITIZE_STRING); ?>" type="button"><i class="fa fa-plus nm"></i></button>									
																																		<input type="text" class="form-control limit-value pass_min<?php echo filter_var($arr['id'], FILTER_SANITIZE_STRING); ?> v_c" name="ld_maximum_order_per_day" id="limit-value<?php echo filter_var($arr['id'], FILTER_SANITIZE_STRING); ?>" value="<?php echo filter_var($arr['service_limit'], FILTER_SANITIZE_STRING);	?>">
																																		<button class="btn ld-subtraction btn-default input-group-addon" data-id="<?php echo filter_var($arr['id'], FILTER_SANITIZE_STRING); ?>" type="button"><i class="fa fa-minus nm"></i></button>
																																	</div>
																																</div>
																															</td>
																														</tr>
															<tr>
																<td></td>
																<td>
																	<a id="" name="" data-id="<?php echo filter_var($arr['id'], FILTER_SANITIZE_STRING);	?>" class="btn btn-success ld-btn-width col-md-offset-1 edtservicebtn" ><?php echo filter_var($label_language_values['update'], FILTER_SANITIZE_STRING);	?></a>
																	<button type="reset" class="btn btn-default ld-btn-width ml-30"><?php echo filter_var($label_language_values['reset'], FILTER_SANITIZE_STRING);	?></button>
																</td>
															</tr>
                                                            </tbody>
                                                        </table>
                                                </div>
                                               
                                                </form>
                                            </div>
                                        </div>
                                    </li>
                                <?php 
                                }
                                ?>
                            </ul>
                            <ul  class="new-service-scroll">
                                <li>
                                    
                                    <div class="panel panel-default ld-clean-services-panel ld-add-new-service">
                                        <div class="panel-heading">
                                            <h4 class="panel-title">
                                                <div class="lda-col8">
                                                    <div class="pull-left">
                                                        <i class="fa fa-th-list"></i><span class="badge" style="background-color:#555;" title="Service color badge"></span>
                                                    </div>
                                                    <span class="ld-service-title-name"></span>
                                                </div>
                                                <div class="pull-right lda-col4">
                                                    <div class="pull-right">
                                                        <div class="ld-show-hide pull-right">
                                                            <input type="checkbox" name="ld-show-hide" checked="checked" class="ld-show-hide-checkbox" id="sp3" >
                                                            <label class="ld-show-hide-label" for="sp3"></label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </h4>
                                        </div>
                                        <div id="" class="panel-collapse collapse in detail_sp3">
                                            <div class="panel-body">
                                                <div class="ld-service-collapse-div col-sm-7 col-md-6 col-lg-5 col-xs-12">
                                                    <form id="addservice_form" method="post" type="" class="slide-toggle" >
                                                        <table class="ld-create-service-table">
                                                            <tbody>
                                                            <tr>
                                                                <td><label for="ld-service-color-tag"><?php echo filter_var($label_language_values['color_tag'], FILTER_SANITIZE_STRING);	?></label></td>
                                                                <td><input type="text" name="txtcolor" id="ld-service-color-tag" class="form-control demo mycolortag"  data-control="saturation" value="#0088cc"></td>
                                                            </tr>
                                                            <tr>
                                                                <td><label for="ld-service-title"><?php echo filter_var($label_language_values['service_title'], FILTER_SANITIZE_STRING);	?></label></td>
                                                                <td><input type="text" name="txtservicetitle" class="form-control myservicetitle" id="ld-service-title" /></td>
                                                            </tr>
                                                            <tr>
                                                                <td><label for="ld-service-desc"><?php echo filter_var($label_language_values['service_description'], FILTER_SANITIZE_STRING);	?></label></td>
                                                                <td><textarea id="ld-service-desc" class="form-control myservicedesc"></textarea></td>
                                                            </tr>
                                                            <tr>
                                                                <td><label for="ld-service-desc"><?php echo filter_var($label_language_values['service_image'], FILTER_SANITIZE_STRING);	?></label></td>
                                                                <td>
                                                                    <i class="myserviceimage"></i>
                                                                    <div class="ld-clean-service-image-uploader">
                                                                        <img id="pcasserviceimage" src="<?php echo filter_var(SITE_URL, FILTER_VALIDATE_URL);	?>assets/images/default_service.png" class="ld-clean-service-image br-100" height="100" width="100">
                                                                        
                                                                        <label for="ld-upload-imagepcas" class="ld-clean-service-img-icon-label">
                                                                            <i class="ld-camera-icon-common br-100 fa fa-camera"></i>
                                                                            <i class="pull-left fa fa-plus-circle fa-2x"></i>
                                                                        </label>
                                                                        <input data-us="pcas" class="hide ld-upload-images" type="file" name="" id="ld-upload-imagepcas" />
                                                                        <a id="ld-remove-service-imagepcas" id="ld_service_image" class="pull-left br-100 btn-danger bt-remove-service-img btn-xs hide" rel="popover" data-placement='left' title="<?php echo filter_var($label_language_values['remove_image'], FILTER_SANITIZE_STRING);	?>"> <i class="fa fa-trash" title="<?php echo filter_var($label_language_values['remove_service_image'], FILTER_SANITIZE_STRING);	?>"></i></a>
                                                                        <label><b class="error-service" style="color:red;"></b></label>
                                                                        <div id="popover-ld-remove-service-imagepcas" style="display: none;">
                                                                            <div class="arrow"></div>
                                                                            <table class="form-horizontal" cellspacing="0">
                                                                                <tbody>
                                                                                <tr>
                                                                                    <td>
                                                                                        <a href="javascript:void(0)" id="" value="Delete" class="btn btn-danger btn-sm" type="submit"><?php echo filter_var($label_language_values['yes'], FILTER_SANITIZE_STRING);	?></a>
                                                                                        <a href="javascript:void(0)" id="ld-close-popover-service-imagepcas" class="btn btn-default btn-sm" href="javascript:void(0)"><?php echo filter_var($label_language_values['cancel'], FILTER_SANITIZE_STRING);	?></a>
                                                                                    </td>
                                                                                </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                    <div id="ld-image-upload-popuppcas" class="ld-image-upload-popup modal fade" tabindex="-1" role="dialog">
                                                                        <div class="vertical-alignment-helper">
                                                                            <div class="modal-dialog modal-md vertical-align-center">
                                                                                <div class="modal-content">
                                                                                    <div class="modal-header">
                                                                                        <div class="col-md-12 col-xs-12">
                                                                                            <a data-us="pcas" class="btn btn-success ld_upload_img1" data-imageinputid="ld-upload-imagepcas"><?php echo filter_var($label_language_values['crop_and_save'], FILTER_SANITIZE_STRING);	?></a>
                                                                                            <button type="button" class="btn btn-default hidemodal" data-dismiss="modal" aria-hidden="true"><?php echo filter_var($label_language_values['cancel'], FILTER_SANITIZE_STRING);	?></button>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="modal-body">
                                                                                        <img id="ld-preview-imgpcas" class="ld-preview-img" style="width: 100%;" />
                                                                                    </div>
                                                                                    <div class="modal-footer">
                                                                                        <div class="col-md-12 np">
                                                                                            <div class="col-md-12 np">
                                                                                                <div class="col-md-4 col-xs-12">
                                                                                                    <label class="pull-left"><?php echo filter_var($label_language_values['file_size'], FILTER_SANITIZE_STRING);	?></label> <input type="text" class="form-control" id="pcasfilesize" name="filesize" />
                                                                                                </div>
                                                                                                <div class="col-md-4 col-xs-12">
                                                                                                    <label class="pull-left">H</label> <input type="text" class="form-control" id="pcash" name="h" />
                                                                                                </div>
                                                                                                <div class="col-md-4 col-xs-12">
                                                                                                    <label class="pull-left">W</label> <input type="text" class="form-control" id="pcasw" name="w" />
                                                                                                </div>
                                                                                                
                                                                                                <input type="hidden" id="pcasx1" name="x1" />
                                                                                                <input type="hidden" id="pcasy1" name="y1" />
                                                                                                <input type="hidden" id="pcasx2" name="x2" />
                                                                                                <input type="hidden" id="pcasy2" name="y2" />
                                                                                                <input type="hidden" id="pcasid" name="id" value="" />
                                                                                                <label class="error_image" ></label>
                                                                                                <input id="pcasctimage" type="hidden" name="ctimage" />
                                                                                                <input type="hidden" id="lastrecordid" value="service_">
                                                                                                <input type="hidden" id="pcasctimagename" class="pcasimg" name="ctimagename" value="" />
                                                                                                <input type="hidden" id="pcasnewname" value="service_" />
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
																														<tr>
																															<td>
																																<label><?php echo filter_var($label_language_values['maximum_order_per_day'], FILTER_SANITIZE_STRING);	?></label>
																															</td>
																															<td>
																																<div class="input-group spinner">
																																	<div class="input-group-btn-horizontal">
																																		<button class="btn ld-addition btn-default input-group-addon" data-id="" type="button"><i class="fa fa-plus nm"></i></button>									
																																		<input type="text" class="form-control limit-value pass_min v_c" name="ld_maximum_order_per_day" id="limit-value" value="0">
																																		<button class="btn ld-subtraction btn-default input-group-addon" data-id="" type="button"><i class="fa fa-minus nm"></i></button>
																																	</div>
																																</div>
																															</td>
																														</tr>
																														<tr>
																															<td></td>
																															<td class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
																																<a  id="" name="" class="btn btn-success ld-btn-width myserviceaddbtn" ><?php echo filter_var($label_language_values['save'], FILTER_SANITIZE_STRING);	?></a>
																																<button id="reset_service_form" type="reset" class="btn btn-default ld-btn-width ml-30"><?php echo filter_var($label_language_values['reset'], FILTER_SANITIZE_STRING);	?></button>
																															</td>
																														</tr>
                                                            </tbody>
                                                        </table>
                                                </div>
                                                
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>
<?php 
include(dirname(__FILE__).'/footer.php');
?>
<script type="text/javascript">
    var ajax_url = '<?php echo filter_var(AJAX_URL, FILTER_VALIDATE_URL);	?>';
    var ajaxObj = {'ajax_url':'<?php echo filter_var(AJAX_URL, FILTER_VALIDATE_URL);	?>'};
    var servObj={'site_url':'<?php echo filter_var(SITE_URL, FILTER_VALIDATE_URL).'assets/images/business/';	?>'};
    var imgObj={'img_url':'<?php echo filter_var(SITE_URL, FILTER_VALIDATE_URL).'assets/images/';	?>'};
</script>