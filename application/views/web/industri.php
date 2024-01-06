<main id="hero" class="align-items-center bg-primary">
    <div class="container">
        <div class="row">
            <div class="container" style="margin-bottom:-40px;">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <h3 class="text-center text-white">Halaman Industri</h3>
                        <table class="table datatable-basic table-striped mt-5" width="100%">
                            <thead>
                                <tr>
                                    <th width="30px;">No.</th>
                                    <th width="50">Logo</th>
                                    <th>Nama Industri</th>
                                    <th>Bidang Usaha</th>
                                    <th>Wilayah</th>
                                    <th>Telpon</th>
                                    <th class="text-center" width="70">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                foreach ($v_industri->result() as $baris) {
                                ?>
                                    <tr>
                                        <td><?php echo $no . '.'; ?></td>
                                        <td><img src="foto/industri/<?php echo $baris->foto; ?>" alt="industri" width="50" height="50"></td>
                                        <td><?php echo $baris->nama_industri; ?></td>
                                        <td><?php echo $baris->bidang_kerja; ?></td>
                                        <td><?php echo $baris->wilayah; ?></td>
                                        <td><?php echo $baris->telepon; ?></td>
                                        <td>
                                            <a href="web/industri/<?php echo $baris->kd_industri; ?>" class="btn btn-info btn-xs">Detail</a>
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
</main><!-- End Hero -->