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
          <h6 class="panel-title"><i class="icon-link2"></i> Data Tempat PKL<a class="heading-elements-toggle"><i class="icon-more"></i></a></h6>
          <div class="heading-elements">
            <ul class="icons-list">
              <li><a data-action="collapse"></a></li>
              <li><a data-action="close"></a></li>
            </ul>
          </div>
        </div>

        <div class="panel-body">
          <?php
          echo $this->session->flashdata('msg');
          ?>
          <table class="table datatable-basic" width="100%">
            <thead>
              <tr>
                <th width="30px;">No.</th>
                <th>NIS</th>
                <th>Nama Siswa</th>
                <th>Nama Pembimbing</th>
                <th>Nama Industri</th>
                <th>Tahun</th>
                <th>Status</th>
                <th class="text-center" width="240">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php
              error_reporting(0);
              $no = 1;
              foreach ($v_penempatan->result() as $baris) {
                $nama_siswa = $this->db->get_where('tbl_siswa', "nis_siswa='$baris->nis_siswa'")->row()->nama_lengkap;
                if ($nama_siswa == '') {
                  $nama_siswa = '-';
                }
                $cek_pembimbing = $this->db->get_where('tbl_pembimbing', "kd_pembimbing='$user->kd_pembimbing'")->row();
                if ($cek_pembimbing->kd_pembimbing == '') {
                  $nama_pembimbing = '-';
                } else {
                  $nama_pembimbing = $cek_pembimbing->nama_lengkap;
                }
                $nama_pembimbing = $this->db->get_where('tbl_pembimbing', "kd_pembimbing='$baris->kd_pembimbing'")->row()->nama_lengkap;
                if ($nama_pembimbing == '') {
                  $nama_pembimbing = '-';
                }
                $nama_industri = $this->db->get_where('tbl_industri', "kd_industri='$baris->kd_industri'")->row()->nama_industri;
                if ($nama_industri == '') {
                  $nama_industri = '-';
                }
              ?>
                <tr>
                  <td><?php echo $no . '.'; ?></td>
                  <td><?php echo $baris->nis_siswa; ?></td>
                  <td><?php echo $nama_siswa; ?></td>
                  <td><?php echo $nama_pembimbing; ?></td>
                  <td><?php echo $nama_industri; ?></td>
                  <td><?php echo $baris->tahun; ?></td>
                  <td>
                    <?php
                    if ($baris->status == 'proses') { ?>
                      <label class="label label-warning">Proses</label>
                    <?php
                    } elseif ($baris->status == 'ditolak') { ?>
                      <label class="label label-danger">Ditolak</label>
                    <?php
                    } elseif ($baris->status == 'diterima') { ?>
                      <label class="label label-success">Diterima</label>
                    <?php
                    } else {
                      echo "-";
                    }
                    ?>
                  </td>
                  <td>
                    <a <?php if ($baris->status != 'ditolak') { ?>href="#" data-toggle="modal" data-target=".modal_tolak_<?php echo $no; ?>" <?php } else { ?>href="users/tempat/tolak/<?php echo $baris->kd_tempat; ?>" <?php } ?> class="btn btn-warning btn-xs">
                      <?php if ($baris->status == 'ditolak') {
                        echo "Batal";
                      } else {
                        echo "Tolak";
                      }
                      ?>
                    </a>
                    <a href="users/tempat/setujui/<?php echo $baris->kd_tempat; ?>" class="btn btn-success btn-xs" onclick="return confirm('Anda yakin?')">
                      <?php if ($baris->status == 'diterima') {
                        echo "Batal";
                      } else {
                        echo "Setujui";
                      }
                      ?>
                    </a>
                    <a href="users/tempat/d/<?php echo $baris->kd_tempat; ?>" class="btn btn-info btn-xs"><i class="icon-eye"></i></a>
                    <a href="users/tempat/h/<?php echo $baris->kd_tempat; ?>" class="btn btn-danger btn-xs" onclick="return confirm('Anda yakin?')"><i class="icon-trash"></i></a>
                  </td>
                </tr>

                <div class="modal fade modal_tolak_<?php echo $no; ?>" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                  <div class="modal-dialog modal-md">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <h4 class="modal-title">Alasan ditolak</h4>
                      </div>
                      <hr>
                      <form action="" method="post">
                        <div class="modal-body">
                          <input type="hidden" name="kd_tempat_<?php echo $no; ?>" value="<?php echo $baris->kd_tempat; ?>">
                          <textarea name="pesan_<?php echo $no; ?>" class="form-control" rows="2" cols="80" placeholder="Alasan ditolak?" required></textarea>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                          <button type="submit" name="btntolak_<?php echo $no; ?>" class="btn btn-primary" onclick="return confirm('Anda yakin?')">Kirim</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              <?php
                $no++;
              } ?>
            </tbody>
          </table>


        </div>
      </div>
    </div>

    <!-- /basic datatable -->
  </div>
  <!-- /dashboard content -->