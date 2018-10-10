<div class="container">
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
             

                <form action="<?php echo base_url(); ?>upload/newCheckSimiliarity" method="post" enctype="multipart/form-data">
                <table class = "table">
                    <tr>
                        <td class ="col-lg-5"><label for="usr">Hasil Preprocessing File Pertama</label></td>
                        <td class ="col-lg-7"><textarea readonly class="form-control" rows="5" name ="cleantext" id="cleantext"><?php echo $user['text'];?></textarea></td>
                    </tr>
                    <tr>
                        <td class ="col-lg-5"><label for="usr">Hasil Preprocessing File Kedua </label></td>
                        <td class ="col-lg-7"><textarea readonly class="form-control" rows="5" name ="cleantext" id="cleantext"><?php echo $user2['text'];?></textarea></td>
                    </tr>
                     <tr>
                        <td class ="col-lg-5"><label for="usr">K Gram</label></td>
                        <td class ="col-lg-7"><textarea class="form-control" rows="1" name="kgram" id="kgram" value="2">2</textarea></td>
                    </tr>
                     <tr>
                        <td class ="col-lg-5"><label for="usr">W Gram</label></td>
                        <td class ="col-lg-7"><textarea class="form-control" rows="1" name="wgram" id="wgram" value="2">2</textarea></td>
                    </tr>
                     <tr>
                        <td class ="col-lg-2"><label>Time Elapsed</label></td>
                        <td colspan="2"><strong>{elapsed_time}</strong> Second</td>
                    </tr>
                     
                </table>
                        <span class="error has-error"><?php echo $this->session->flashdata('Message'); ?></span>
                        <button type="submit" class="btn btn-block btn-success">Process File Anda</button>
                        <a href="<?php echo base_url('upload'); ?> " class="btn btn-block btn-danger"> <span class="glyphicon glyphicon-circle-arrow-left"></span> Back</a> 
                        
                </form>
            </div>
        </div>
    </div>