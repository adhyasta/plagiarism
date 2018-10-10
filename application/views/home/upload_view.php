<div class="container">
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                <div class="progress progress-loading" style="display: none;">
                    <div class="progress-bar" role="progressbar"
                aria-valuemin="0" aria-valuemax="100">
                        Loading <span class="loading-titik-titik">.</span>
                    </div>
                </div>

            </div>
            <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                <form action="<?php echo base_url(); ?>upload/uploadValidation" method="post" enctype="multipart/form-data">
                
                
                <br>
                <label class="btn btn-danger btn-file center-block">
                <span class="glyphicon glyphicon-file"></span>
                    Pilih File Upload 1
                <input accept=".pdf" style="display: none;" type="file" name="fileToUpload1" id="fileToUpload1">
                </label>
                <br>
                <label class="btn btn-success btn-file center-block">
                <span class="glyphicon glyphicon-file"></span>
                    Pilih File Upload 2
                <input accept=".pdf" style="display: none;" type="file" name="fileToUpload2" id="fileToUpload2">
                </label>
                
                <br>                
                <button class="btn btn-info center-block submit-me" type="submit" value="Upload File" name="submit">
                <span class="glyphicon glyphicon-upload"></span>
                Upload File
                </button>
                <br>
                <span class="error has-error"><?php echo $this->session->flashdata('Message'); ?></span>
                </form>
            </div>
        </div>
    </div>
    <script>
    $("form").submit(function(e) {
        //e.preventDefault();
        var progress = 0;
        function a() {
            setTimeout(function() {
                if (progress < 100) {
                    $(".progress-loading").show();
                    progress += 1/10;
                    $(".loading-titik-titik").html(".".repeat((progress%10)+1))
                    $(".progress-loading").find(".progress-bar").css("width", progress+"%")
                    a();
                }
            }, 1);
        }
        a();
    })    
    </script>