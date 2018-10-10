<?php 

class Winnowing {
        private $word1 = '';
        private $word2 = '';
 
        //input properties
        private $prime_number = 7;
        private $n_gram_value = 2;
        private $n_window_value = 2;    
       
        //output properties
        private $arr_n_gram1;
        private $arr_n_gram2;
        private $arr_rolling_hash1;
        private $arr_rolling_hash2;
        private $arr_window1;
        private $arr_window2;
        private $arr_fingerprints1;
        private $arr_fingerprints2;
 
        public function SetPrimeNumber($value){
                $this->prime_number = $value;
        }      
        public function SetNGramValue($value){
                $this->n_gram_value = $value;
        }
        public function SetNWindowValue($value){
                $this->n_window_value = $value;
        }
        public function GetNGramFirst(){
                return $this->arr_n_gram1;
        }
        public function GetNGramSecond(){
                return $this->arr_n_gram2;
        }
        public function GetRollingHashFirst(){
                return $this->arr_rolling_hash1;
        }
        public function GetRollingHashSecond(){
                return $this->arr_rolling_hash2;
        }
        public function GetWindowFirst(){
                return $this->arr_window1;
        }
        public function GetWindowSecond(){
                return $this->arr_window2;
        }
        public function GetFingerprintsFirst(){
                return $this->arr_fingerprints1;
        }
        public function GetFingerprintsSecond(){
                return $this->arr_fingerprints2;
        }
        public function GetJaccardCoefficient($prosen = true){
                if($prosen)
                        return round( ($this->jaccard_coefficient * 100), 2);
                else
                        return $this->jaccard_coefficient;
        }
 
        function __construct(){
               
        }
 
        public function process(){
                if (($this->word1 == '') || ($this->word2 == '')) exit;
 
                //langkah 1 : buang semua huruf yang bukan kelompok [a-z A-Z 0-9] dan ubah menjadi huruf kecil semua (lowercase)
                $this->word1 = strtolower(str_replace(' ','',preg_replace("/[^a-zA-Z0-9\s-]/", "", $this->word1)));
                $this->word2 = strtolower(str_replace(' ','',preg_replace("/[^a-zA-Z0-9\s-]/", "", $this->word2)));
 
                //langkah 2 : buat N-Gram
                $this->arr_n_gram1 = $this->n_gram($this->word1, $this->n_gram_value);
                $this->arr_n_gram2 = $this->n_gram($this->word2, $this->n_gram_value);
 
                //langkah 3 : rolling hash untuk masing-masing n gram
                $this->arr_rolling_hash1 = $this->rolling_hash($this->arr_n_gram1);
                $this->arr_rolling_hash2 = $this->rolling_hash($this->arr_n_gram2);
 
                //langkah 4 : buat windowing untuk masing-masing tabel hash
                $this->arr_window1 = $this->windowing($this->arr_rolling_hash1, $this->n_window_value);
                $this->arr_window2 = $this->windowing($this->arr_rolling_hash2, $this->n_window_value);
 
                //langkah 5 : cari nilai minimum masing-masing window table (fingerprints)
                $this->arr_fingerprints1 = $this->fingerprints($this->arr_window1);
                $this->arr_fingerprints2 = $this->fingerprints($this->arr_window2);
 
                //langkah 6 : hitung koefisien plagiarisme memanfaatkan persamaan Jaccard Coefficient
                $this->jaccard_coefficient = $this->jaccard_coefficient($this->arr_fingerprints1, $this->arr_fingerprints2);
        }
 
        private function n_gram($word, $n) {
                $ngrams = array();
                $length = strlen($word);
                for($i = 0; $i < $length; $i++) {
                        if($i > ($n - 2)) {
                                $ng = '';
                                for($j = $n-1; $j >= 0; $j--) {
                                        $ng .= $word[$i-$j];
                                }
                                $ngrams[] = $ng;
                        }
                }
                return $ngrams;
        }
 
        private function char2hash($string) {
                if (strlen($string) == 1) {
                        return ord($string);
                } else {
                        $result = 0;
                        $length = strlen($string);
                        for ($i = 1; $i <= $length; $i++) {
                                $result += ord(substr($string, $i-1, 1)) * pow($this->prime_number, $length-$i);
                        }
                        return $result;
                }
        }
 
        private function rolling_hash($ngram){
                $roll_hash = array();  
                foreach($ngram as $ng){
                        $roll_hash[] = $this->char2hash($ng);
                }
                return $roll_hash;
        }
 
        private function windowing($rolling_hash, $n){
                $ngram = array();
                $length = count($rolling_hash);
                $x = 0;
                for($i = 0; $i < $length; $i++){
                        if($i > ($n - 2)) {
                                $ngram[$x] = array();
                                $y = 0;
                                for($j = $n-1; $j >= 0; $j--){
                                        $ngram[$x][$y] = $rolling_hash[$i-$j];
                                        $y++;
                                }
                                $x++;
                        }
                }
                //echo $x.' '.$y;
                return $ngram;
        }
 
        private function fingerprints($window_table){
                $fingers = array();    
                for($i = 0; $i < count($window_table); $i++){
                        $min = $window_table[$i][0];
                        for($j = 1 ; $j < $this->n_window_value; $j++){
                                if($min > $window_table[$i][$j])
                                        $min = $window_table[$i][$j];
                        }
                        $fingers[] = $min;
                }
                return $fingers;       
        }

        private function array_intersect($fingerprint1, $fingerprint2) {
                return array_unique(array_merge($fingerprint1, $fingerprint2));
        } 

 
        private function jaccard_coefficient($fingerprint1, $fingerprint2) {
                if (count($fingerprint1) < count($fingerprint2)) 
                {
                      $arr_intersect = array_intersect( $fingerprint1, $fingerprint2 );
                } else {
                       $arr_intersect = array_intersect( $fingerprint2, $fingerprint1 );
                }
                
                
                /*$arr_intersect = array_intersect( $fingerprint1, $fingerprint2 );*/
                
                $arr_union = count( $fingerprint1) + count($fingerprint2 );
                $count_intersect_fingers = count($arr_intersect);
                $count_union_fingers = $arr_union;

 
                $coefficient = $count_intersect_fingers /
                        ($count_union_fingers - $count_intersect_fingers );
                return $coefficient;
        }

        public function SetWord1($word1='')
        {
                $this->word1 = $word1;
        }
        public function GetWord1()
        {
                return $this->word1;
        }
        public function SetWord2($word2='')
        {
                $this->word2 = $word2;
        }
        public function GetWord2()
        {
                return $this->word2;
        }
}