<?php
$info =  $this->session->userdata('user_details');
$pro_name = $info['name'];
$pro_photo = $info['photo'];
?>

<header class="header-container ng-scope header-fixed bg-white" id="header">

<header class="top-header clearfix ng-scope">

       
    <div class="logo bg-primary">
        <a href="<?php echo base_url();?>index.php/home">
            <span class="imd imd-directions-bus"></span>
            <span class="logo-text ng-binding">Bus Management</span>
        </a>
    </div>

    
    <div class="menu-button">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
    </div>

    <!--input type="hidden" name="current_view_type" id="current_view_type" value="<?php //echo $_SESSION['view_type']?>" /-->
    <div class="top-nav">
        <ul class="nav-left list-unstyled" >
            <li>
                <a class="toggle-min ui-wave" id="top_nav_btn" ui-wave="" href="#/"><i class="imd imd-menu"></i></a>
            </li> 

            <li>
                <a href="javascript:void(0)" class="quick_links"><i class="glyphicon glyphicon-th"></i></a>
            </li> 

        </ul> 


        <ul class="nav-right pull-right list-unstyled">
            
            <li class="dropdown langs text-normal">                
                <ul class="langselect with-arrow  pull-right list-langs scaleInRight animated" role="menu" style="display:block !important;">
                    <li>
                        <a href="<?php echo base_url();?>index.php/language/load/hindi"><div class="flag flags-india"></div></a>
                    </li>

                    <li>
                        <a href="<?php echo base_url();?>index.php/language/load"><div class="flag flags-american"></div></a>
                    </li>
                    
                     
                    
                    <!--li>
                        <div id="google_translate_element"></div><script type="text/javascript">
function googleTranslateElementInit() {
  new google.translate.TranslateElement({pageLanguage: 'en', includedLanguages: 'en,hi,mr,te,ur', layout: google.translate.TranslateElement.InlineLayout.SIMPLE}, 'google_translate_element');
}
</script><script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
                    </li-->
                                    
                </ul>
            </li>


            <li dropdown="" class="dropdown text-normal nav-profile">
                <a class="dropdown-toggle ui-wave" href="<?php echo base_url();?>index.php/home/profile_edit" aria-haspopup="true" aria-expanded="false">
                    <img class="img-circle img30_30" alt="" src="<?php echo base_url();?>public/uploads/user/<?php echo $pro_photo;?>">
                    <span class="hidden-xs">
                        <span class="ng-scope"><?php echo $pro_name;?></span>
                    </span>
                </a>
                <ul class="dropdown-menu with-arrow pull-right scaleInRight animated">
                    <li>
                        <a href="#/">
                            <i class="imd imd-home"></i>
                            <span class="ng-scope">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="#/pages/profile">
                            <i class="imd imd-person"></i>
                            <span class="ng-scope">My Profile</span>
                        </a>
                    </li>
                    <li>
                        <a href="#/pages/signin">
                            <i class="imd imd-forward"></i>
                            <span class="ng-scope">Logout</span>
                        </a>
                    </li>
                </ul>
            </li>


            
           
        </ul>
    </div>

</header>
</header>

