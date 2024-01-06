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
                <legend class="text-bold"><i class="icon-office"></i> Detail Industri <?php echo ucwords($user->nama_industri); ?></legend>
                  <center>
                    <img src="foto/<?php if ($user->foto == '') { echo'default.png'; }else{echo "industri/$user->foto";}?>" alt="<?php echo $user->nama_industri; ?>" class="img-circle" width="176" height="176">
                    <br>
                    <b>
                      Foto Industri <br>
                       <?php echo ucwords($user->nama_industri); ?>
                    </b>
                  </center>
                  <hr>
                  <table width="100%" border=0>
                      <tr>
                        <th width="30%"><b>Nama Industri</b></th>
                        <td width="2%"><b>:</b>&nbsp; </td>
                        <td> <b><?php echo $user->nama_industri; ?></b></td>
                      </tr>
                      <tr>
                        <th><b>Bidang Kerja</b></th>
                        <td><b>:</b>&nbsp; </td>
                        <td> <?php echo ucwords($user->bidang_kerja); ?></td>
                      </tr>
                      <tr>
                        <th><b>Deskripsi</b></th>
                        <td><b>:</b>&nbsp; </td>
                        <td> <?php echo ucwords($user->deskripsi); ?></td>
                      </tr>
                      <tr>
                        <th><b>Alamat Industri</b></th>
                        <td><b>:</b>&nbsp; </td>
                        <td> <?php echo ucwords($user->alamat_industri); ?></td>
                      </tr>
                      <tr>
                        <th><b>Wilayah</b></th>
                        <td><b>:</b>&nbsp; </td>
                        <td> <?php echo ucwords($user->wilayah); ?></td>
                      </tr>
                      <tr>
                        <th><b>Telepon</b></th>
                        <td><b>:</b>&nbsp; </td>
                        <td> <?php echo ucwords($user->telepon); ?></td>
                      </tr>
                      <tr>
                        <th><b>Kuota</b></th>
                        <td><b>:</b>&nbsp; </td>
                        <td> <?php echo ucwords($user->kuota); ?></td>
                      </tr>
                  </table>

                <hr>
                  <a href="javascript:history.back()" class="btn btn-default"><< Kembali</a>

              </fieldset>


            </div>

        </div>
      </div>
    </div>
    <!-- /dashboard content -->
