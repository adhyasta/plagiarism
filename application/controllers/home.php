<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {

	public function index(){
		$data=array('title'=>'Algoritma Winnowing', 
					'subtitle'=>'Algoritma untuk Mengukur Plagiarisme Dua Dokumen',
					'isi' =>'home/index_home'
					);
					
		$this->load->view('layout/wrapper',$data);
	}
}
