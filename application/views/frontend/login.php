<?php $this->load->view('frontend/plain-head'); ?>
<div class="container" style="margin:15% auto 15% auto">
  <form role="form" id="feedbackForm" class="text-center" action="<?php echo base_url();?>main/check_pin" method="post">
  <div class="col-md-4  col-md-offset-4">
      <div class="clearfix text-center"><h4 style="font-weight:normal; font-size:20px; margin-bottom:15px">Please enter your 4 digits PIN number.</h4></div>
      <font color="clearfix text-center" style="color:red;"><?php echo $this->session->flashdata('error'); ?></font>
      <div class="input-group">
          <input type="text" class="form-control" placeholder="Please enter PIN here" id="pincode" name="pincode" required>
              <span class="input-group-btn">
                  <button class="btn btn-warning" type="submit">Submit</button>
                </span>
        </div>
        
    </div>
  </form>  
</div>
<?php $this->load->view('frontend/foot'); ?>
