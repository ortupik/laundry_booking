<?php  

include_once('../header.php');
if($_FILES['image']["type"]){		
        $iWidth = $iHeight = 200;
		
		$iJpgQuality = 90;	
		 $hh=filter_var($_POST['h'], FILTER_SANITIZE_STRING);
		 $ww=filter_var($_POST['w'], FILTER_SANITIZE_STRING);
		 $xx1=filter_var($_POST['x1'], FILTER_SANITIZE_STRING);
		 $yy1=filter_var($_POST['y1'], FILTER_SANITIZE_STRING);	
		 $xx2=filter_var($_POST['x2'], FILTER_SANITIZE_STRING);
		$yy2=filter_var($_POST['y2'], FILTER_SANITIZE_STRING);
		$newfilename=filter_var($_POST['newname'], FILTER_SANITIZE_STRING);	
		$newfolderpath="";
		 if($_FILES){
		 
		$hh=filter_var($_POST['h'], FILTER_SANITIZE_STRING);
		$ww=filter_var($_POST['w'], FILTER_SANITIZE_STRING);
		$xx1=filter_var($_POST['x1'], FILTER_SANITIZE_STRING);
		$yy1=filter_var($_POST['y1'], FILTER_SANITIZE_STRING);
		$xx2=filter_var($_POST['x2'], FILTER_SANITIZE_STRING);
		$yy2=filter_var($_POST['y2'], FILTER_SANITIZE_STRING);		
		$newfilename=filter_var($_POST['newname'], FILTER_SANITIZE_STRING);
		
             /* if no errors and size less than 250kb */
            if (! $_FILES['image']['error'] && $_FILES['image']['size'] < 1300 * 1300) {
                    /* new unique filename */
					$filename = explode('.',$_FILES['image']['name']);
					
						$newfolderpath=realpath('../assets/images/services')."/";
						
						if(!file_exists($newfolderpath)){
						mkdir($newfolderpath,0755);						
						}
						$sTempFileName=$newfolderpath.$newfilename;
					
                    /*  move uploaded file into cache folder */
                    move_uploaded_file($_FILES['image']['tmp_name'], $sTempFileName);
                    
                      
                        $aSize = getimagesize($sTempFileName); 
                      if (!$aSize) {
                            @unlink($sTempFileName);
                            return;
                        }
                        /*  check for image type */
                        switch($aSize[2]) {
                            case IMAGETYPE_JPEG:
                                $sExt = '.jpg';
                                /*  create a new image from file */
                                $vImg = @imagecreatefromjpeg($sTempFileName);
                                break;
                            case IMAGETYPE_PNG:
                                $sExt = '.png';
                                /*  create a new image from file */
                                $vImg = @imagecreatefrompng($sTempFileName);
                                break;
                            default:
                                unlink($sTempFileName);
                                return;
                        }
                        /*  create a new true color image */
                        $vDstImg = @imagecreatetruecolor( $iWidth, $iHeight );
                        /*  copy and resize part of an image with resampling */
                        /*  define a result image filename */
                        $sResultFileName = $sTempFileName . $sExt;
                        /*  output image to file */
                        imagejpeg($vDstImg, $sResultFileName, $iJpgQuality);
                        @unlink($sTempFileName);
						
						echo filter_var($newfilename.$sExt, FILTER_SANITIZE_STRING);
					}
			}
		}	
	 
?>