<?php
$sub_menu3 = strtolower($this->uri->segment(3)); ?>
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
                <legend class="text-bold"><i class="icon-office"></i> Tambah Industri</legend>
                <?php
                echo $this->session->flashdata('msg');?>
                  <form class="form-horizontal" action="" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                      <label class="control-label col-lg-3">Nama Industri</label>
                      <div class="col-lg-9">
                        <input type="text" name="nama_industri" class="form-control" value="" placeholder="Nama Industri" maxlength="50" required>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-lg-3">Bidang Kerja</label>
                      <div class="col-lg-9">
                        <input type="text" name="bidang_kerja" class="form-control" value="" placeholder="Bidang Kerja" maxlength="50" required>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-lg-3">Deskripsi</label>
                      <div class="col-lg-9">
                        <textarea name="deskripsi" rows="4" cols="80" class="form-control" placeholder="Deskripsi" required></textarea>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-lg-3">ALamat Industri</label>
                      <div class="col-lg-9">
                        <textarea name="alamat_industri" rows="4" cols="80" class="form-control" placeholder="ALamat Industri" required></textarea>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-lg-3">Wilayah</label>
                      <div class="col-lg-9">
                        <input type="text" name="wilayah" class="form-control" value="" placeholder="Wilayah" maxlength="50" required>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-lg-3">Telepon</label>
                      <div class="col-lg-9">
                        <input type="text" name="telepon" class="form-control" value="" placeholder="Telepon" maxlength="20" required>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-lg-3">Kuota</label>
                      <div class="col-lg-9">
                        <input type="number" name="kuota" class="form-control" value="" placeholder="Kuota" maxlength="20" required>
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="control-label col-lg-3">Foto</label>
                      <div class="col-lg-9">
                        <input type="file" name="file" class="form-control" value="" placeholder="Foto" required>
                        <b style="color:red;font-size:10px;">*Max Size: 5 MB</b>
                      </div>
                    </div>

                    <a href="javascript:history.back()" class="btn btn-default"><< Kembali</a>
                    <button type="submit" name="btnsimpan" class="btn btn-primary" style="float:right;">Simpan</button>
                  </form>
              </fieldset>


            </div>

        </div>
      </div>
    </div>
    <!-- /dashboard content -->
