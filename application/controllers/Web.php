<?php
defined('BASEPATH') or exit('No direct script access allowed');
error_reporting(0);
class Web extends CI_Controller
{

	public function index()
	{
		$ceks = $this->session->userdata('pkl_smk@TA-D3-2023');
		// if(!isset($ceks)) {
		// redirect('web/login');
		// }else{
		// 			redirect('users');
		// }

		$data['judul_web'] = "PKL SMK AL-AMIIN";

		$this->load->view('web/header', $data);
		$this->load->view('web/beranda');
		$this->load->view('web/footer');
	}


	public function industri($id = '')
	{
		if ($id != '') {
			$this->db->where('kd_industri', $id);
		}
		$this->db->order_by('kd_industri', 'DESC');
		$data['v_industri']		 = $this->db->get('tbl_industri');

		if ($id == '') {
			$data['judul_web'] = 'Industri';

			$p = 'industri';
		} else {
			$data['judul_web'] = $data['v_industri']->row()->nama_industri;

			$p = 'industri_detail';

			if ($data['v_industri']->num_rows() == 0) {
				redirect('web/error_not_found');
			}
		}

		$this->load->view('web/header', $data);
		$this->load->view('web/' . $p, $data);
		$this->load->view('web/footer');
	}

	public function info()
	{

		$data['judul_web'] = "PKL SMK AL-AMIIN";

		if ($aksi == 'd') {
			$p = "informasi/informasi_detail";

			$data['judul_web'] 	  = "Detail Informasi | PKL SMK AL-AMIIN";
		} 
		$this->load->view('web/header', $data);
		$this->load->view('web/informasi',$p);
		$this->load->view('web/footer');
	}

	public function informasi()
	{

		$data['judul_web'] = "PKL SMK AL-AMIIN";

		$this->load->view('web/header', $data);
		$this->load->view('web/informasi_detail');
		$this->load->view('web/footer');
	}

	public function login()
	{
		$ceks = $this->session->userdata('pkl_smk@TA-D3-2023');
		if (isset($ceks)) {
			redirect('users');
		} else {
			$data['judul_web'] = "PKL SMK AL-AMIIN";

			$this->load->view('web/header', $data);
			$this->load->view('web/login');
			$this->load->view('web/footer');

			if (isset($_POST['btnlogin'])) {
				$username = htmlentities(strip_tags($_POST['username']));
				$pass	   = htmlentities(strip_tags(md5($_POST['password'])));

				$cek_un    = $this->Mcrud->get_users_by_un($username);
				$cek_pemb  = $this->db->get_where("tbl_pembimbing", array('username' => "$username"));
				$cek_siswa = $this->db->get_where("tbl_siswa", array('nis_siswa' => "$username"));
				if ($cek_un->num_rows() != 0) {
					$query  = $cek_un;
				} elseif ($cek_pemb->num_rows() != 0) {
					$query  = $cek_pemb;
				} else {
					$query  = $cek_siswa;
				}
				$cek    = $query->result();
				$cekun  = $username;
				$jumlah = $query->num_rows();

				if ($jumlah == 0) {
					$this->session->set_flashdata(
						'msg',
						'
									 <div class="alert alert-danger alert-dismissible" role="alert">
									 		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
												<span aria-hidden="true">&times;&nbsp;</span>
											</button>
											<strong>NIS/Username "' . $username . '"</strong> belum terdaftar.
									 </div>'
					);
					redirect('web/login');
				} else {
					$row = $query->row();
					$cekpass = $row->password;
					if ($cekpass <> $pass) {
						$this->session->set_flashdata(
							'msg',
							'<div class="alert alert-warning alert-dismissible" role="alert">
													 		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
																<span aria-hidden="true">&times;&nbsp;</span>
															</button>
															<strong>NIS/Username atau Password Salah!</strong>.
													 </div>'
						);
						redirect('web/login');
					} else {

						if ($cek_un->num_rows() != 0) {
							//  $this->Mcrud->update_user(array('username' => $username), $data);

							$id_user  = $row->id_user;
							$level    = 'admin';
						} elseif ($cek_pemb->num_rows() != 0) {
							//  $this->db->update('tbl_pemb', $data, array('username' => $username));

							$id_user  = $row->nip;
							$level    = 'pembimbing';
						} else {
							//  $this->db->update('tbl_siswa', $data, array('nis' => $username));

							$id_user  = $row->nis_siswa;
							$level    = "siswa";
						}

						$this->session->set_userdata('pkl_smk@TA-D3-2023', "$cekun");
						$this->session->set_userdata('id_user@PKL-2023', "$id_user");
						$this->session->set_userdata('level@PKL-2023', "$level");

						redirect('users');
					}
				}
			}
		}
	}


	public function logout()
	{
		if ($this->session->has_userdata('pkl_smk@TA-D3-2023') and $this->session->has_userdata('id_user@PKL-2023') and $this->session->has_userdata('level@PKL-2023')) {
			$this->session->sess_destroy();
			redirect('');
		}
		redirect('');
	}

	function error_not_found()
	{
		$this->load->view('404_content');
	}
}
