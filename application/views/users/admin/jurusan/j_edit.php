<?php
$sub_menu3 = strtolower($this->uri->segment(3));
$user = $query; ?>
<!-- Main content -->
<div class="content-wrapper">
  <!-- Content area -->
  <div class="content">

    <!-- Dashboard content -->
    <div class="row">
      <div class="col-md-3"></div>
      <div class="col-md-12">
        <div class="panel panel-flat">

          <div class="panel-body">

            <fieldset class="content-group">
              <legend class="text-bold"><i class="icon-user"></i> Edit <?php if ($sub_menu3 == 'e_jurusan') {
                                                                          echo "Jurusan";
                                                                        } ?></legend>
              <?php
              echo $this->session->flashdata('msg');
              if ($sub_menu3 == 'e_jurusan') { ?>
                <form class="form-horizontal" action="" method="post">
                  <div class="form-group">
                    <label class="control-label col-lg-3">Nama Jurusan</label>
                    <div class="col-lg-9">
                      <input type="text" name="jurusan" class="form-control" value="<?php echo $user->nama; ?>" placeholder="Nama Jurusan" required>
                    </div>
                  </div>
                  <a href="javascript:history.back()" class="btn btn-default">
                    << Kembali</a>
                      <button type="submit" name="btnupdate" class="btn btn-primary" style="float:right;">Update</button>
                </form>
              <?php
              } ?>

            </fieldset>


          </div>

        </div>
      </div>
    </div>
    <!-- /dashboard content -->