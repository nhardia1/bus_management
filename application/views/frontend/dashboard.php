<?php $this->load->view('frontend/head'); ?>
<div class="container outer">
  <!--Bus list Begins, Repeat this div for bus list-->
    <div class="bus-list clearfix">
        <div class="col-md-12" style="padding:0">
      <div class="col-md-10">
        <input id="ex4" type="text" data-slider-ticks="[0, 50, 100, 150, 200, 250, 300, 450]" data-slider-ticks-snap-bounds="30" data-slider-ticks-labels="["$0", "$100", "$200", "$300", "$400"]"/>
      </div>
    
        <div class="col-md-1" style="padding:0">
            <div class="pull-right thumbnails">
            <a class="bus" id="bus1"> <img src="<?php echo IMAGE_PATH;?>bus.jpg" name="aboutme" width="60" height="60" id="aboutme"> </a>
          </div>
        </div>
        <div class="col-md-1" style="padding:0;">
          <div class="pull-right thumbnails">
            <a href="#aboutModal" data-toggle="modal" data-target="#myModal"> <img src="<?php echo IMAGE_PATH;?>driver.png" name="aboutme" width="60" height="60"> </a>
          </div>
        </div>
        
        <!--Bus Photo-->
        <div class="modal fade" id="modal-gallery" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
                 <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span class="glyphicon glyphicon-remove-circle"></span></button>
                <h4 class="modal-title">MP09 AB 1234</h4>
          </div>
          <div class="modal-body">
              <div id="modal-carousel" class="carousel">
                <div class="carousel-inner">
                </div>
              </div>
          </div>
          <div class="modal-footer">
            <a class="btn btn-primary pull-left" href="#modal-carousel" data-slide="prev"><i class="glyphicon glyphicon-chevron-left"></i></a>
             <a class="right btn btn-primary" href="#modal-carousel" data-slide="next"><i class="glyphicon glyphicon-chevron-right"></i></a>
          </div>
        </div>
      </div>
    </div>
        <!--bus Photo-->
        
        <!--Driver Photo-->
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
          <div class="modal-dialog" style="width:332px;">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span class="glyphicon glyphicon-remove-circle"></span></button>
                <h4 class="modal-title" id="myModalLabel">Santosh Sahu<br>
                  989 564 3587</h4>
              </div>
              <div class="modal-body">
                <center>
                  <img src="<?php echo IMAGE_PATH;?>/driver.png" name="aboutme" width="300" height="300" border="0">
                </center>
              </div>
            </div>
          </div>
        </div>
        <!--/Driver Photo-->
    </div>
    </div>
    <div class="bus-list clearfix">
        <div class="col-md-12" style="padding:0">
      <div class="col-md-10">
        <input id="ex6" type="text"/>
      </div>
    
        <div class="col-md-1" style="padding:0">
            <div class="pull-right thumbnails">
            <a class="bus" id="bus1"> <img src="<?php echo IMAGE_PATH;?>bus.jpg" name="aboutme" width="60" height="60" id="aboutme"> </a>
          </div>
        </div>
        <div class="col-md-1" style="padding:0;">
          <div class="pull-right thumbnails">
            <a href="#aboutModal" data-toggle="modal" data-target="#myModal"> <img src="<?php echo IMAGE_PATH;?>driver.png" name="aboutme" width="60" height="60"> </a>
          </div>
        </div>
        
        <!--Bus Photo-->
        <div class="modal fade" id="modal-gallery" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
                 <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span class="glyphicon glyphicon-remove-circle"></span></button>
                <h4 class="modal-title">MP09 AB 1234</h4>
          </div>
          <div class="modal-body">
              <div id="modal-carousel" class="carousel">
                <div class="carousel-inner">
                </div>
              </div>
          </div>
          <div class="modal-footer">
            <a class="btn btn-primary pull-left" href="#modal-carousel" data-slide="prev"><i class="glyphicon glyphicon-chevron-left"></i></a>
             <a class="right btn btn-primary" href="#modal-carousel" data-slide="next"><i class="glyphicon glyphicon-chevron-right"></i></a>
          </div>
        </div>
      </div>
    </div>
        <!--bus Photo-->
        
        <!--Driver Photo-->
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
          <div class="modal-dialog" style="width:332px;">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span class="glyphicon glyphicon-remove-circle"></span></button>
                <h4 class="modal-title" id="myModalLabel">Santosh Sahu<br>
                  989 564 3587</h4>
              </div>
              <div class="modal-body">
                <center>
                  <img src="<?php echo IMAGE_PATH;?>/driver.png" name="aboutme" width="300" height="300" border="0">
                </center>
              </div>
            </div>
          </div>
        </div>
        <!--/Driver Photo-->
    </div>
    </div>
    <!--Bus list End-->
    
    <!--Bus Details Begins-->
  <!--<div class="bus-details clearfix">
      <div class="col-md-9 bus-frame clearfix">
          <ul style="list-style:none;padding:0">
              <li style="width:90px" class="pull-left">
                  <div class="stearing"></div>
                </li>
                <li style="border-left:4px solid #706f6d;" class="pull-left">
                  <div class="busrow clearfix">
                      <ul>
                          <li class="seat"><a href="#"></a></li>
                            <li class="seat"><a href="#"></a></li>
                            <li class="seat"><a href="#"></a></li>
                            <li class="seat"><a href="#"></a></li>
                            <li class="seat"><a href="#"></a></li>
                            <li class="seat"><a href="#"></a></li>
                            <li class="seat"><a href="#"></a></li>
                            <li class="seat"><a href="#"></a></li>
                            <li class="seat"><a href="#"></a></li>
                            <li class="seat"><a href="#"></a></li>
                            <li class="seat"><a href="#"></a></li>
                            <li class="seat"><a href="#"></a></li>
                            <li class="seat"><a href="#"></a></li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
        <div class="col-md-3" style="background-color:blue">2</div>
  </div>-->
    <!--Bus Details End-->
    
</div>
    <!--bus Photo-->
    
    <!--Driver Photo-->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog" style="width:332px;">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span class="glyphicon glyphicon-remove-circle"></span></button>
            <h4 class="modal-title" id="myModalLabel">Santosh Sahu<br>
              989 564 3587</h4>
          </div>
          <div class="modal-body">
            <center>
              <img src="<?php echo IMAGE_PATH;?>driver.png" name="aboutme" width="300" height="300" border="0">
            </center>
          </div>
        </div>
      </div>
    </div>
    <!--/Driver Photo-->
</div>
	<hr>
</div>
<?php $this->load->view('frontend/foot'); ?>
