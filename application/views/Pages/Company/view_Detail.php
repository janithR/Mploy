<style type="text/css">

  #buangLine{
    border: none;
    background-color: transparent;
    resize: none;
    outline: none;
  }

</style>

<?php foreach ($output as $data): ?>
  <!-- Horizontal Form -->
  <div class="box-header with-border">
    <div class="col-md-6">
      <h3 class="box-title">Profile</h3>
    </div>

    <div class="col-md-6">
      <span class="pull-right">

        <button class="btn btn-default" id = "butangBack"><i class="fa fa-arrow-left"> </i> Back</button>

      </span>
    </div>
  </div>
  <!-- /.box-header -->
  <!-- form start -->


  <form class="form-horizontal" id="dataCryo" method="post" enctype="multipart/form-data">

    <div class="box-body">

      <div class="form-group">
        <label for="" class="col-sm-2 control-label">Company Name : </label>

        <div class="col-sm-6">
          <h5 class="col-sm-6 "><?php echo $data->company_name; ?></h5>
        </div>
      </div>


      <div class="form-group">
        <label for="" class="col-sm-2 control-label">Company Type : </label>

        <div class="col-sm-6">
          <h5 class="col-sm-6 "><?php echo $data->type; ?></h5>
        </div>
      </div>

        <div class="form-group">
            <label for="" class="col-sm-2 control-label">Company Size : </label>

            <div class="col-sm-6">
                <h5 class="col-sm-6 "><?php echo $data->size; ?></h5>
            </div>
        </div>

        <div class="form-group">
            <label for="" class="col-sm-2 control-label">Country : </label>

            <div class="col-sm-6">
                <h5 class="col-sm-6 "><?php echo $data->country; ?></h5>
            </div>
        </div>


        <div class="form-group">
            <label for="" class="col-sm-2 control-label">Email : </label>

            <div class="col-sm-6">
                <h5 class="col-sm-6 "><?php echo $data->email; ?></h5>
            </div>
        </div>

        <div class="form-group">
        <label for="" class="col-sm-2 control-label">Address : </label>

        <div class="col-sm-6">
          <h5 class="col-sm-6 "><?php echo $data->address; ?></h5>
        </div>
      </div>

      <div class="form-group">
        <label for="" class="col-sm-2 control-label">Contact Number : </label>

        <div class="col-sm-6">
          <h5 class="col-sm-6 "><?php echo $data->contact_no; ?></h5>
        </div>
      </div>

        <div class="form-group">
            <label for="" class="col-sm-2 control-label">Linked In : </label>

            <div class="col-sm-6">
                <h5 class="col-sm-6 "><?php echo $data->linkedin; ?></h5>
            </div>
        </div>

        <div class="form-group">
            <label for="" class="col-sm-2 control-label">Website : </label>

            <div class="col-sm-6">
                <h5 class="col-sm-6 "><?php echo $data->website; ?></h5>
            </div>
        </div>






    <?php endforeach; ?>


  </div>
</form>
<!-- /.box -->



<script>
$(document).ready(function () {

  $('#butangBack').unbind('click').click(function () {
    $.ajax({
      url : "<?php echo base_url();?>Company/employers",
      success: function (result) {
        $('#dynamicPart').empty().html(result).fadeIn('slow');
      }});
  })
})
</script>

