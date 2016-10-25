<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo $meta_title;?></title>

<!-- Bootstrap -->
<link rel="stylesheet" href="<?php echo base_url();?>assets/css/bootstrap.css">
<link rel="stylesheet" href="<?php echo base_url();?>assets/css/bootstrap-datetimepicker.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/bootstrap-slider.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/jquerysctipttop.css">
<link rel="stylesheet" href="<?php echo base_url();?>assets/css/style.css">
<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
<script src="<?php echo JS_PATH;?>jquery-1.11.3.min.js"></script>
<script src="<?php echo JS_PATH;?>bootstrap.js"></script>
<script src="<?php echo JS_PATH;?>moment.js"></script>
<script src="<?php echo JS_PATH;?>bootstrap-datetimepicker.js"></script>
<script src="<?php echo JS_PATH;?>bootstrap-slider.js"></script>
<script src="<?php echo JS_PATH;?>common.js"></script>
<script src="<?php echo JS_PATH;?>jquery.jsscroll.min.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCmLKGuDaI1isfsHlHtJwLEm-CMqha6tmE&v=3.exp"></script>

</head>
<body>
<!--Header and Search-->
<?php if($user_detail['staff_type']=='Operator'){
        $redirect_url='operator';
        echo "<style>.navbar-default{background-color:yellow !important;}</style>";
      }else if($user_detail['staff_type']=='Agency'){
        $redirect_url='agency';
        echo "<style>.navbar-default{background-color:cyan !important;}</style>";
      }else if($user_detail['staff_type']=='Conductor'){
        $redirect_url='conductor';
         echo "<style>.navbar-default{background-color:orange !important;}</style>";
      }else{
        $redirect_url='logout';
      }
 
?>
<nav class="navbar navbar-default navbar-fixed-top">
  <div class="container"> 
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
      <a class="navbar-brand" href="<?php echo base_url($redirect_url);?>"><img src="<?php echo base_url();?>assets/images/logo.png" width="205" height="50" alt=""/></a> </div>
    
    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <div class="user-profile">
        <ul class="nav navbar-nav navbar-right">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false" aria-haspopup="true">
              <i class="glyphicon glyphicon-option-vertical"></i>
            </a>
          <ul class="dropdown-menu">
            <li>
              <div class="profile">
                <?php if(isset($user_detail['profile_image']) && $user_detail['profile_image']!=''){
                    $profile_image=base_url().'public/uploads/staff/'.$user_detail['profile_image'];
                  }else{
                     $profile_image=base_url().'assets/images/rounded-512.png';
                    }?>
                  <div class="profile-userpic"><img src="<?php echo $profile_image;?>" width="336" height="336" alt=""/></div>
                <div class="profile-usertitle">
          <div class="profile-usertitle-name">
           <?php echo $user_detail['name'];?>
          </div>
          <div class="profile-usertitle-job">
            <?php echo $user_detail['staff_type'];?>
          </div>
        </div>
                </div>
            </li>
            <li>
              <div class="profile-usermenu">
                  <ul>
                      <li class="active"><a href="#"><i class="glyphicon glyphicon-cog"></i>Settings </a></li>
                        <li><a href="#"><i class="glyphicon glyphicon-search"></i>Info</a></li>
                        <li><a href="<?php echo base_url($redirect_url.'/logout');?>"><i class="glyphicon glyphicon-log-out"></i>Logout</a></li>
                    </ul>
                </div>
            </li>
          </ul>
        </li>
      </ul>
      </div>
      <?php if($user_detail['staff_type']!='Conductor'){?>
      <form class="navbar-form navbar-right" role="search" style="border:0" action="<?php echo base_url().$redirect_url;?>">
        <div class="form-group">
          <label class="control-label">From</label>
        <select class="form-control" id="from_city" name="from_city">
         <?php echo getStateCityDropDown($_GET['from_city']);?>
          </select>
        </div>
        <div class="form-group">
          <label class="control-label">To</label>
         <select class="form-control" id="to_city" name="to_city">
          <?php echo getStateCityDropDown($_GET['to_city']);?>
          </select>
        </div>
        <div class="form-group">
          <label class="control-label"  style="display:block">Date & Time</label>
          <div class="input-group date" id="datetimepicker1"> <span class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </span>
             <input type="text" class="form-control" required name="datetime" id="datetime" <?php if(isset($_GET['datetime'])){?> value="<?php echo $_GET['datetime'];?>" <?php }else{?>  <?php } ?> />
          </div>
        </div>
        <div class="form-group">
          <label style="display:block;margin:0">&nbsp;</label>
            <input type="hidden" name="search" value="1">
          <button type="submit" class="btn btn-default btn-search"> <span class="glyphicon glyphicon-search"></span> Search </button>
        </div>
        <div class="form-group">
        
         <span class=""><a href="<?php echo base_url().$redirect_url;?>"><img src="<?php echo base_url();?>assets/images/img_refresh.png" style="width: 34px; padding-top: 20px;"></a></span></div>
        <div class="form-group vertical-saperator">
          <label style="display:block;margin:0">&nbsp;</label>
          <div style="border-right:1px solid #dfdfe6; height:35px; margin-right:4px;">&nbsp;</div>
        </div>
        <div class="form-group">
          <label class="control-label">Sort By</label>
          <select class="form-control" name="sort_by" id="sort_by">
            <option value="">Select Path</option>
            <option value="1" <?php if(isset($_GET['sort_by']) &&  $_GET['sort_by']==1){?>  selected="selected" <?php }?>>Short Path</option>
            <option value="2" <?php if(isset($_GET['sort_by']) &&  $_GET['sort_by']==2){?>  selected="selected" <?php }?>>Long Path</option>
          </select>
        </div>
      </form>
      <?php }?>
    </div>
    <!-- /.navbar-collapse --> 
  </div>
  <!-- /.container-fluid --> 
</nav>