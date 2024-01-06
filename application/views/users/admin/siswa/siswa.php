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
          <h6 class="panel-title"><i class="icon-users"></i> Data Siswa <a class="heading-elements-toggle"><i class="icon-more"></i></a></h6>
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
              <li class="<?php if($sub_menu3 == 'tbl_siswa'){echo 'active';} ?>"><a href="#tbl_siswa" data-toggle="tab" aria-expanded="true">Tambah Data Siswa</a></li>
            </ul>

            <div class="tab-content">
            <div class="tab-pane <?php if($sub_menu3 == ''){echo 'active';} ?>" id="tbl_siswa">
                <?php
                echo $this->session->flashdata('msg_siswa');
                ?>
                <a href="users/siswa/t_siswa" class="btn btn-primary"><i class="icon-user-plus"></i> Siswa Baru</a>
                <table class="table datatable-basic" width="100%">
                  <thead>
                    <tr>
                      <th width="30px;">No.</th>
                      <th>NIS</th>
                      <th>Nama Lengkap</th>
                      <th>Telp</th>
                      <th>Nama Pembimbing</th>
                      <th class="text-center" width="130">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                      <?php
                      $no = 1;
                      foreach ($v_siswa->result() as $baris) {
                        $nama_pemb = $this->db->get_where('tbl_pembimbing', "kd_pembimbing='$baris->kd_pembimbing'")->row()->nama_lengkap;
                      ?>
                        <tr>
                          <td><?php echo $no.'.'; ?></td>
                          <td><?php echo $baris->nis_siswa; ?></td>
                          <td><?php echo $baris->nama_lengkap; ?></td>
                          <td><?php echo $baris->telp; ?></td>
                          <td><?php echo $nama_pemb; ?></td>
                          <td>
                            <a href="users/siswa/d_siswa/<?php echo $baris->nis_siswa; ?>" class="btn btn-info btn-xs"><i class="icon-eye"></i></a>
                            <a href="users/siswa/h_siswa/<?php echo $baris->nis_siswa; ?>" class="btn btn-danger btn-xs" onclick="return confirm('Apakah Anda yakin?')"><i class="icon-trash"></i></a>
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
      </div>

      <!-- /basic datatable -->
    </div>
    <!-- /dashboard content -->