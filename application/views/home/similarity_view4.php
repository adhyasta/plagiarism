<div class="container">
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
             
                
                <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th class ="col-lg-2"></th>
                        <th class ="col-lg-5">File TA Anda</th>
                        <th class ="col-lg-5">File TA Database</th>
                      </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td class ="col-lg-2"><label>Judul</label></td>
                        <td class ="col-lg-5"><h4 class="text-primary" id="comment"><?php echo $text1_judul;?></h4></td>
                        <td class ="col-lg-5"><h4 class="text-primary" id="comment"><?php echo $text2_judul;?></h4></td>
                    </tr>
                    <tr>
                        <td class ="col-lg-2"><label>Gram </label></td>
                        <td class ="col-lg-5"><textarea disabled class="form-control" rows="5" name ="cleantext" id="cleantext"><?php echo implode('|', $w->GetNGramFirst() );?></textarea></td>
                        <td class ="col-lg-5"><textarea disabled class="form-control" rows="5" name ="cleantext" id="cleantext"><?php echo implode('|', $w->GetNGramSecond());?></textarea></td>
                    </tr>
                    <tr>
                        <td class ="col-lg-2"><label>Rolling Hash </label></td>
                        <td class ="col-lg-5"><textarea disabled class="form-control" rows="5" name ="cleantext" id="cleantext"><?php echo implode('|', $w->GetRollingHashFirst() );?></textarea></td>
                        <td class ="col-lg-5"><textarea disabled class="form-control" rows="5" name ="cleantext" id="cleantext"><?php echo implode('|', $w->GetRollingHashSecond());?></textarea></td>
                    </tr>
                    <tr>
                        <td class ="col-lg-2"><label>Window </label></td>
                        <td class ="col-lg-5"><textarea disabled class="form-control" rows="5" name="cleantext" id="cleantext"><?php
                            $wdf = $w->GetWindowFirst();
                            $sWf = '';
                            for($i = 0; $i< count($wdf); $i++){
                                    $s = '';
                                    for($j=0; $j < $window; $j++) {
                                            $s .= $wdf[$i][$j]. ' ';
                                    }
                                    $sWf = "W-".($i+1)." : {".rtrim($s, ' ')."}\n";
                                    echo $sWf;
                            }
                            ?>
                        </textarea></td>
                        <td class ="col-lg-5"><textarea disabled class="form-control" rows="5" name ="cleantext" id="cleantext"><?php
                            $wds = $w->GetWindowSecond();
                            $sWs = '';
                            for($i = 0; $i< count($wds); $i++){
                                $s = '';
                                for($j=0; $j < $window; $j++){
                                        $s .= $wds[$i][$j]. ' ';
                                }
                                $sWs = "W-".($i+1)." : {".rtrim($s, ' ')."}\n";
                                echo $sWs;
                            }
                            ?>
                        </textarea></td>
                    </tr>
                    <tr>
                        <td class ="col-lg-2"><label>Fingerprint</label></td>
                        <td class ="col-lg-5"><textarea disabled class="form-control" rows="5" name ="cleantext" id="cleantext"><?php echo implode('|', $w->GetFingerprintsFirst() );?></textarea></td>
                        <td class ="col-lg-5"><textarea disabled class="form-control" rows="5" name ="cleantext" id="cleantext"><?php echo implode('|', $w->GetFingerprintsSecond());?></textarea></td>
                    </tr>
                    <tr>
                        <td class ="col-lg-2"><label>Similarity</label></td>
                        <td colspan="2"><?php echo  $w->GetJaccardCoefficient();?> %</td>
                    </tr>
                    <tr>
                        <td class ="col-lg-2"><label>Time Elapsed</label></td>
                        <td colspan="2"><strong><?=number_format($time, 5, ".", ",");?></strong> Second</td>
                    </tr>
                    </tbody>
                </table>
                
                <a href="<?php echo base_url('upload2/status'); ?> " class="btn btn-block btn-success"><span class="glyphicon glyphicon-circle-arrow-left"></span>  Input N-Gram Lain</a>
                <button type="button" class="btn btn-info btn-block" id="save"><span class="glyphicon glyphicon-file"></span> Save</button>
                <a href="<?php echo base_url('upload2/result'); ?> " class="btn btn-block btn-danger"> <span class="glyphicon glyphicon-circle-arrow-left"></span> Back</a> 
            </div>
        </div>
    </div>
    <script>
    $(document).ready(function(){
        $("#save").click(function(){
            $.post("<?php echo base_url(); ?>upload/simpandata",
            function(){
                alert("Upload Sukses \n\nFile Sudah Tersimpan Di Database ");
            });
        });
    });
    </script>