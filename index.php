<?php
$name = '';
$type = '';
$size = '';
$error = '';

function compress_image($sourceURL, $destinationURL, $quality)
{

    $info = getimagesize($sourceURL);

    if ($info['mime'] == 'image/jpeg')
        $image = imagecreatefromjpeg($sourceURL);

    elseif ($info['mime'] == 'image/gif')
        $image = imagecreatefromgif($sourceURL);

    elseif ($info['mime'] == 'image/png')
        $image = imagecreatefrompng($sourceURL);

    /**
     * at present compressed image will be in JPEG format only. I'm working on other formats too, will update here once it's done.
     */
    imagejpeg($image, $destinationURL, $quality);
    return $destinationURL;
}
if ($_POST) {

    if ($_FILES["file"]["error"] > 0) {
        $error = $_FILES["file"]["error"];
    } else if (($_FILES["file"]["type"] == "image/gif") ||
        ($_FILES["file"]["type"] == "image/jpeg") ||
        ($_FILES["file"]["type"] == "image/png") ||
        ($_FILES["file"]["type"] == "image/pjpeg")) {

        /**
         * assign output file name with output path here..
         * By default, it will be downloaded into downloads(or browser specific path). 
         * @var string
         */
        $url = 'destination .jpg';

        /**
         * compression quality can be changeable...
         * 80 is used here for best quality..can be still reduced to reduce file size but quality won't be good.
         *
         * @var integer
         */
        $compressionquality = 80;

        $filename = compress_image($_FILES["file"]["tmp_name"], $url, $compressionquality);
        $image = file_get_contents($url);

        /* Force download dialog... */
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");

        /* Don't allow caching... */
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");

        /* Set data type, size and filename */
        header("Content-Type: application/octet-stream");
        header("Content-Transfer-Encoding: binary");
        header("Content-Length: " . strlen($image));
        header("Content-Disposition: attachment; filename=$url");


        /* Send our file... */
        echo $image;
    } else {
        $error = "Uploaded image should be jpg or gif or png";
    }
}

?>
<html>
    <head>
        <title>Image Compressor By NakeeranR</title>
    </head>
    <body>

        <div class="message">
            <?php
            if ($_POST) {
                if ($error) {

                    ?>
                    <label class="error"><?php echo $error; ?></label>
                    <?php
                }
            }

            ?>
        </div>
        <fieldset class="well">
            <legend>Upload Image:</legend>                
            <form action="" name="myform" id="myform" method="post" enctype="multipart/form-data">
                <ul>
                    <li>
                        <input type="file" name="file" id="file"/>
                    </li>
                    <br/>
                    <li>
                        <input type="submit" name="submit" id="submit" class="submit btn-success"/>
                    </li>
                </ul>
            </form>
        </fieldset>
    </body>
</html>