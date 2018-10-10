<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Upload3 extends CI_Controller {

	public function __construct(){
            parent::__construct();
            $this->load->model('winnowingmodel3');
            $this->load->helper('upload','form','url','date');
            $this->load->library('session','form_validation');
            $this->load->database();
        }

	public function index()	{
		$this->session->unset_userdata('user');
		$data=array('title'=>'Preprocessing',
					'subtitle'=>'Upload file untuk Preprocess',
					'isi'=>'home/upload_view3'
					);
		$this->load->view('layout/wrapper',$data);
	}

	public function status(){
		$this->session->unset_userdata('hash');
		$this->session->unset_userdata('fingerprint');
		$this->session->unset_userdata('kgram');
		$this->session->unset_userdata('wgram');

		$data=array('title'=>'File Status',
					'subtitle'=>'Halaman status dokumen anda',
					'isi'=>'home/status_view3',
					'user'=>$this->session->userdata('user'),
					);
		
		$this->load->view('layout/wrapper',$data);
	}

	
	public function result()	{
		$this->load->library('winnowing');
		$text1 = $this->session->userdata('user')['text'];
		$w = new winnowing();
		$w->SetWord1($text1);
		$w->SetWord2('dfdghjk');
        $w->SetPrimeNumber(7);
        $n = $this->session->userdata('kgram');
        $w->SetNGramValue($n);
        $window = $this->session->userdata('wgram');
        $w->SetNWindowValue($window);
         
        $w->process();
		$data=array('title'=>'File Status',
					'subtitle'=>'Halaman status dokumen anda',
					'isi'=>'home/result_view3',
					'user'=>$this->session->userdata('user'),
					'hash'=>$this->session->userdata('hash'),					
					'kgram'=>$this->session->userdata('kgram'),
					'wgram'=>$this->session->userdata('wgram'),	
					'fingerprint'=>$this->session->userdata('fingerprint'),		
					'time'=>$this->session->userdata('time'),		
					'table'=>$this->winnowingmodel3->showdata(),
					'w'=> $w
				);
		
		$this->load->view('layout/wrapper',$data);
	}

	public function similarity()	{
		
		$start = microtime(true);
		function jaccardCoeffient($text1,$text2){
			$intersect=0;
			foreach ($text1 as $r) {
							# code...
					if(in_array($r, $text2)){
						$intersect++;
					}
				}			
					$a=count($text1);
					$b=count($text2);
					$union = $a+$b-$intersect;

					$result=$intersect/$union*100;
					return round($result,2);
		}

		$selected=$this->input->post('selected');
		$data2 = $this->winnowingmodel3->getselect($selected);
		$judul2=$data2->judul;
		$hash2 =$this->winnowingmodel3->rollingHash($this->session->userdata('kgram'), $data2->text);
		$fingerprint2 = $this->winnowingmodel3->winnowingFingerprint($this->session->userdata('wgram'), array('hash' => $hash2));
		$text1=array(
			'judul'=>$this->session->userdata('user')['judul'],
			'fingerprint' =>$this->session->userdata('fingerprint')
			);
		$text2=array(
			'judul'=>$judul2,
			'fingerprint' =>$fingerprint2
			);
		$similarity=jaccardCoeffient($text1['fingerprint'], $text2['fingerprint']);
		$time_elapsed_secs = microtime(true) - $start;
		$time_elapsed_secs = round($time_elapsed_secs,2);
		$data=array('title'=>'Hasil Similarity',
					'subtitle'=>'Silakan check persentase kesamaan file anda',
					'isi'=>'home/similarity_view3',
					'text1'=>$text1,
					'text2'=>$text2,
					'similarity'=>$similarity,
					'time'=>$time_elapsed_secs,
					);		
		$this->load->view('layout/wrapper',$data);
	}

	public function similarity2()	{
		$time_start = microtime(true);

		$this->load->library('winnowing');
		$text1 = $this->session->userdata('user')['text'];
		$selected=$this->input->post('selected');
		$data2 = $this->winnowingmodel3->getselect($selected);
		$text2 = $data2->text;
		$w = new winnowing();
		$w->SetWord1($text1);
		$w->SetWord2($text2);
        $w->SetPrimeNumber(7);
        $n = $this->session->userdata('kgram');
        $w->SetNGramValue($n);
        $window = $this->session->userdata('wgram');
        $w->SetNWindowValue($window);
         
        $w->process();

        $time_elapsed_secs = microtime(true)-$time_start;
		
		$data=array(
			'title'=>'Hasil Similarity',
			'subtitle'=>'Silakan check persentase kesamaan file anda',
			'isi'=>'home/similarity_view5',
			'w' => $w,
			'time'=>$time_elapsed_secs,
			'text1_judul' => $this->session->userdata('user')['judul'],
			'text2_judul' => $data2->judul,
			'window' => $window
		);

		$this->load->view('layout/wrapper',$data);
	}

	public function simpandata(){
		$this->winnowingmodel3->uploadData($this->session->userdata('user'));
	}

	public function runWinnowing(){
		$start = microtime(true);
		ini_set('max_execution_time', 300);
		$this->form_validation->set_rules('kgram','Kgram','required');
		$this->form_validation->set_rules('wgram','Wgram','required');

		if($this->form_validation->run()==FALSE){
			$this->session->set_flashdata('Message','Nilai Kgram dan Wgram belum diisi!');
			redirect('upload3/status');
		}
		else{
			
			$text=$this->input->post('cleantext');
			$kgram=$this->input->post('kgram');
			$wgram=$this->input->post('wgram');
			$hash = $this->winnowingmodel3->rollingHash($kgram, $text);
			$this->session->set_userdata('hash',$hash);
			$fingerprint= $this->winnowingmodel3->winnowingFingerprint($wgram, array('hash' => $hash));
			$time_elapsed_secs = microtime(true) - $start;
			$time_elapsed_secs = round($time_elapsed_secs,2);
			$this->session->set_userdata('time',$time_elapsed_secs);
			$this->session->set_userdata('fingerprint',$fingerprint);
			$this->session->set_userdata('kgram',$kgram);
			$this->session->set_userdata('wgram',$wgram);
			//$this->session->set_userdata('fingerprint',$data);

			redirect('upload3/result');

			
			
		}
	}

	public function uploadValidation(){
		$this->form_validation->set_rules('judulskripsi','JudulSkripsi','required');
		$this->form_validation->set_rules('namauploader','NamaUploader','required');
		$judul=$this->input->post('namauploader');
		$database=$this->winnowingmodel3->showdata();
		foreach ($database as $r) {
			
			if ($judul==$r->judul) {
				$this->session->set_flashdata('Message','Judul Sudah Ada D Database!');
				redirect('upload3');
			}
		}

		if(!isset($_FILES['fileToUpload']))
		{
			$this->session->set_flashdata('Message','Judul Belum Terisi Atau File Belum Diupload Silahkan Lakukan Check Ulang!');
			redirect('upload3');
		}

		if($this->form_validation->run()==FALSE)
		{
			$this->session->set_flashdata('Message','Judul Belum Terisi Atau File Belum Diupload Silahkan Lakukan Check Ulang!');
			redirect('upload3');
		}
		else
		{
			$parser = new \Smalot\PdfParser\Parser();
			if(isset($_FILES['fileToUpload'])) {
			$pdf = $parser->parseFile($_FILES['fileToUpload']['tmp_name']);
			$text = $pdf->getText();
			$data['nama']=strtolower($this->input->post('namauploader'));
			$data['judul']=strtolower($this->input->post('judulskripsi'));
			$data['text']=$text;
			// $data['text']=$this->stemText($text);
			// echo sprintf("Text Sebelum Stem <br/>%s<hr>", $text);
			// echo sprintf("Text Setelah Stem <br/>%s<hr>", $data['text']);
			// die();
			$data = $this->winnowingmodel3->filterText($data);
			$data['date_upload']= date("Y-m-d");
			$this->session->set_userdata('user',$data);
			redirect('upload3/status');
			//$this->winnowingmodel3->uploadData($data);
			}
		
		}

	}

	// public function stemText($text='')
	// {
	// 	$stemmerFactory = new \Sastrawi\Stemmer\StemmerFactory();
	// 	$stemmer  = $stemmerFactory->createStemmer();
	// 	$stopWordRemoverFactory = new \Sastrawi\StopWordRemover\StopWordRemoverFactory();
	// 	$remover = $stopWordRemoverFactory->createStopWordRemover();

	// 	$output   = $stemmer->stem($remover->remove($text));
	// 	return $output;
	// }

	// public function stemm($text='')
	// {
	// 	$worker = new \Algenza\Fztstemming\Worker;
	// 	$text = explode(" ", $text);
	// 	$hasil = $worker->multiWords($text);
	// 	$hasil = implode(" ", $hasil);
	// 	return ($hasil);
	// }
}
