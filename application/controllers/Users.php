<?php
defined('BASEPATH') or exit('No direct script access allowed');
error_reporting(0);
class Users extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		// load library Excell_Reader
		$this->load->library(array('PHPExcel', 'PHPExcel/IOFactory'));
	}


	// Controller Default
	public function index()
	{
		$ceks = $this->session->userdata('pkl_smk@TA-D3-2023');
		$id_user = $this->session->userdata('id_user@PKL-2023');
		$level = $this->session->userdata('level@PKL-2023');
		if (!isset($ceks)) {
			redirect('web/login');
		} else {
			if ($level == 'admin') {
				redirect('users/profile');
			} elseif ($level == 'pembimbing') {
				redirect('users/profile');
			} else {
				redirect('users/status_pkl');
			}
		}
	}

	// Controller Format
	public static function format($date)
	{
		$str = explode('-', $date);
		$bulan = array(
			'01' => 'Januari',
			'02' => 'Februari',
			'03' => 'Maret',
			'04' => 'April',
			'05' => 'Mei',
			'06' => 'Juni',
			'07' => 'Juli',
			'08' => 'Agustus',
			'09' => 'September',
			'10' => 'Oktober',
			'11' => 'November',
			'12' => 'Desember',
		);
		return $str['2'] . " " . $bulan[$str[1]] . " " . $str[0];
	}

	// Controller Profile
	public function profile()
	{
		$ceks    = $this->session->userdata('pkl_smk@TA-D3-2023');
		$level   = $this->session->userdata('level@PKL-2023');
		$id_user = $this->session->userdata('id_user@PKL-2023');
		if (!isset($ceks)) {
			redirect('web/login');
		} else {

			$this->db->order_by('nama', 'ASC');
			$data['v_kelas'] 		= $this->db->get('tbl_kelas');
			$this->db->order_by('nama', 'ASC');
			$data['v_jurusan']  = $this->db->get('tbl_jurusan');
			if ($level == 'admin') {
				$data['user']   	 = $this->Mcrud->get_users_by_un($ceks);
				$data['email']		 = '';
				$data['level']		 = 'Admin';
			} elseif ($level == 'pembimbing') {
				$data['user']   	 = $this->Mcrud->get_pemb_by_un($ceks);
				$data['email']		 = '';
				$data['level']		 = 'Pembimbing';
			} else {
				$data['user']   	 = $this->db->get_where('tbl_siswa', "nis_siswa='$ceks'");
				$data['email']		 = '';
				$data['level']		 = 'Siswa';
			}
			// $data['level_users']  = $this->Mcrud->get_level_users();
			$data['judul_web'] 		= "Profil | PKL SMK AL-AMIIN";

			$this->load->view('users/header', $data);
			$this->load->view('users/profile', $data);
			$this->load->view('users/footer');

			if (isset($_POST['btnupdate'])) {

				if ($level != 'pembimbing') {

					if ($level == 'siswa') {
						$lokasi = 'foto/siswa';
					} else {
						$lokasi = 'foto';
					}

					$file_size = 1024 * 3; // 3 MB
					$this->upload->initialize(array(
						"file_type"     => "image/jpeg",
						"upload_path"   => "./$lokasi",
						"allowed_types" => "jpg|jpeg|png|gif|bmp",
						"max_size" => "$file_size"
					));

					if (!$this->upload->do_upload('avatar-1')) {
						$foto = $data['user']->row()->foto;
					} else {
						if ($data['user']->row()->foto != 'default.png') {
							unlink("$lokasi/" . $data['user']->row()->foto);
						}
						$gbr = $this->upload->data();

						$filename = $gbr['file_name'];
						$foto = preg_replace('/ /', '_', $filename);
					}
				}

				if ($level != 'siswa') {
					$username	    	= htmlentities(strip_tags($this->input->post('username')));
				} else {
					$username				= $ceks;
				}
				$nama_lengkap	 	= htmlentities(strip_tags($this->input->post('nama_lengkap')));
				if ($level == 'admin') {
					$identitas		= htmlentities(strip_tags($this->input->post('identitas')));
					$status	 		  = htmlentities(strip_tags($this->input->post('status')));
				} elseif ($level == 'pembimbing') {
					$kd_jurusan		= htmlentities(strip_tags($this->input->post('jurusan')));
					$nip	 		  	= htmlentities(strip_tags($this->input->post('nip')));
					$wilayah	 		= htmlentities(strip_tags($this->input->post('wilayah')));

					if ($id_user != $nip) {
						$cek_nip   = $this->db->get_where("tbl_pembimbing", array('nip' => "$nip"));
						if ($cek_nip->num_rows() != 0) {
							$query  = 'gagal';
							$pesan  = "NIP '$nip'";
						}
					}
				} else {
					$kd_kelas		  = htmlentities(strip_tags($this->input->post('kelas')));
					$telp	 		    = htmlentities(strip_tags($this->input->post('telp')));
				}

				if ($ceks != $username) {
					$cek_un    = $this->Mcrud->get_users_by_un($username);
					$cek_pemb  = $this->db->get_where("tbl_pembimbing", array('username' => "$username"));
					$cek_siswa = $this->db->get_where("tbl_siswa", array('nis_siswa' => "$username"));
					if ($cek_un->num_rows() != 0) {
						$query  = 'gagal';
						$pesan  = "Username '$username'";
					} elseif ($cek_pemb->num_rows() != 0) {
						$query  = 'gagal';
						$pesan  = "Username '$username'";
					} elseif ($cek_siswa->num_rows() != 0) {
						$query  = 'gagal';
						if ($level == 'siswa') {
							$pesan  = "NIS_SISWA '$username'";
						} else {
							$pesan  = "Username '$username'";
						}
					}

					if ($query == 'gagal') {
						$this->session->set_flashdata(
							'msg',
							'
									<div class="alert alert-warning alert-dismissible" role="alert">
										 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
											 <span aria-hidden="true">&times;&nbsp; &nbsp;</span>
										 </button>
										 <strong>Gagal!</strong> ' . $pesan . ' sudah ada.
									</div>'
						);
						redirect('users/profile');
					}
				}

				if ($level == 'admin') {
					$data = array(
						'username'	    => $username,
						'nama_lengkap'	=> $nama_lengkap,
						'identitas'			=> $identitas,
						'status'				=> $status,
						'foto'				  => $foto
					);
					$this->Mcrud->update_user(array('username' => $ceks), $data);
				} elseif ($level == 'pembimbing') {
					$data = array(
						'username'	    => $username,
						'nama_lengkap'	=> $nama_lengkap,
						'kd_jurusan'			=> $kd_jurusan,
						'nip'						=> $nip,
						'wilayah'				=> $wilayah
					);
					$this->db->update('tbl_pembimbing', $data, array('username' => $ceks));
				} else {
					$data = array(
						'nama_lengkap'	=> $nama_lengkap,
						'kd_kelas'				=> $kd_kelas,
						'telp'				  => $telp,
						'foto'				  => $foto
					);
					$this->db->update('tbl_siswa', $data, array('nis_siswa' => $ceks));
				}


				$this->session->has_userdata('pkl_smk@TA-D3-2023');
				$this->session->set_userdata('pkl_smk@TA-D3-2023', "$username");

				if ($level == 'pembimbing') {
					$id_user = $nip;
				} elseif ($level == 'siswa') {
					$id_user = $username;
				}
				$this->session->has_userdata('id_user@PKL-2023');
				$this->session->set_userdata('id_user@PKL-2023', "$id_user");

				$this->session->has_userdata('level@PKL-2023');
				$this->session->set_userdata('level@PKL-2023', "$level");

				$this->session->set_flashdata(
					'msg',
					'
										<div class="alert alert-success alert-dismissible" role="alert">
											 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
												 <span aria-hidden="true">&times;&nbsp; &nbsp;</span>
											 </button>
											 <strong>Sukses!</strong> Profile berhasil diperbarui.
										</div>'
				);
				redirect('users/profile');
			}


			if (isset($_POST['btnupdate2'])) {
				$password 	= htmlentities(strip_tags($this->input->post('password')));
				$password2 	= htmlentities(strip_tags($this->input->post('password2')));

				if ($password != $password2) {
					$this->session->set_flashdata(
						'msg2',
						'
									<div class="alert alert-warning alert-dismissible" role="alert">
										 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
											 <span aria-hidden="true">&times;&nbsp; &nbsp;</span>
										 </button>
										 <strong>Gagal!</strong> Password tidak cocok.
									</div>'
					);
				} else {
					$data = array(
						'password'	=> md5($password)
					);
					if ($level == 'admin') {
						$this->Mcrud->update_user(array('username' => $ceks), $data);
					} elseif ($level == 'pembimbing') {
						$this->db->update('tbl_pembimbing', $data, array('username' => $ceks));
					} else {
						$this->db->update('tbl_siswa', $data, array('nis_siswa' => $ceks));
					}

					$this->session->set_flashdata(
						'msg2',
						'
										<div class="alert alert-success alert-dismissible" role="alert">
											 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
												 <span aria-hidden="true">&times;&nbsp; &nbsp;</span>
											 </button>
											 <strong>Sukses!</strong> Password berhasil diperbarui.
										</div>'
					);
				}
				redirect('users/profile');
			}
		}
	}


	// Controller Jurusan
	public function jurusan($aksi = '', $id = '')
	{
		$ceks = $this->session->userdata('pkl_smk@TA-D3-2023');
		$id_user = $this->session->userdata('id_user@PKL-2023');
		$level = $this->session->userdata('level@PKL-2023');
		if (!isset($ceks)) {
			redirect('web/login');
		} else {

			if ($level != 'admin') {
				redirect('web/error_not_found');
			}

			$data['user']   	 = $this->Mcrud->get_users_by_un($ceks);
			$this->db->order_by('nama', 'ASC');
			$data['v_jurusan'] 	 = $this->db->get('tbl_jurusan');
			// $this->db->order_by('nama', 'ASC');
			// $data['v_kelas'] 	 = $this->db->get('tbl_kelas');
			$data['email']		 = '';
			$data['level']		 = 'Admin';


			if ($aksi == 'e_jurusan') {
				$p = "jurusan/j_edit";

				$data['query'] = $this->db->get_where("tbl_jurusan", "kd_jurusan = '$id'")->row();
				$data['judul_web'] 	  = "Edit Jurusan | PKL SMK AL-AMIIN";
			} elseif ($aksi == 'e_jurusan') {
				$p = "jurusan/j_edit";

				$data['query'] = $this->db->get_where("tbl_jurusan", "kd_jurusan = '$id'")->row();
				$data['judul_web'] 	  = "Edit Jurusan | PKL SMK AL-AMIIN";
			} elseif ($aksi == 'h_jurusan') {
				$data['query'] = $this->db->get_where("tbl_jurusan", "kd_jurusan = '$id'")->row();

				if ($data['query']->kd_jurusan != '') {
					$this->db->delete('tbl_jurusan', "kd_jurusan='$id'");
					$this->session->set_flashdata(
						'msg_jurusan',
						'
							<div class="alert alert-success alert-dismissible" role="alert">
								 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
									 <span aria-hidden="true">&times;&nbsp; &nbsp;</span>
								 </button>
								 <strong>Sukses!</strong> Data Jurusan berhasil dihapus.
							</div>'
					);
				}
				redirect('users/jurusan');
			} else {
				$p = "jurusan/jurusan";

				$data['judul_web'] 	  = "Jurusan | PKL SMK AL-AMIIN";
			}

			$this->load->view('users/header', $data);
			$this->load->view("users/admin/$p", $data);
			$this->load->view('users/footer');

			date_default_timezone_set('Asia/Jakarta');
			$tgl = date('d-m-Y H:i:s');

			if (isset($_POST['btnsimpan'])) {


				if (!empty($_POST['jurusan'])) {
					$jurusan   	 	= htmlentities(strip_tags($this->input->post('jurusan')));
					$cek_data = $this->db->get_where("tbl_jurusan", "nama = '$jurusan'")->num_rows();
					if ($cek_data != 0) {
						$this->session->set_flashdata(
							'msg_jurusan',
							'
										<div class="alert alert-warning alert-dismissible" role="alert">
											 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
												 <span aria-hidden="true">&times;&nbsp; &nbsp;</span>
											 </button>
											 <strong>Gagal!</strong> Nama Jurusan "' . $jurusan . '" Sudah ada.
										</div>'
						);
					} else {
						$data = array(
							'nama'	 	 => $jurusan
						);
						$this->db->insert('tbl_jurusan', $data);

						$this->session->set_flashdata(
							'msg_jurusan',
							'
												<div class="alert alert-success alert-dismissible" role="alert">
													 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
														 <span aria-hidden="true">&times;&nbsp; &nbsp;</span>
													 </button>
													 <strong>Sukses!</strong> Jurusan berhasil ditambah.
												</div>'
						);
					}
					redirect('users/jurusan/tbl_jurusan');
				}
			}

			if (isset($_POST['btnupdate'])) {

				if (!empty($_POST['jurusan'])) {
					$jurusan   	 	= htmlentities(strip_tags($this->input->post('jurusan')));
					$data = array(
						'nama'	 	 => $jurusan
					);
					$this->db->update('tbl_jurusan', $data, "kd_jurusan='$id'");
					$this->session->set_flashdata(
						'msg_jurusan',
						'
									<div class="alert alert-success alert-dismissible" role="alert">
										 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
											 <span aria-hidden="true">&times;&nbsp; &nbsp;</span>
										 </button>
										 <strong>Sukses!</strong> Jurusan berhasil ditambah.
									</div>'
					);

					redirect('users/jurusan/tbl_jurusan');
				}
			}
		}
	}

	// Controller Kelas
	public function kelas($aksi = '', $id = '')
	{
		$ceks = $this->session->userdata('pkl_smk@TA-D3-2023');
		$id_user = $this->session->userdata('id_user@PKL-2023');
		$level = $this->session->userdata('level@PKL-2023');
		if (!isset($ceks)) {
			redirect('web/login');
		} else {

			if ($level != 'admin') {
				redirect('web/error_not_found');
			}
			$this->db->order_by('nama', 'ASC');
			$data['v_jurusan'] 	 = $this->db->get('tbl_jurusan');
			$data['user']   	 = $this->Mcrud->get_users_by_un($ceks);
			$this->db->order_by('nama', 'ASC');
			$data['v_kelas'] 	 = $this->db->get('tbl_kelas');
			$data['email']		 = '';
			$data['level']		 = 'Admin';

			if ($aksi == 'e_kelas') {
				$p = "kelas/k_edit";

				$data['query'] = $this->db->get_where("tbl_kelas", "kd_kelas = '$id'")->row();
				$data['judul_web'] 	  = "Edit Kelas | PKL SMK AL-AMIIN";
			} elseif ($aksi == 'h_kelas') {
				$data['query'] = $this->db->get_where("tbl_kelas", "kd_kelas = '$id'")->row();

				if ($data['query']->kd_kelas != '') {
					$this->db->delete('tbl_kelas', "kd_kelas='$id'");
					$this->session->set_flashdata(
						'msg_kelas',
						'
							<div class="alert alert-success alert-dismissible" role="alert">
								 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
									 <span aria-hidden="true">&times;&nbsp; &nbsp;</span>
								 </button>
								 <strong>Sukses!</strong> Data Kelas berhasil dihapus.
							</div>'
					);
				}
				redirect('users/kelas');
			} else {
				$p = "kelas/kelas";

				$data['judul_web'] 	  = "Kelas | PKL SMK AL-AMIIN";
			}

			$this->load->view('users/header', $data);
			$this->load->view("users/admin/$p", $data);
			$this->load->view('users/footer');

			date_default_timezone_set('Asia/Jakarta');
			$tgl = date('d-m-Y H:i:s');

			if (isset($_POST['btnsimpan'])) {
				if (!empty($_POST['kelas'])) {
					$jurusan   	 	= htmlentities(strip_tags($this->input->post('jurusan')));
					$kelas   	 	= htmlentities(strip_tags($this->input->post('kelas')));
					$cek_data = $this->db->get_where("tbl_kelas", "nama = '$kelas'")->num_rows();
					if ($cek_data != 0) {
						$this->session->set_flashdata(
							'msg_kelas',
							'
										<div class="alert alert-warning alert-dismissible" role="alert">
											 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
												 <span aria-hidden="true">&times;&nbsp; &nbsp;</span>
											 </button>
											 <strong>Gagal!</strong> Nama Kelas "' . $kelas . '" Sudah ada.
										</div>'
						);
					} else {
						$data = array(
							'nama'	 	  => $kelas,
							'kd_jurusan' => $jurusan
						);
						$this->db->insert('tbl_kelas', $data);

						$this->session->set_flashdata(
							'msg_kelas',
							'
												<div class="alert alert-success alert-dismissible" role="alert">
													 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
														 <span aria-hidden="true">&times;&nbsp; &nbsp;</span>
													 </button>
													 <strong>Sukses!</strong> Kelas berhasil ditambah.
												</div>'
						);
					}
					redirect('users/kelas');
				}
			}

			if (isset($_POST['btnupdate'])) {
				$jurusan   	 	= htmlentities(strip_tags($this->input->post('jurusan')));
				if (!empty($_POST['kelas'])) {
					$kelas   	 	= htmlentities(strip_tags($this->input->post('kelas')));
					$data = array(
						'nama'	 	  => $kelas,
						'kd_jurusan' => $jurusan
					);
					$this->db->update('tbl_kelas', $data, "kd_kelas='$id'");
					$this->session->set_flashdata(
						'msg_kelas',
						'
									<div class="alert alert-success alert-dismissible" role="alert">
										 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
											 <span aria-hidden="true">&times;&nbsp; &nbsp;</span>
										 </button>
										 <strong>Sukses!</strong> Kelas berhasil diubah.
									</div>'
					);

					redirect('users/kelas');
				}
			}
		}
	}


	// Controller Pembimbing
	public function pembimbing($aksi = '', $id = '')
	{
		$ceks = $this->session->userdata('pkl_smk@TA-D3-2023');
		$id_user = $this->session->userdata('id_user@PKL-2023');
		$level = $this->session->userdata('level@PKL-2023');
		if (!isset($ceks)) {
			redirect('web/login');
		} else {

			if ($level != 'admin') {
				redirect('web/error_not_found');
			}

			$data['user']   	 = $this->Mcrud->get_users_by_un($ceks);
			$this->db->order_by('nama', 'ASC');
			$data['v_jurusan'] 	 = $this->db->get('tbl_jurusan');
			$this->db->order_by('nama', 'ASC');
			$data['v_kelas'] 	 = $this->db->get('tbl_kelas');
			$this->db->order_by('nama_lengkap', 'ASC');
			$data['v_pemb'] 	 = $this->db->get('tbl_pembimbing');
			$data['email']		 = '';
			$data['level']		 = 'Admin';

			if ($aksi == 't_pemb') {
				$p = "pembimbing/pembimbing_tambah";
				$data['judul_web'] 	  = "Tambah Pembimbing | PKL SMK AL-AMIIN";
			} elseif ($aksi == 'd_pemb') {
				$p = "pembimbing/pembimbing_detail";

				$data['query'] = $this->db->get_where("tbl_pembimbing", "kd_pembimbing = '$id'")->row();
				$data['judul_web'] 	  = "Detail Pembimbing | PKL SMK AL-AMIIN";
			} elseif ($aksi == 'h_pemb') {

				$data['query'] = $this->db->get_where("tbl_pembimbing", "kd_pembimbing = '$id'")->row();

				if ($data['query']->username != '') {
					$this->db->delete('tbl_pembimbing', "kd_pembimbing='$id'");
					$this->session->set_flashdata(
						'msg_pemb',
						'
							<div class="alert alert-success alert-dismissible" role="alert">
								 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
									 <span aria-hidden="true">&times;&nbsp; &nbsp;</span>
								 </button>
								 <strong>Sukses!</strong> Pengguna Pembimbing berhasil dihapus.
							</div>'
					);
				}
				redirect('users/pembimbing');
			} else {
				$p = "pembimbing/pembimbing";

				$data['judul_web'] 	  = "Pembimbing | PKL SMK AL-AMIIN";

				$this->db->order_by('kd_pembimbing', 'DESC');
				$data['v_pemb']  = $this->db->get("tbl_pembimbing");
			}

			$this->load->view('users/header', $data);
			$this->load->view("users/admin/$p", $data);
			$this->load->view('users/footer');

			date_default_timezone_set('Asia/Jakarta');
			$tgl = date('d-m-Y H:i:s');

			if (isset($_POST['btnsimpan'])) {
				$jurusan   		 	= htmlentities(strip_tags($this->input->post('jurusan')));
				$username	 		  = htmlentities(strip_tags($this->input->post('username')));
				$nip	 					= htmlentities(strip_tags($this->input->post('nip')));
				$nama_lengkap	 	= htmlentities(strip_tags($this->input->post('nama_lengkap')));
				$wilayah	 			= htmlentities(strip_tags($this->input->post('wilayah')));

				$cek_user  = $this->db->get_where("tbl_user", "username = '$username'")->num_rows();
				$cek_pemb  = $this->db->get_where("tbl_pembimbing", "username = '$username'")->num_rows();
				$cek_nip   = $this->db->get_where("tbl_pembimbing", "nip = '$nip'")->num_rows();
				$cek_siswa = $this->db->get_where("tbl_siswa", "nis_siswa = '$username'")->num_rows();
				if ($cek_user != 0) {
					$this->session->set_flashdata(
						'msg',
						'
									<div class="alert alert-warning alert-dismissible" role="alert">
										 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
											 <span aria-hidden="true">&times;&nbsp; &nbsp;</span>
										 </button>
										 <strong>Gagal!</strong> Username "' . $username . '" Sudah ada.
									</div>'
					);
				} else {
					if ($cek_pemb != 0) {
						$this->session->set_flashdata(
							'msg',
							'
											<div class="alert alert-warning alert-dismissible" role="alert">
												 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
													 <span aria-hidden="true">&times;&nbsp; &nbsp;</span>
												 </button>
												 <strong>Gagal!</strong> Username "' . $username . '" Sudah ada.
											</div>'
						);
					} else {
						if ($cek_nip != 0) {
							$this->session->set_flashdata(
								'msg',
								'
												<div class="alert alert-warning alert-dismissible" role="alert">
													 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
														 <span aria-hidden="true">&times;&nbsp; &nbsp;</span>
													 </button>
													 <strong>Gagal!</strong> NIP "' . $nip . '" Sudah ada.
												</div>'
							);
							redirect('users/pembimbing/t_pemb');
						}

						$data = array(
							'username'	 	 => $username,
							'kd_jurusan'    => $jurusan,
							'password'	 	 => md5($username),
							'nip'		 			 => $nip,
							'nama_lengkap' => $nama_lengkap,
							'wilayah' 		 => $wilayah
						);
						$this->db->insert('tbl_pembimbing', $data);

						$this->session->set_flashdata(
							'msg_pemb',
							'
											<div class="alert alert-success alert-dismissible" role="alert">
												 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
													 <span aria-hidden="true">&times;&nbsp; &nbsp;</span>
												 </button>
												 <strong>Sukses!</strong> Pengguna Pembimbing berhasil ditambahkan.
											</div>'
						);
						redirect('users/pembimbing');
					}
				}
				redirect('users/pembimbing/t_pemb');
			}
		}
	}

	// Controller Siswa
	public function siswa($aksi = '', $id = '')
	{
		$ceks = $this->session->userdata('pkl_smk@TA-D3-2023');
		$id_user = $this->session->userdata('id_user@PKL-2023');
		$level = $this->session->userdata('level@PKL-2023');
		if (!isset($ceks)) {
			redirect('web/login');
		} else {

			if ($level != 'admin') {
				redirect('web/error_not_found');
			}

			$data['user']   	 = $this->Mcrud->get_users_by_un($ceks);
			$this->db->order_by('nama', 'ASC');
			$data['v_jurusan'] 	 = $this->db->get('tbl_jurusan');
			$this->db->order_by('nama', 'ASC');
			$data['v_kelas'] 	 = $this->db->get('tbl_kelas');
			$this->db->order_by('nama_lengkap', 'ASC');
			$data['v_pemb'] 	 = $this->db->get('tbl_pembimbing');
			$data['email']		 = '';
			$data['level']		 = 'Admin';

			if ($aksi == 't_siswa') {
				$p = "siswa/siswa_tambah";

				$data['judul_web'] 	  = "Tambah Siswa | PKL SMK AL-AMIIN";
			} elseif ($aksi == 'd_siswa') {
				$p = "siswa/siswa_detail";

				$data['query'] = $this->db->get_where("tbl_siswa", "nis_siswa = '$id'")->row();
				$data['judul_web'] 	  = "Detail Siswa | PKL SMK AL-AMIIN";
			} elseif ($aksi == 'h_siswa') {
				$data['query'] = $this->db->get_where("tbl_siswa", "nis_siswa = '$id'")->row();

				if ($data['query']->nis_siswa != '') {
					unlink("foto/siswa/" . $data['query']->foto);
					$this->db->delete('tbl_siswa', "nis_siswa='$id'");
					$this->session->set_flashdata(
						'msg_siswa',
						'
						<div class="alert alert-success alert-dismissible" role="alert">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close">
								<span aria-hidden="true">&times;&nbsp; &nbsp;</span>
								 </button>
								<strong>Sukses!</strong> Pengguna Siswa berhasil dihapus.
						</div>'
					);
				}
				redirect('users/siswa');
			} else {
				$p = "siswa/siswa";

				$data['judul_web'] 	  = "Siswa | PKL SMK AL-AMIIN";

				$this->db->order_by('kd_pembimbing', 'DESC');
				$data['v_pemb']  = $this->db->get("tbl_pembimbing");

				$this->db->order_by('nis_siswa', 'DESC');
				$data['v_siswa']  = $this->db->get("tbl_siswa");
			}

			$this->load->view('users/header', $data);
			$this->load->view("users/admin/$p", $data);
			$this->load->view('users/footer');

			date_default_timezone_set('Asia/Jakarta');
			$tgl = date('d-m-Y H:i:s');

			if (isset($_POST['btnsimpan'])) {
				$nis_siswa	 			= htmlentities(strip_tags($this->input->post('nis_siswa')));
				$kelas   		 	= htmlentities(strip_tags($this->input->post('kelas')));
				$telp	 			= htmlentities(strip_tags($this->input->post('telp')));
				$nama_lengkap	 	= htmlentities(strip_tags($this->input->post('nama_lengkap')));
				$kd_pembimbing	 	= htmlentities(strip_tags($this->input->post('kd_pembimbing')));

				$cek_user  = $this->db->get_where("tbl_user", "username = '$nis_siswa'")->num_rows();
				$cek_pemb  = $this->db->get_where("tbl_pembimbing", "username = '$nis_siswa'")->num_rows();
				$cek_siswa = $this->db->get_where("tbl_siswa", "nis_siswa = '$nis_siswa'")->num_rows();
				if ($cek_user != 0) {
					$this->session->set_flashdata(
						'msg',
						'
									<div class="alert alert-warning alert-dismissible" role="alert">
										 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
											 <span aria-hidden="true">&times;&nbsp; &nbsp;</span>
										 </button>
										 <strong>Gagal!</strong> Username "' . $nis_siswa . '" Sudah ada.
									</div>'
					);
				} else {
					if ($cek_pemb != 0) {
						$this->session->set_flashdata(
							'msg',
							'
											<div class="alert alert-warning alert-dismissible" role="alert">
												 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
													 <span aria-hidden="true">&times;&nbsp; &nbsp;</span>
												 </button>
												 <strong>Gagal!</strong> Username "' . $nis_siswa . '" Sudah ada.
											</div>'
						);
					} else {

						$file_size = 1024 * 5; //5 MB
						$this->upload->initialize(array(
							"upload_path"   => "./foto/siswa/",
							"allowed_types" => "*",
							"max_size" => "$file_size"
						));

						if (!$this->upload->do_upload('file')) {
							$error = $this->upload->display_errors('<p>', '</p>');
							$this->session->set_flashdata(
								'msg',
								'
														 <div class="alert alert-warning alert-dismissible" role="alert">
																<button type="button" class="close" data-dismiss="alert" aria-label="Close">
																	<span aria-hidden="true">&times; &nbsp;</span>
																</button>
																<strong>Gagal!</strong> ' . $error . '.
														 </div>'
							);
							redirect('users/siswa/t_siswa');
						} else {
							$file = $this->upload->data();
							$filename = $file['file_name'];
							$file 		= preg_replace('/ /', '_', $filename);
						}

						$data = array(
							'nis_siswa'	 			 	 => $nis_siswa,
							'kd_kelas'    	                 => $kelas,
							'password'	 	             => md5($nis_siswa),
							'nama_lengkap'               => $nama_lengkap,
							'telp'				         => $telp,
							'foto'		 		         => $file,
							'kd_pembimbing'		 	     => $kd_pembimbing
						);
						$this->db->insert('tbl_siswa', $data);

						$this->session->set_flashdata(
							'msg_siswa',
							'
											<div class="alert alert-success alert-dismissible" role="alert">
												 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
													 <span aria-hidden="true">&times;&nbsp; &nbsp;</span>
												 </button>
												 <strong>Sukses!</strong> Pengguna Siswa berhasil ditambahkan.
											</div>'
						);
						redirect('users/siswa');
					}
				}
				redirect('users/siswa/t_siswa');
			}
		}
	}

	// Controller Industri
	public function industri($aksi = '', $id = '')
	{
		$ceks = $this->session->userdata('pkl_smk@TA-D3-2023');
		$id_user = $this->session->userdata('id_user@PKL-2023');
		$level = $this->session->userdata('level@PKL-2023');
		if (!isset($ceks)) {
			redirect('web/login');
		} else {

			if ($level != 'admin') {
				redirect('web/error_not_found');
			}

			$data['user']   	 = $this->Mcrud->get_users_by_un($ceks);
			$this->db->order_by('nama_industri', 'ASC');
			$data['v_industri'] 	 = $this->db->get('tbl_industri');
			$data['email']		 = '';
			$data['level']		 = 'Admin';

			if ($aksi == 't') {
				$p = "industri/industri_tambah";

				$data['judul_web'] 	  = "Tambah Industri | PKL SMK AL-AMIIN";
			} elseif ($aksi == 'd') {
				$p = "industri/industri_detail";

				$data['query'] = $this->db->get_where("tbl_industri", "kd_industri = '$id'")->row();
				$data['judul_web'] 	  = "Detail Industri | PKL SMK AL-AMIIN";
			} elseif ($aksi == 'e') {
				$p = "industri/industri_edit";

				$data['query'] = $this->db->get_where("tbl_industri", "kd_industri = '$id'")->row();
				$data['judul_web'] 	  = "Edit Industri | PKL SMK AL-AMIIN";
			} elseif ($aksi == 'h') {
				$data['query'] = $this->db->get_where("tbl_industri", "kd_industri = '$id'")->row();

				if ($data['query']->kd_industri != '') {
					unlink("foto/industri/" . $data['query']->foto);
					$this->db->delete('tbl_industri', "kd_industri='$id'");
					$this->session->set_flashdata(
						'msg',
						'
							<div class="alert alert-success alert-dismissible" role="alert">
								 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
									 <span aria-hidden="true">&times;&nbsp; &nbsp;</span>
								 </button>
								 <strong>Sukses!</strong> Industri berhasil dihapus.
							</div>'
					);
				}
				redirect('users/industri');
			} else {
				$p = "industri/industri";

				$data['judul_web'] 	  = "Industri | PKL SMK AL-AMIIN";
			}

			$this->load->view('users/header', $data);
			$this->load->view("users/admin/$p", $data);
			$this->load->view('users/footer');

			date_default_timezone_set('Asia/Jakarta');
			$tgl = date('d-m-Y H:i:s');

			if (isset($_POST['btnsimpan'])) {
				$nama_industri   	= htmlentities(strip_tags($this->input->post('nama_industri')));
				$bidang_kerja	 	= htmlentities(strip_tags($this->input->post('bidang_kerja')));
				$deskripsi	 		= htmlentities(strip_tags($this->input->post('deskripsi')));
				$alamat_industri  = htmlentities(strip_tags($this->input->post('alamat_industri')));
				$wilayah   		 = htmlentities(strip_tags($this->input->post('wilayah')));
				$telepon	 	= htmlentities(strip_tags($this->input->post('telepon')));
				$kuota	 		= htmlentities(strip_tags($this->input->post('kuota')));

				$file_size = 1024 * 5; //5 MB
				$this->upload->initialize(array(
					"upload_path"   => "./foto/industri/",
					"allowed_types" => "*",
					"max_size" => "$file_size"
				));

				if (!$this->upload->do_upload('file')) {
					$error = $this->upload->display_errors('<p>', '</p>');
					$this->session->set_flashdata(
						'msg',
						'
														 <div class="alert alert-warning alert-dismissible" role="alert">
																<button type="button" class="close" data-dismiss="alert" aria-label="Close">
																	<span aria-hidden="true">&times; &nbsp;</span>
																</button>
																<strong>Gagal!</strong> ' . $error . '.
														 </div>'
					);
					redirect('users/industri/t');
				} else {
					$file = $this->upload->data();
					$filename = $file['file_name'];
					$file 		= preg_replace('/ /', '_', $filename);
				}

				$data = array(
					'nama_industri'	 	=> $nama_industri,
					'bidang_kerja'    => $bidang_kerja,
					'deskripsi'				=> $deskripsi,
					'alamat_industri' => $alamat_industri,
					'wilayah'		 		  => $wilayah,
					'telepon'	 			  => $telepon,
					'kuota'		 		    => $kuota,
					'foto'	 			 	  => $file
				);
				$this->db->insert('tbl_industri', $data);

				$this->session->set_flashdata(
					'msg',
					'
											<div class="alert alert-success alert-dismissible" role="alert">
												 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
													 <span aria-hidden="true">&times;&nbsp; &nbsp;</span>
												 </button>
												 <strong>Sukses!</strong> Industri berhasil ditambahkan.
											</div>'
				);
				redirect('users/industri');
			}



			if (isset($_POST['btnupdate'])) {
				$nama_industri   	= htmlentities(strip_tags($this->input->post('nama_industri')));
				$bidang_kerja	 	= htmlentities(strip_tags($this->input->post('bidang_kerja')));
				$deskripsi	 		= htmlentities(strip_tags($this->input->post('deskripsi')));
				$alamat_industri = htmlentities(strip_tags($this->input->post('alamat_industri')));
				$wilayah   		 		= htmlentities(strip_tags($this->input->post('wilayah')));
				$telepon	 	= htmlentities(strip_tags($this->input->post('telepon')));
				$kuota	 				  = htmlentities(strip_tags($this->input->post('kuota')));
				$file	 				  = htmlentities(strip_tags($this->input->post('foto')));

				$file_size = 1024 * 5; //5 MB
				$this->upload->initialize(array(
					"upload_path"   => "./foto/industri/",
					"allowed_types" => "*",
					"max_size" => "$file_size"
				));

				$cek_foto = $data['query']->foto;
				if ($_FILES['file']['error'] <> 4) {
					if (!$this->upload->do_upload('file')) {
						$error = $this->upload->display_errors('<p>', '</p>');
						$update = "";
					} else {
						unlink("foto/industri/$cek_foto");
						$gbr = $this->upload->data();
						$filename = $gbr['file_name'];
						$file 		= preg_replace('/ /', '_', $filename);

						$update = "yes";
					}
				} else {
					$file   = $cek_foto;
					$update = "yes";
				}

				if ($update == 'yes') {

					$data = array(
						'nama_industri'	 	=> $nama_industri,
						'bidang_kerja'    => $bidang_kerja,
						'deskripsi'				=> $deskripsi,
						'alamat_industri' => $alamat_industri,
						'wilayah'		 		  => $wilayah,
						'telepon'	 			  => $telepon,
						'kuota'		 		    => $kuota,
						'foto'	 			 	  => $file
					);
					$this->db->update('tbl_industri', $data, "kd_industri='$id'");

					$this->session->set_flashdata(
						'msg',
						'
											<div class="alert alert-success alert-dismissible" role="alert">
												 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
													 <span aria-hidden="true">&times;&nbsp; &nbsp;</span>
												 </button>
												 <strong>Sukses!</strong> Industri berhasil diperbarui.
											</div>'
					);
					redirect('users/industri');
				} else {
					$this->session->set_flashdata(
						'msg',
						'
											 <div class="alert alert-warning alert-dismissible" role="alert">
													<button type="button" class="close" data-dismiss="alert" aria-label="Close">
														<span aria-hidden="true">&times; &nbsp;</span>
													</button>
													<strong>Gagal!</strong> ' . $error . '.
											 </div>'
					);
					redirect('users/industri/e/' . $id);
				}
			}
		}
	}


	// Controller Tempat
	public function tempat($aksi = '', $id = '')
	{
		$ceks = $this->session->userdata('pkl_smk@TA-D3-2023');
		$id_user = $this->session->userdata('id_user@PKL-2023');
		$level = $this->session->userdata('level@PKL-2023');
		if (!isset($ceks)) {
			redirect('web/login');
		} else {

			if ($level != 'admin') {
				redirect('web/error_not_found');
			}

			$data['user']   	 = $this->Mcrud->get_users_by_un($ceks);
			$this->db->order_by('kd_tempat', 'DESC');
			$this->db->order_by('tahun', 'DESC');
			$data['v_penempatan'] 	 = $this->db->get('tbl_tempat');
			$data['email']		 = '';
			$data['level']		 = 'Admin';

			if ($aksi == 'd') {
				$p = "tempat/tempat_detail";

				$data['query'] = $this->db->get_where("tbl_tempat", "kd_tempat = '$id'")->row();
				$data['judul_web'] 	  = "Detail Tempat | PKL SMK AL-AMIIN";
			} elseif ($aksi == 'h') {
				$cek_data_tolak = $this->db->get_where('tbl_tolak_tempat', "kd_tempat='$id'")->num_rows();
				if ($cek_data_tolak != 0) {
					$this->db->delete('tbl_tolak_tempat', "kd_tempat='$id'");
				}
				$cek_data = $this->db->get_where('tbl_tempat', "kd_tempat='$id'")->row();
				unlink("lampiran/surat/$cek_data->surat");
				$this->db->delete('tbl_tempat', "kd_tempat='$id'");

				$this->session->set_flashdata(
					'msg',
					'
						 <div class="alert alert-success alert-dismissible" role="alert">
								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
									<span aria-hidden="true">&times; &nbsp;</span>
								</button>
								<strong>Sukses!</strong> Tempat berhasil dihapus.
						 </div>'
				);
				redirect('users/tempat');
			} elseif ($aksi == 'tolak') {
				$cek_status = $this->db->get_where('tbl_tempat', "kd_tempat='$id'")->row()->status;
				if ($cek_status == 'ditolak') {
					$status = 'proses';
				} else {
					$status = 'ditolak';
				}
				$data = array(
					'status'	 	=> $status
				);
				$this->db->update('tbl_tempat', $data, "kd_tempat='$id'");

				$this->session->set_flashdata(
					'msg',
					'
						<div class="alert alert-success alert-dismissible" role="alert">
							 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
								 <span aria-hidden="true">&times;&nbsp; &nbsp;</span>
							 </button>
							 <strong>Sukses!</strong> Tempat berhasil diperbarui.
						</div>'
				);
				redirect('users/tempat');
			} elseif ($aksi == 'setujui') {
				$cek_status = $this->db->get_where('tbl_tempat', "kd_tempat='$id'")->row()->status;
				if ($cek_status == 'diterima') {
					$status = 'proses';
				} else {
					$status = 'diterima';
				}
				$data = array(
					'status'	 	=> $status
				);
				$this->db->update('tbl_tempat', $data, "kd_tempat='$id'");

				$this->session->set_flashdata(
					'msg',
					'
						<div class="alert alert-success alert-dismissible" role="alert">
							 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
								 <span aria-hidden="true">&times;&nbsp; &nbsp;</span>
							 </button>
							 <strong>Sukses!</strong> Tempat berhasil diperbarui.
						</div>'
				);
				redirect('users/tempat');
			} else {
				$p = "tempat/tempat";

				$data['judul_web'] 	  = "Tempat PKL | SMK AL-AMIIN";
			}

			$this->load->view('users/header', $data);
			$this->load->view("users/admin/$p", $data);
			$this->load->view('users/footer');

			date_default_timezone_set('Asia/Jakarta');
			$tgl = date('Y-m-d');

			for ($i = 1; $i <= $data['v_penempatan']->num_rows(); $i++) {
				if (isset($_POST['btntolak_' . $i])) {
					$kd_tempat = $_POST['kd_tempat_' . $i];
					$data = array(
						'kd_tempat'	 	=> $kd_tempat,
						'tanggal'	 				=> $tgl,
						'alasan'	 				=> $_POST['pesan_' . $i],
					);
					$this->db->insert('tbl_tolak_tempat', $data);

					$data = array(
						'status'	 	=> 'ditolak'
					);
					$this->db->update('tbl_tempat', $data, "kd_tempat='$kd_tempat'");

					$this->session->set_flashdata(
						'msg',
						'
								<div class="alert alert-success alert-dismissible" role="alert">
									 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
										 <span aria-hidden="true">&times;&nbsp; &nbsp;</span>
									 </button>
									 <strong>Sukses!</strong> Penolakan tempat berhasil dikirim.
								</div>'
					);
					redirect('users/tempat');
				}
			}
		}
	}


	// Controller Monitoring
	public function monitoring($aksi = '', $id = '')
	{
		$ceks = $this->session->userdata('pkl_smk@TA-D3-2023');
		$id_user = $this->session->userdata('id_user@PKL-2023');
		$level = $this->session->userdata('level@PKL-2023');
		if (!isset($ceks)) {
			redirect('web/login');
		} else {

			if ($level != 'admin') {
				redirect('web/error_not_found');
			}

			$data['user']   	 = $this->Mcrud->get_users_by_un($ceks);
			$this->db->join('tbl_siswa', 'tbl_siswa.nis_siswa=tbl_monitoring.nis_siswa');
			$this->db->order_by('kd_monitoring', 'ASC');
			$data['v_monitoring'] 	 = $this->db->get('tbl_monitoring');
			$data['email']		 = '';
			$data['level']		 = 'Admin';

			if ($aksi == 'd') {
				$p = "monitoring/monitoring_detail";

				$data['judul_web'] 	  = "Detail Log Book | PKL SMK AL-AMIIN";
			} else {
				$p = "monitoring/monitoring";

				$data['judul_web'] 	  = "Log Book | PKL SMK AL-AMIIN";
			}

			$this->load->view('users/header', $data);
			$this->load->view("users/admin/$p", $data);
			$this->load->view('users/footer');
		}
	}

	// Controller Laporan
	public function laporan($aksi = '', $id = '')
	{
		$ceks = $this->session->userdata('pkl_smk@TA-D3-2023');
		$id_user = $this->session->userdata('id_user@PKL-2023');
		$level = $this->session->userdata('level@PKL-2023');
		if (!isset($ceks)) {
			redirect('web/login');
		} else {

			if ($level != 'admin') {
				redirect('web/error_not_found');
			}

			$data['user']   	 = $this->Mcrud->get_users_by_un($ceks);
			$this->db->join('tbl_siswa', 'tbl_siswa.nis_siswa=tbl_laporan.nis_siswa');
			$this->db->order_by('kd_laporan', 'ASC');
			$data['v_laporan'] 	 = $this->db->get('tbl_laporan');
			$data['email']		 = '';
			$data['level']		 = 'Admin';

			if ($aksi == 'd') {
				$p = "laporan/laporan_detail";

				$data['judul_web'] 	  = "Detail Laporan | PKL SMK AL-AMIIN";
			} else {
				$p = "laporan/laporan";

				$data['judul_web'] 	  = "Laporan | PKL SMK AL-AMIIN";
			}

			$this->load->view('users/header', $data);
			$this->load->view("users/admin/$p", $data);
			$this->load->view('users/footer');
		}
	}

	// Controller Sidang
	public function sidang($aksi = '', $id = '')
	{
		$ceks = $this->session->userdata('pkl_smk@TA-D3-2023');
		$id_user = $this->session->userdata('id_user@PKL-2023');
		$level = $this->session->userdata('level@PKL-2023');
		if (!isset($ceks)) {
			redirect('web/login');
		} else {

			if ($level != 'admin') {
				redirect('web/error_not_found');
			}

			$data['user']   	 = $this->Mcrud->get_users_by_un($ceks);
			$this->db->join('tbl_siswa', 'tbl_siswa.nis_siswa=tbl_sidang.nis_siswa');
			$this->db->order_by('kd_sidang', 'ASC');
			$data['v_sidang'] 	 = $this->db->get('tbl_sidang');
			$data['email']		 = '';
			$data['level']		 = 'Admin';

			if ($aksi == 'd') {
				$p = "sidang/sidang_detail";

				$data['judul_web'] 	  = "Detail Sidang | PKL SMK AL-AMIIN";
			} else {
				$p = "sidang/sidang";

				$data['judul_web'] 	  = "Sidang | PKL SMK AL-AMIIN";
			}

			$this->load->view('users/header', $data);
			$this->load->view("users/admin/$p", $data);
			$this->load->view('users/footer');
		}
	}


	// Controller Nilai
	public function nilai($aksi = '', $id = '')
	{
		$ceks = $this->session->userdata('pkl_smk@TA-D3-2023');
		$id_user = $this->session->userdata('id_user@PKL-2023');
		$level = $this->session->userdata('level@PKL-2023');
		if (!isset($ceks)) {
			redirect('web/login');
		} else {

			if ($level != 'admin') {
				redirect('web/error_not_found');
			}

			$data['user']   	 = $this->Mcrud->get_users_by_un($ceks);
			$this->db->join('tbL_tempat', 'tbL_tempat.nis_siswa=tbL_siswa.nis_siswa');
			$this->db->join('tbl_nilai', 'tbl_nilai.kd_tempat=tbl_tempat.kd_tempat');
			if ($aksi == 'd') {
				$this->db->where('tbl_nilai.kd_nilai', $id);
			}
			$this->db->order_by('tbl_siswa.nama_lengkap', 'ASC');
			$this->db->order_by('tbl_tempat.tahun', 'DESC');
			$data['v_nilai'] 	 = $this->db->get('tbl_siswa');
			$data['email']		 = '';
			$data['level']		 = 'Admin';

			if ($aksi == 't') {
				$p = "nilai/nilai_tambah";

				$data['judul_web'] 	  = "Nilai | PKL SMK AL-AMIIN";
				$this->db->order_by('nis_siswa', 'DESC');
				$this->db->order_by('nama_lengkap', 'ASC');
				$data['v_siswa'] 	    = $this->db->get('tbl_siswa');
			} elseif ($aksi == 'd') {
				$p = "nilai/nilai_detail";

				$data['judul_web'] 	  = "Detail Nilai | PKL SMK AL-AMIIN";
			} elseif ($aksi == 'h') {
				$this->db->delete('tbl_nilai', "kd_nilai='$id'");

				$this->session->set_flashdata(
					'msg',
					'
					<div class="alert alert-success alert-dismissible" role="alert">
						 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
							 <span aria-hidden="true">&times;&nbsp; &nbsp;</span>
						 </button>
						 <strong>Sukses!</strong> Nilai berhasil dihapus.
					</div>'
				);
				redirect('users/nilai');
			} else {
				$p = "nilai/nilai";

				$data['judul_web'] 	  = "Data Nilai | PKL SMK AL-AMIIN";
			}

			$this->load->view('users/header', $data);
			$this->load->view("users/admin/$p", $data);
			$this->load->view('users/footer');

			if (isset($_POST['btnsimpan'])) {
				$nis_siswa	 					= htmlentities(strip_tags($this->input->post('nis_siswa')));
				$nilai	 				= htmlentities(strip_tags($this->input->post('nilai')));
				$keterangan	 		= htmlentities(strip_tags($this->input->post('keterangan')));

				$cek_penempatan = $this->db->get_where('tbl_tempat', "nis_siswa='$nis_siswa'");
				if ($cek_penempatan->num_rows() == 0) {
					$this->session->set_flashdata(
						'msg',
						'
							<div class="alert alert-warning alert-dismissible" role="alert">
								 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
									 <span aria-hidden="true">&times;&nbsp; &nbsp;</span>
								 </button>
								 <strong>Gagal!</strong> Mahasiswa belum menentukan tempat.
							</div>'
					);
					redirect('users/nilai/t');
				} else {
					$data = array(
						'kd_tempat' => $cek_penempatan->row()->kd_tempat,
						'keterangan'   => $keterangan,
						'nilai'				 => $nilai
					);
					$this->db->insert('tbl_nilai', $data);

					$this->session->set_flashdata(
						'msg',
						'
							<div class="alert alert-success alert-dismissible" role="alert">
								 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
									 <span aria-hidden="true">&times;&nbsp; &nbsp;</span>
								 </button>
								 <strong>Sukses!</strong> Nilai berhasil diisi.
							</div>'
					);
					redirect('users/nilai');
				}
			}
		}
	}


	//------------------- Pembimbing --------------------//
	public function d_siswa($aksi = '', $id = '')
	{
		$ceks = $this->session->userdata('pkl_smk@TA-D3-2023');
		$id_user = $this->session->userdata('id_user@PKL-2023');
		$level = $this->session->userdata('level@PKL-2023');
		if (!isset($ceks)) {
			redirect('web/login');
		} else {

			if ($level != 'pembimbing') {
				redirect('web/error_not_found');
			}

			$data['user']   	 = $this->Mcrud->get_pemb_by_un($ceks);
			if ($aksi == 'd') {
				$this->db->where('nis_siswa', $id);
			}
			$this->db->order_by('nis_siswa', 'DESC');
			$data['v_siswa'] 	 = $this->db->get('tbl_siswa');
			$data['email']		 = '';
			$data['level']		 = 'Pembimbing';

			if ($aksi == 'd') {
				$p = "daftar_siswa/siswa_detail";

				$data['judul_web'] 	  = "Detail Siswa | PKL SMK AL-AMIIN";
			} else {
				$p = "daftar_siswa/siswa";

				$data['judul_web'] 	  = "Data Siswa | PKL SMK AL-AMIIN";
			}

			$this->load->view('users/header', $data);
			$this->load->view("users/pembimbing/$p", $data);
			$this->load->view('users/footer');
		}
	}


	public function bimbingan($aksi = '', $id = '')
	{
		$ceks = $this->session->userdata('pkl_smk@TA-D3-2023');
		$id_user = $this->session->userdata('id_user@PKL-2023');
		$level = $this->session->userdata('level@PKL-2023');
		if (!isset($ceks)) {
			redirect('web/login');
		} else {

			if ($level != 'pembimbing') {
				redirect('web/error_not_found');
			}

			$data['user']   	 = $this->Mcrud->get_pemb_by_un($ceks);
			$this->db->join('tbl_siswa', 'tbl_siswa.nis_siswa=tbl_bimbingan.nis_siswa');
			$this->db->where('nip', $id_user);
			if ($aksi == 'd') {
				$this->db->where('kd_bimbingan', $id);
			}
			$this->db->order_by('kd_bimbingan', 'DESC');
			$data['v_bimbingan'] 	 = $this->db->get('tbl_bimbingan');
			$data['email']		 = '';
			$data['level']		 = 'Pembimbing';

			if ($aksi == 't') {
				$p = "bimbingan/bimbingan_tambah";

				$data['judul_web'] 	  = "Tambah Bimbingan | PKL SMK AL-AMIIN";
				$this->db->order_by('nis_siswa', 'DESC');
				$this->db->order_by('nama_lengkap', 'ASC');
				$data['v_siswa']		 	 = $this->db->get('tbl_siswa');
			} elseif ($aksi == 'd') {
				$p = "bimbingan/bimbingan_detail";

				$data['judul_web'] 	  = "Detail Bimbingan | PKL SMK AL-AMIIN";
			} elseif ($aksi == 'h') {

				$cek_data = $this->db->get_where('tbl_bimbingan', "kd_bimbingan='$id'")->row();
				unlink("$cek_data->file");
				$this->db->delete('tbl_bimbingan', "kd_bimbingan='$id'");

				$this->session->set_flashdata(
					'msg',
					'
						 <div class="alert alert-success alert-dismissible" role="alert">
								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
									<span aria-hidden="true">&times; &nbsp;</span>
								</button>
								<strong>Sukses!</strong> Bimbingan berhasil dihapus.
						 </div>'
				);
				redirect('users/bimbingan');
			} else {
				$p = "bimbingan/bimbingan";

				$data['judul_web'] 	  = "Data Bimbingan | PKL SMK AL-AMIIN";
			}

			$this->load->view('users/header', $data);
			$this->load->view("users/pembimbing/$p", $data);
			$this->load->view('users/footer');


			if (isset($_POST['btnsimpan'])) {
				$nis_siswa	 					= htmlentities(strip_tags($this->input->post('nis_siswa')));
				$judul	 				= htmlentities(strip_tags($this->input->post('judul')));
				$catatan  	 		= htmlentities(strip_tags($this->input->post('catatan')));

				date_default_timezone_set('Asia/Jakarta');
				$tgl = date('Y-m-d');

				$cek_penempatan = $this->db->get_where('tbl_tempat', "nis_siswa='$nis_siswa'");
				if ($cek_penempatan->num_rows() == 0) {
					$this->session->set_flashdata(
						'msg',
						'
								<div class="alert alert-warning alert-dismissible" role="alert">
									 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
										 <span aria-hidden="true">&times;&nbsp; &nbsp;</span>
									 </button>
									 <strong>Gagal!</strong> Siswa belum menentukan tempat.
								</div>'
					);
					redirect('users/nilai/t');
				} else {

					$file_size = 1024 * 5; //5 MB
					$this->upload->initialize(array(
						"upload_path"   => "./lampiran/bimbingan/",
						"allowed_types" => "*",
						"max_size" => "$file_size"
					));

					if (!$this->upload->do_upload('file')) {
						$error = $this->upload->display_errors('<p>', '</p>');
						$this->session->set_flashdata(
							'msg_file',
							'
												 <div class="alert alert-warning alert-dismissible" role="alert">
														<button type="button" class="close" data-dismiss="alert" aria-label="Close">
															<span aria-hidden="true">&times; &nbsp;</span>
														</button>
														<strong>Gagal!</strong> ' . $error . '.
												 </div>'
						);

						redirect('users/bimbingan/t');
					} else {
						$file = $this->upload->data();
						$filename = "lampiran/bimbingan/" . $file['file_name'];
						$file 		= preg_replace('/ /', '_', $filename);

						$data = array(
							'kd_tempat' => $cek_penempatan->row()->kd_tempat,
							'nip'				   => $id_user,
							'nis_siswa'				   => $nis_siswa,
							'tanggal'			 => $tgl,
							'judul'			   => $judul,
							'catatan'			 => $catatan,
							'file'			   => $file
						);
						$this->db->insert('tbl_bimbingan', $data);

						$this->session->set_flashdata(
							'msg',
							'
											<div class="alert alert-success alert-dismissible" role="alert">
												 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
													 <span aria-hidden="true">&times;&nbsp; &nbsp;</span>
												 </button>
												 <strong>Sukses!</strong> Bimbingan berhasil dikirim.
											</div>'
						);
						redirect('users/bimbingan');
					}
				}
			}
		}
	}

	// Controller Monitoring
	public function monitoring_pemb($aksi = '', $id = '')
	{
		$ceks = $this->session->userdata('pkl_smk@TA-D3-2023');
		$id_user = $this->session->userdata('id_user@PKL-2023');
		$level = $this->session->userdata('level@PKL-2023');
		if (!isset($ceks)) {
			redirect('web/login');
		} else {

			if ($level != 'pembimbing') {
				redirect('web/error_not_found');
			}

			$data['user']   	 = $this->Mcrud->get_pemb_by_un($ceks);
			$this->db->join('tbl_siswa', 'tbl_siswa.nis_siswa=tbl_monitoring.nis_siswa');
			$this->db->order_by('kd_monitoring', 'ASC');
			$data['v_monitoring'] 	 = $this->db->get('tbl_monitoring');
			$data['email']		 = '';
			$data['level']		 = 'Pembimbing';

			if ($aksi == 'd') {
				$p = "monitoring_pemb/monitoring_detail";

				$data['judul_web'] 	  = "Detail Log Book | PKL SMK AL-AMIIN";
			} else {
				$p = "monitoring_pemb/monitoring_pemb";

				$data['judul_web'] 	  = "Log Book | PKL SMK AL-AMIIN";
			}

			$this->load->view('users/header', $data);
			$this->load->view("users/pembimbing/$p", $data);
			$this->load->view('users/footer');
		}
	}


	//---------------------------- Siswa ----------------------------//
	public function status_pkl($aksi = '', $id = '')
	{
		$ceks = $this->session->userdata('pkl_smk@TA-D3-2023');
		$id_user = $this->session->userdata('id_user@PKL-2023');
		$level = $this->session->userdata('level@PKL-2023');
		if (!isset($ceks)) {
			redirect('web/login');
		} else {

			if ($level != 'siswa') {
				redirect('web/error_not_found');
			}

			$cek_penempatan = $this->db->get_where('tbl_tempat', array('nis_siswa' => "$id_user", 'status !=' => "ditolak"));
			$this->db->order_by('kd_tempat', 'DESC');
			$cek_penempatan = $this->db->get_where('tbl_tempat', array('nis_siswa' => "$id_user"));

			$data['cek_penempatan'] = $cek_penempatan;
			$data['user']   	 = $this->Mcrud->get_siswa_by_nis($ceks);
			$data['email']		 = '';
			$data['level']		 = 'Siswa';

			if ($aksi == 't') {

				if ($this->db->get_where('tbl_tempat', array('nis_siswa' => "$id_user", 'status !=' => "ditolak"))->num_rows() != 0) {
					redirect('web/error_not_found');
				}

				$p = "status/status_tambah";

				$data['judul_web'] 	  = "Daftar Tempat PKL | PKL SMK AL-AMIIN";

				$this->db->order_by('nama_industri', 'ASC');
				$data['v_industri']   = $this->db->get('tbl_industri');
			} else {
				$p = "status/status";

				$data['judul_web'] 	  = "Status PKL | PKL SMK AL-AMIIN";

				$kd_tempat  = $cek_penempatan->row()->kd_tempat;
				$this->db->order_by('kd_tempat', 'DESC');
				$data['query'] = $this->db->get_where("tbl_tempat", "kd_tempat = '$kd_tempat'")->row();
			}

			$this->load->view('users/header', $data);
			$this->load->view("users/siswa/$p", $data);
			$this->load->view('users/footer');

			if (isset($_POST['btnsimpan'])) {
				$kd_industri	 		= htmlentities(strip_tags($this->input->post('kd_industri')));
				$wilayah	 				= htmlentities(strip_tags($this->input->post('wilayah')));
				$kd_pembimbing		= $this->db->get_where('tbl_siswa', "nis_siswa='$id_user'")->row()->kd_pembimbing;

				date_default_timezone_set('Asia/Jakarta');
				$tgl = date('Y-m-d');
				$tahun = date('Y');

				$file_size = 1024 * 5; //5 MB
				$this->upload->initialize(array(
					"upload_path"   => "./lampiran/surat/",
					"allowed_types" => "*",
					"max_size" => "$file_size"
				));

				if (!$this->upload->do_upload('file')) {
					$error = $this->upload->display_errors('<p>', '</p>');
					$this->session->set_flashdata(
						'msg_file',
						'
												 <div class="alert alert-warning alert-dismissible" role="alert">
														<button type="button" class="close" data-dismiss="alert" aria-label="Close">
															<span aria-hidden="true">&times; &nbsp;</span>
														</button>
														<strong>Gagal!</strong> ' . $error . '.
												 </div>'
					);

					redirect('users/status_pkl/t');
				} else {
					$file = $this->upload->data();
					$filename = $file['file_name'];
					$file 		= preg_replace('/ /', '_', $filename);

					$data = array(
						'nis_siswa'				   => $id_user,
						'kd_pembimbing'		   => $kd_pembimbing,
						'kd_industri'			   => $kd_industri,
						'tanggal'			 => $tgl,
						'wilayah'			 => $wilayah,
						'tahun'			   => $tahun,
						'status'			 => 'proses',
						'surat'			   => $file
					);
					$this->db->insert('tbl_tempat', $data);

					$this->session->set_flashdata(
						'msg',
						'
											<div class="alert alert-success alert-dismissible" role="alert">
												 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
													 <span aria-hidden="true">&times;&nbsp; &nbsp;</span>
												 </button>
												 <strong>Sukses!</strong> Pendaftaran berhasil dikirim.
											</div>'
					);
					redirect('users/status_pkl');
				}
			}
		}
	}

	public function bimbingan_siswa($aksi = '', $id = '')
	{
		$ceks = $this->session->userdata('pkl_smk@TA-D3-2023');
		$id_user = $this->session->userdata('id_user@PKL-2023');
		$level = $this->session->userdata('level@PKL-2023');
		if (!isset($ceks)) {
			redirect('web/login');
		} else {

			if ($level != 'siswa') {
				redirect('web/error_not_found');
			}

			$data['user']   	 = $this->Mcrud->get_siswa_by_nis($ceks);
			$this->db->join('tbl_siswa', 'tbl_siswa.nis_siswa=tbl_bimbingan.nis_siswa');
			$this->db->where('tbl_siswa.nis_siswa',   $id_user);
			if ($aksi == 'd') {
				$this->db->where('kd_bimbingan', $id);
			}
			$this->db->order_by('kd_bimbingan', 'DESC');
			$data['v_bimbingan'] 	 = $this->db->get('tbl_bimbingan');
			$data['email']		 = '';
			$data['level']		 = 'Siswa';

			if ($aksi == 't') {
				$p = "bimbingan_siswa/bimbingan_tambah";

				$data['judul_web'] 	  = "Tambah Bimbingan | PKL SMK AL-AMIIN";
				$this->db->order_by('nis_siswa', 'DESC');
				$this->db->order_by('nama_lengkap', 'ASC');
				$data['v_siswa']		 	 = $this->db->get('tbl_siswa');
				$data['judul_web'] 	  = "Tambah Bimbingan | PKL SMK AL-AMIIN";
				$this->db->order_by('nip', 'DESC');
				$this->db->order_by('nama_lengkap', 'ASC');
				$data['v_pemb']		 	 = $this->db->get('tbl_pembimbing');
			} elseif ($aksi == 'd') {
				$p = "bimbingan_siswa/bimbingan_detail";

				$data['judul_web'] 	  = "Detail Bimbingan | PKL SMK AL-AMIIN";
			} elseif ($aksi == 'h') {

				$cek_data = $this->db->get_where('tbl_bimbingan', "kd_bimbingan='$id'")->row();
				unlink("$cek_data->file");
				$this->db->delete('tbl_bimbingan', "kd_bimbingan='$id'");

				$this->session->set_flashdata(
					'msg',
					'
						 <div class="alert alert-success alert-dismissible" role="alert">
								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
									<span aria-hidden="true">&times; &nbsp;</span>
								</button>
								<strong>Sukses!</strong> Bimbingan berhasil dihapus.
						 </div>'
				);
				redirect('users/bimbingan_siswa');
			} else {
				$p = "bimbingan_siswa/bimbingan_siswa";

				$data['judul_web'] 	  = "Data Bimbingan | PKL SMK AL-AMIIN";
			}

			$this->load->view('users/header', $data);
			$this->load->view("users/siswa/$p", $data);
			$this->load->view('users/footer');


			if (isset($_POST['btnsimpan'])) {
				$nis_siswa	 					= htmlentities(strip_tags($this->input->post('nis_siswa')));
				$nip	 					= htmlentities(strip_tags($this->input->post('nip')));
				$judul	 				= htmlentities(strip_tags($this->input->post('judul')));
				$catatan  	 		= htmlentities(strip_tags($this->input->post('catatan')));

				date_default_timezone_set('Asia/Jakarta');
				$tgl = date('Y-m-d');
				$cek_penempatan = $this->db->get_where('tbl_tempat', "nis_siswa='$nis_siswa'");
				if ($cek_penempatan->num_rows() == 0) {
					$this->session->set_flashdata(
						'msg',
						'
								<div class="alert alert-warning alert-dismissible" role="alert">
									 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
										 <span aria-hidden="true">&times;&nbsp; &nbsp;</span>
									 </button>
									 <strong>Gagal!</strong> Siswa belum menentukan tempat.
								</div>'
					);
					redirect('users/nilai/t');
				} else {

					$file_size = 1024 * 5; //5 MB
					$this->upload->initialize(array(
						"upload_path"   => "./lampiran/bimbingan_siswa/",
						"allowed_types" => "*",
						"max_size" => "$file_size"
					));

					if (!$this->upload->do_upload('file')) {
						$error = $this->upload->display_errors('<p>', '</p>');
						$this->session->set_flashdata(
							'msg_file',
							'
												 <div class="alert alert-warning alert-dismissible" role="alert">
														<button type="button" class="close" data-dismiss="alert" aria-label="Close">
															<span aria-hidden="true">&times; &nbsp;</span>
														</button>
														<strong>Gagal!</strong> ' . $error . '.
												 </div>'
						);

						redirect('users/bimbingan_siswa/t');
					} else {
						$file = $this->upload->data();
						$filename = "lampiran/bimbingan_siswa/" . $file['file_name'];
						$file 		= preg_replace('/ /', '_', $filename);

						$data = array(
							'kd_tempat' => $cek_penempatan->row()->kd_tempat,
							'nis_siswa'				   => $id_user,
							'nip'						=> $nip,
							'nis_siswa'				   => $nis_siswa,
							'tanggal'			 => $tgl,
							'judul'			   => $judul,
							'catatan'			 => $catatan,
							'file'			   => $file
						);
						$this->db->insert('tbl_bimbingan', $data);

						$this->session->set_flashdata(
							'msg',
							'
											<div class="alert alert-success alert-dismissible" role="alert">
												 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
													 <span aria-hidden="true">&times;&nbsp; &nbsp;</span>
												 </button>
												 <strong>Sukses!</strong> Bimbingan berhasil dikirim.
											</div>'
						);
						redirect('users/bimbingan_siswa');
					}
				}
			}
		}
	}

	public function monitoring_siswa($aksi = '', $id = '')
	{
		$ceks = $this->session->userdata('pkl_smk@TA-D3-2023');
		$id_user = $this->session->userdata('id_user@PKL-2023');
		$level = $this->session->userdata('level@PKL-2023');
		if (!isset($ceks)) {
			redirect('web/login');
		} else {

			if ($level != 'siswa') {
				redirect('web/error_not_found');
			}

			$data['user']   	 = $this->Mcrud->get_siswa_by_nis($ceks);
			$this->db->join('tbl_siswa', 'tbl_siswa.nis_siswa=tbl_monitoring.nis_siswa');
			$this->db->where('tbl_siswa.nis_siswa',   $id_user);
			if ($aksi == 'd') {
				$this->db->where('kd_monitoring', $id);
			}
			$this->db->order_by('kd_monitoring', 'DESC');
			$data['v_monitoring'] 	 = $this->db->get('tbl_monitoring');
			$data['email']		 = '';
			$data['level']		 = 'Siswa';

			if ($aksi == 't') {
				$p = "monitoring_siswa/monitoring_tambah";

				$data['judul_web'] 	  = "Tambah Log Book | PKL SMK AL-AMIIN";
				$this->db->order_by('nis_siswa', 'DESC');
				$this->db->order_by('nama_lengkap', 'ASC');
				$data['v_siswa']		 	 = $this->db->get('tbl_siswa');
				$data['judul_web'] 	  = "Tambah Log Book | PKL SMK AL-AMIIN";
				$this->db->order_by('kd_industri', 'DESC');
				$this->db->order_by('nama_industri', 'ASC');
				$data['v_industri']		 	 = $this->db->get('tbl_industri');
			} elseif ($aksi == 'd') {
				$p = "monitoring_siswa/monitoring_detail";

				$data['judul_web'] 	  = "Detail Log Book | PKL SMK AL-AMIIN";
			} elseif ($aksi == 'h') {

				$cek_data = $this->db->get_where('tbl_monitoring', "kd_monitoring='$id'")->row();
				unlink("$cek_data->file");
				$this->db->delete('tbl_monitoring', "kd_monitoring='$id'");

				$this->session->set_flashdata(
					'msg',
					'
						 <div class="alert alert-success alert-dismissible" role="alert">
								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
									<span aria-hidden="true">&times; &nbsp;</span>
								</button>
								<strong>Sukses!</strong> Bimbingan berhasil dihapus.
						 </div>'
				);
				redirect('users/monitoring_siswa');
			} else {
				$p = "monitoring_siswa/monitoring_siswa";

				$data['judul_web'] 	  = "Data Log Book | PKL SMK AL-AMIIN";
			}

			$this->load->view('users/header', $data);
			$this->load->view("users/siswa/$p", $data);
			$this->load->view('users/footer');


			if (isset($_POST['btnsimpan'])) {
				$kd_industri	 		= htmlentities(strip_tags($this->input->post('kd_industri')));
				$nis_siswa	 			= htmlentities(strip_tags($this->input->post('nis_siswa')));
				$kegiatan	 				= htmlentities(strip_tags($this->input->post('kegiatan')));

				$cek_penempatan = $this->db->get_where('tbl_tempat', "nis_siswa='$nis_siswa'");
				if ($cek_penempatan->num_rows() == 0) {
					$this->session->set_flashdata(
						'msg',
						'
								<div class="alert alert-warning alert-dismissible" role="alert">
									 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
										 <span aria-hidden="true">&times;&nbsp; &nbsp;</span>
									 </button>
									 <strong>Gagal!</strong> Siswa belum menentukan tempat.
								</div>'
					);
					redirect('users/nilai/t');
				} else {

				$file_size = 1024 * 5; //5 MB
				$this->upload->initialize(array(
					"upload_path"   => "./uploads/monitoring/",
					"allowed_types" => "*",
					"max_size" => "$file_size"
				));

				if (!$this->upload->do_upload('image')) {
					$error = $this->upload->display_errors('<p>', '</p>');
					$this->session->set_flashdata(
						'msg',
						'
														 <div class="alert alert-warning alert-dismissible" role="alert">
																<button type="button" class="close" data-dismiss="alert" aria-label="Close">
																	<span aria-hidden="true">&times; &nbsp;</span>
																</button>
																<strong>Gagal!</strong> ' . $error . '.
														 </div>'
					);
					redirect('users/monitoring_siswa/t');
				} else {
					$file = $this->upload->data();
					$filename = $file['file_name'];
					$file 		= preg_replace('/ /', '_', $filename);
				}

					$data = array(
						'kd_tempat' => $cek_penempatan->row()->kd_tempat,
						'nis_siswa'		   => $id_user,
						'kd_industri'	   => $kd_industri,
						'nis_siswa'		   => $nis_siswa,
						'kegiatan'			   => $kegiatan,
						'image'			   => $file
					);
					$this->db->insert('tbl_monitoring', $data);

					$this->session->set_flashdata(
						'msg',
						'
											<div class="alert alert-success alert-dismissible" role="alert">
												 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
													 <span aria-hidden="true">&times;&nbsp; &nbsp;</span>
												 </button>
												 <strong>Sukses!</strong> Monitoring berhasil dikirim.
											</div>'
					);
					redirect('users/monitoring_siswa');
					// }
				}
			}
		}
	}


	public function laporan_siswa($aksi = '', $id = '')
	{
		$ceks = $this->session->userdata('pkl_smk@TA-D3-2023');
		$id_user = $this->session->userdata('id_user@PKL-2023');
		$level = $this->session->userdata('level@PKL-2023');
		if (!isset($ceks)) {
			redirect('web/login');
		} else {

			if ($level != 'siswa') {
				redirect('web/error_not_found');
			}

			$data['user']   	 = $this->Mcrud->get_siswa_by_nis($ceks);
			$this->db->join('tbl_siswa', 'tbl_siswa.nis_siswa=tbl_laporan.nis_siswa');
			$this->db->where('tbl_siswa.nis_siswa',   $id_user);
			if ($aksi == 'd') {
				$this->db->where('kd_laporan', $id);
			}
			$this->db->order_by('kd_laporan', 'DESC');
			$data['v_laporan'] 	 = $this->db->get('tbl_laporan');
			$data['email']		 = '';
			$data['level']		 = 'Siswa';

			if ($aksi == 't') {
				$p = "laporan_siswa/laporan_tambah";

				$data['judul_web'] 	  = "Tambah Laporan | PKL SMK AL-AMIIN";
				$this->db->order_by('nis_siswa', 'DESC');
				$this->db->order_by('nama_lengkap', 'ASC');
				$data['v_siswa']		 	 = $this->db->get('tbl_siswa');
				$data['judul_web'] 	  = "Tambah Laporan | PKL SMK AL-AMIIN";
				$this->db->order_by('kd_industri', 'DESC');
				$this->db->order_by('nama_industri', 'ASC');
				$data['v_industri']		 	 = $this->db->get('tbl_industri');
			} elseif ($aksi == 'd') {
				$p = "laporan_siswa/laporan_detail";

				$data['judul_web'] 	  = "Detail Laporan | PKL SMK AL-AMIIN";
			} elseif ($aksi == 'h') {

				$cek_data = $this->db->get_where('tbl_laporan', "kd_laporan='$id'")->row();
				unlink("$cek_data->file");
				$this->db->delete('tbl_laporan', "kd_laporan='$id'");

				$this->session->set_flashdata(
					'msg',
					'
						 <div class="alert alert-success alert-dismissible" role="alert">
								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
									<span aria-hidden="true">&times; &nbsp;</span>
								</button>
								<strong>Sukses!</strong> Bimbingan berhasil dihapus.
						 </div>'
				);
				redirect('users/laporan_siswa');
			} else {
				$p = "laporan_siswa/laporan_siswa";

				$data['judul_web'] 	  = "Data Laporan | PKL SMK AL-AMIIN";
			}

			$this->load->view('users/header', $data);
			$this->load->view("users/siswa/$p", $data);
			$this->load->view('users/footer');


			if (isset($_POST['btnsimpan'])) {
				$kd_industri	 		= htmlentities(strip_tags($this->input->post('kd_industri')));
				$nis_siswa	 			= htmlentities(strip_tags($this->input->post('nis_siswa')));
				$judul	 				= htmlentities(strip_tags($this->input->post('judul')));

				date_default_timezone_set('Asia/Jakarta');
				$tgl = date('Y-m-d');
				$cek_penempatan = $this->db->get_where('tbl_tempat', "nis_siswa='$nis_siswa'");
				if ($cek_penempatan->num_rows() == 0) {
					$this->session->set_flashdata(
						'msg',
						'
								<div class="alert alert-warning alert-dismissible" role="alert">
									 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
										 <span aria-hidden="true">&times;&nbsp; &nbsp;</span>
									 </button>
									 <strong>Gagal!</strong> Siswa belum menentukan tempat.
								</div>'
					);
					redirect('users/nilai/t');
				} else {

					$file_size = 1024 * 5; //5 MB
					$this->upload->initialize(array(
						"upload_path"   => "./lampiran/laporan_siswa/",
						"allowed_types" => "*",
						"max_size" => "$file_size"
					));

					if (!$this->upload->do_upload('file')) {
						$error = $this->upload->display_errors('<p>', '</p>');
						$this->session->set_flashdata(
							'msg_file',
							'
												 <div class="alert alert-warning alert-dismissible" role="alert">
														<button type="button" class="close" data-dismiss="alert" aria-label="Close">
															<span aria-hidden="true">&times; &nbsp;</span>
														</button>
														<strong>Gagal!</strong> ' . $error . '.
												 </div>'
						);

						redirect('users/laporan_siswa/t');
					} else {
						$file = $this->upload->data();
						$filename = "lampiran/laporan_siswa/" . $file['file_name'];
						$file 		= preg_replace('/ /', '_', $filename);

						$data = array(
							'kd_tempat' => $cek_penempatan->row()->kd_tempat,
							'nis_siswa'		   => $id_user,
							'kd_industri'	   => $kd_industri,
							'nis_siswa'		   => $nis_siswa,
							'judul'			   => $judul,
							'file'			   => $file
						);
						$this->db->insert('tbl_laporan', $data);

						$this->session->set_flashdata(
							'msg',
							'
											<div class="alert alert-success alert-dismissible" role="alert">
												 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
													 <span aria-hidden="true">&times;&nbsp; &nbsp;</span>
												 </button>
												 <strong>Sukses!</strong> Sidang berhasil dikirim.
											</div>'
						);
						redirect('users/laporan_siswa');
					}
				}
			}
		}
	}

	public function sidang_siswa($aksi = '', $id = '')
	{
		$ceks = $this->session->userdata('pkl_smk@TA-D3-2023');
		$id_user = $this->session->userdata('id_user@PKL-2023');
		$level = $this->session->userdata('level@PKL-2023');
		if (!isset($ceks)) {
			redirect('web/login');
		} else {

			if ($level != 'siswa') {
				redirect('web/error_not_found');
			}

			$data['user']   	 = $this->Mcrud->get_siswa_by_nis($ceks);
			$this->db->join('tbl_siswa', 'tbl_siswa.nis_siswa=tbl_sidang.nis_siswa');
			$this->db->where('tbl_siswa.nis_siswa',   $id_user);
			if ($aksi == 'd') {
				$this->db->where('kd_sidang', $id);
			}
			$this->db->order_by('kd_sidang', 'DESC');
			$data['v_sidang'] 	 = $this->db->get('tbl_sidang');
			$data['email']		 = '';
			$data['level']		 = 'Siswa';

			if ($aksi == 't') {
				$p = "sidang_siswa/sidang_tambah";

				$data['judul_web'] 	  = "Tambah Sidang | PKL SMK AL-AMIIN";
				$this->db->order_by('nis_siswa', 'DESC');
				$this->db->order_by('nama_lengkap', 'ASC');
				$data['v_siswa']		 	 = $this->db->get('tbl_siswa');
				$data['judul_web'] 	  = "Tambah sidang | PKL SMK AL-AMIIN";
				$this->db->order_by('kd_industri', 'DESC');
				$this->db->order_by('nama_industri', 'ASC');
				$data['v_industri']		 	 = $this->db->get('tbl_industri');
			} elseif ($aksi == 'd') {
				$p = "sidang_siswa/sidang_detail";

				$data['judul_web'] 	  = "Detail Sidang | PKL SMK AL-AMIIN";
			} elseif ($aksi == 'h') {

				$cek_data = $this->db->get_where('tbl_sidang', "kd_sidang='$id'")->row();
				unlink("$cek_data->file");
				$this->db->delete('tbl_sidang', "kd_sidang='$id'");

				$this->session->set_flashdata(
					'msg',
					'
						 <div class="alert alert-success alert-dismissible" role="alert">
								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
									<span aria-hidden="true">&times; &nbsp;</span>
								</button>
								<strong>Sukses!</strong> Bimbingan berhasil dihapus.
						 </div>'
				);
				redirect('users/sidang_siswa');
			} else {
				$p = "sidang_siswa/sidang_siswa";

				$data['judul_web'] 	  = "Data Sidang | PKL SMK AL-AMIIN";
			}

			$this->load->view('users/header', $data);
			$this->load->view("users/siswa/$p", $data);
			$this->load->view('users/footer');


			if (isset($_POST['btnsimpan'])) {
				$kd_industri	 		= htmlentities(strip_tags($this->input->post('kd_industri')));
				$nis_siswa	 			= htmlentities(strip_tags($this->input->post('nis_siswa')));
				$judul	 				= htmlentities(strip_tags($this->input->post('judul')));

				date_default_timezone_set('Asia/Jakarta');
				$tgl = date('Y-m-d');
				$cek_penempatan = $this->db->get_where('tbl_tempat', "nis_siswa='$nis_siswa'");
				if ($cek_penempatan->num_rows() == 0) {
					$this->session->set_flashdata(
						'msg',
						'
								<div class="alert alert-warning alert-dismissible" role="alert">
									 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
										 <span aria-hidden="true">&times;&nbsp; &nbsp;</span>
									 </button>
									 <strong>Gagal!</strong> Siswa belum menentukan tempat.
								</div>'
					);
					redirect('users/nilai/t');
				} else {

					$file_size = 1024 * 5; //5 MB
					$this->upload->initialize(array(
						"upload_path"   => "./lampiran/sidang_siswa/",
						"allowed_types" => "*",
						"max_size" => "$file_size"
					));

					if (!$this->upload->do_upload('file')) {
						$error = $this->upload->display_errors('<p>', '</p>');
						$this->session->set_flashdata(
							'msg_file',
							'
												 <div class="alert alert-warning alert-dismissible" role="alert">
														<button type="button" class="close" data-dismiss="alert" aria-label="Close">
															<span aria-hidden="true">&times; &nbsp;</span>
														</button>
														<strong>Gagal!</strong> ' . $error . '.
												 </div>'
						);

						redirect('users/sidang_siswa/t');
					} else {
						$file = $this->upload->data();
						$filename = "lampiran/sidang_siswa/" . $file['file_name'];
						$file 		= preg_replace('/ /', '_', $filename);

						$data = array(
							'kd_tempat' => $cek_penempatan->row()->kd_tempat,
							'nis_siswa'		   => $id_user,
							'kd_industri'	   => $kd_industri,
							'nis_siswa'		   => $nis_siswa,
							'judul'			   => $judul,
							'file'			   => $file
						);
						$this->db->insert('tbl_sidang', $data);

						$this->session->set_flashdata(
							'msg',
							'
											<div class="alert alert-success alert-dismissible" role="alert">
												 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
													 <span aria-hidden="true">&times;&nbsp; &nbsp;</span>
												 </button>
												 <strong>Sukses!</strong> Sidang berhasil dikirim.
											</div>'
						);
						redirect('users/sidang_siswa');
					}
				}
			}
		}
	}



	public function nilai_siswa($aksi = '', $id = '')
	{
		$ceks = $this->session->userdata('pkl_smk@TA-D3-2023');
		$id_user = $this->session->userdata('id_user@PKL-2023');
		$level = $this->session->userdata('level@PKL-2023');
		if (!isset($ceks)) {
			redirect('web/login');
		} else {

			if ($level != 'siswa') {
				redirect('web/error_not_found');
			}

			$data['user']   	 = $this->Mcrud->get_siswa_by_nis($ceks);
			$this->db->join('tbl_tempat', 'tbl_tempat.nis_siswa=tbl_siswa.nis_siswa');
			$this->db->join('tbl_nilai', 'tbl_nilai.kd_tempat=tbl_tempat.kd_tempat');
			if ($aksi == 'd') {
				$this->db->where('tbl_nilai.kd_nilai', $id);
			}
			$this->db->where('tbl_siswa.nis_siswa', $id_user);
			$this->db->order_by('tbl_siswa.nama_lengkap', 'ASC');
			$this->db->order_by('tbl_tempat.tahun', 'DESC');
			$data['v_nilai'] 	 = $this->db->get('tbl_siswa');
			$data['email']		 = '';
			$data['level']		 = 'Siswa';

			if ($aksi == 'd') {
				$p = "nilai/nilai_detail";

				$data['judul_web'] 	  = "Detail Nilai | PKL SMK AL-AMIIN";
			} else {
				$p = "nilai/nilai";

				$data['judul_web'] 	  = "Data Nilai | PKL SMK AL-AMIIN";
			}

			$this->load->view('users/header', $data);
			$this->load->view("users/siswa/$p", $data);
			$this->load->view('users/footer');
		}
	}
}
