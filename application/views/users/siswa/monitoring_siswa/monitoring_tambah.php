<script src="public/backend/assets/js/jquery-ui.js"></script>
<script>
  $(function() {
    $("#datepicker").datepicker();
  });
</script>
<div class="content-wrapper">
  <!-- Content area -->
  <div class="content">

    <!-- Dashboard content -->

    <div class="row">
      <!-- Basic datatable -->
      <div class="col-md-3"></div>
      <div class="panel panel-flat col-md-12">
        <div class="panel-heading">
          <h5 class="panel-title">Tambah Data Log Book</h5>
          <div class="heading-elements">
            <ul class="icons-list">
              <li><a data-action="collapse"></a></li>
            </ul>
          </div>

        </div>
        <hr style="margin:0px;">
        <div class="panel-body">
          <?php
          echo $this->session->flashdata('msg');
          ?>
          <form action="" method="post" enctype="multipart/form-data">
            <div class="col-sm-12 pull-left" style="margin-top: 10px;">
              <label for="nama"><b>Nama Industri</b></label>
              <select class="form-control cari_industri" name="kd_industri" required>
                <option value="">-- Pilih Industri --</option>
                <?php foreach ($v_industri->result() as $baris) : ?>
                  <option value="<?php echo $baris->kd_industri; ?>"><?php echo "$baris->nama_industri"; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-sm-12 pull-left" style="margin-top: 10px;">
              <label for="nama"><b>Nama Siswa</b></label>
              <select class="form-control cari_siswa" name="nis_siswa" required>
                <option value="">-- Pilih Siswa --</option>
                <?php foreach ($v_siswa->result() as $baris) : ?>
                  <option value="<?php echo $baris->nis_siswa; ?>"><?php echo "$baris->nama_lengkap [NIS: $baris->nis_siswa]"; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-sm-12 pull-left" style="margin-top: 10px;">
              <label for="judul"><b>Kegiatan</b></label>
              <textarea  type="text" class="form-control" id="kegiatan" name="kegiatan" value="" placeholder="kegiatan" maxlength="50" required></textarea>
            </div>
            <div class="col-sm-12 pull-left" style="margin-top: 10px;">
              <label for="file"><b>Foto</b></label>
              <input type="file" name="image" class="form-control" value="" placeholder="Foto" required>
              <b style="color:red;font-size:10px;">*Max Size: 5 MB</b>
            </div>
            <div class="col-sm-12 pull-left" style="margin-top: 10px;">
              <hr>
              <a href="javascript:history.back()" class="btn btn-default">Kembali</a>
              <button type="submit" name="btnsimpan" class="btn btn-primary" style="float:right;">Simpan</button>
              <button type="reset" class="btn btn-default" style="float:right;margin-right:10px;">Reset</button>
            </div>
          </form>

        </div>
      </div>
      <!-- /basic datatable -->
    </div>
    <!-- /dashboard content -->