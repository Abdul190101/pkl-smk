<?php
$sub_menu3 = strtolower($this->uri->segment(3)); ?>
<!-- Main content -->
<div class="content-wrapper">
  <!-- Content area -->
  <div class="content">
    <!-- Dashboard content -->
    <div class="row">
      <!-- Basic datatable -->
      <div class="panel panel-flat">
        <div class="panel-heading">
          <h6 class="panel-title"><i class="icon-database"></i> Data Jurusan <a class="heading-elements-toggle"><i class="icon-more"></i></a></h6>
          <div class="heading-elements">
            <ul class="icons-list">
              <li><a data-action="collapse"></a></li>
              <li><a data-action="close"></a></li>
            </ul>
          </div>
        </div>
        <div class="panel-body">
          <div class="tabbable">
            <ul class="nav nav-tabs nav-tabs-highlight nav-justified">
              <li class="<?php if ($sub_menu3 == '') {
                            echo 'active';
                          } ?>"><a href="#tbl_jurusan" data-toggle="tab" aria-expanded="true">Tambah Data Jurusan</a></li>
            </ul>

            <div class="tab-content">
              <div class="tab-panel <?php if ($sub_menu3 == '') {echo 'active';} ?>" id="tbl_jurusan">
                <?php
                echo $this->session->flashdata('msg_jurusan');
                ?>

                <form action="" method="post">
                  <div class="form-group">
                    <br>
                    <label class="control-label col-lg-2">Nama Jurusan</label>
                    <div class="col-lg-8">
                      <input type="text" name="jurusan" class="form-control" value="" placeholder="Nama Jurusan" maxlength="20" required>
                    </div>
                    <div class="col-lg-2">
                      <input type="submit" name="btnsimpan" class="btn btn-primary" value="Tambah">
                    </div>
                    <br>
                    <br>
                  </div>
                </form>

                <div class="form-group">
                  <div class="col-lg-12">
                    <hr>
                  </div>
                </div>

                <table class="table datatable-basic" width="100%">
                  <thead>
                    <tr>
                      <th width="30px;">No.</th>
                      <th>Nama Jurusan</th>
                      <th class="text-center" width="170">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $no = 1;
                    foreach ($v_jurusan->result() as $baris) {
                    ?>
                      <tr>
                        <td><?php echo $no . '.'; ?></td>
                        <td><?php echo $baris->nama; ?></td>
                        <td>
                          <a href="users/jurusan/e_jurusan/<?php echo $baris->kd_jurusan; ?>" class="btn btn-success btn-xs"><i class="icon-pencil7"></i></a>
                          <a href="users/jurusan/h_jurusan/<?php echo $baris->kd_jurusan; ?>" class="btn btn-danger btn-xs" onclick="return confirm('Apakah Anda yakin?')"><i class="icon-trash"></i></a>
                        </td>
                      </tr>
                    <?php
                      $no++;
                    } ?>
                  </tbody>
                </table>
              </div>
            </div>

          </div>
        </div>
      </div>

      <!-- /basic datatable -->
    </div>
    <!-- /dashboard content -->