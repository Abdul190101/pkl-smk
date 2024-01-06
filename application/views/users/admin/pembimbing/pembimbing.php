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
          <h6 class="panel-title"><i class="icon-users"></i> Data Pembimbing <a class="heading-elements-toggle"><i class="icon-more"></i></a></h6>
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
              <li class="<?php if($sub_menu3 == ''){echo 'active';} ?>"><a href="#tbl_pembimbing" data-toggle="tab" aria-expanded="true">Tambah Data Pembimbing</a></li>
            </ul>

            <div class="tab-content">
              <div class="tab-pane <?php if($sub_menu3 == ''){echo 'active';} ?>" id="tbl_pembimbing">

                <?php
                echo $this->session->flashdata('msg_pemb');
                ?>
                <a href="users/pembimbing/t_pemb" class="btn btn-primary"><i class="icon-user-plus"></i> Pembimbing Baru</a>

                <table class="table datatable-basic" width="100%">
                  <thead>
                    <tr>
                      <th width="30px;">No.</th>
                      <th>Username</th>
                      <th>Nama Lengkap</th>
                      <th>NIP</th>
                      <th class="text-center" width="130">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                      <?php
                      $no = 1;
                      foreach ($v_pemb->result() as $baris) {
                      ?>
                        <tr>
                          <td><?php echo $no.'.'; ?></td>
                          <td><?php echo $baris->username; ?></td>
                          <td><?php echo $baris->nama_lengkap; ?></td>
                          <td><?php echo $baris->nip; ?></td>
                          <td>
                            <a href="users/pembimbing/d_pemb/<?php echo $baris->kd_pembimbing; ?>" class="btn btn-info btn-xs"><i class="icon-eye"></i></a>
                            <!-- <a href="users/pengguna/e_pemb/<?php echo $baris->kd_pembimbing; ?>" class="btn btn-success btn-xs"><i class="icon-pencil7"></i></a> -->
                            <a href="users/pembimbing/h_pemb/<?php echo $baris->kd_pembimbing; ?>" class="btn btn-danger btn-xs" onclick="return confirm('Apakah Anda yakin?')"><i class="icon-trash"></i></a>
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
