<?php
    class Image{
        private $image = '';

        public function __construct($file){
            // Ouvrir et stocker en mémoire le contenu du fichier image
            $data_file = getimagesize($file);
            switch($data_file['mime']){
                case "image/jpeg":
                    $this->image = imagecreatefromjpeg($file);
                    break;
                case "image/png":
                    $this->image = imagecreatefrompng($file);
                    break;
                case "image/gif":
                    $this->image = imagecreatefromgif($file);
                    break;
                default:
                    $this->errorHandler("Format non valide");
                    break;
            }
        }

        public function store($file_dest){
            $tab_name = explode('.',$file_dest);
            $extension = strtolower($tab_name[count($tab_name)-1]);

            switch($extension){
                case 'jpg':
                    imagejpeg($this->image,$file_dest,80);
                    break;
                case 'png':
                    imagepng($this->image,$file_dest);
                    break;
                case 'gif':
                    imagegif($this->image,$file_dest);
                    break;
            }
        }

        public function resizeByMax($max){
            $o_wd = imagesx($this->image);
            $o_ht = imagesy($this->image);

            if($o_wd > $o_ht){
                // Image horizontale
                $new_width = $max;
                $new_height = ($max * $o_ht) / $o_wd;
            }else{
                // Image verticale
                $new_height = $max;
                $new_width = ($max * $o_wd) / $o_ht;
            }

            $tmp = imageCreateTrueColor($new_width,$new_height);
            imageCopyResampled($tmp, $this->image, 0, 0, 0, 0, $new_width, $new_height, $o_wd, $o_ht);
            $this->image = $tmp;
            unset($tmp);
        }

        public function resizeByMin($max){
            $o_wd = imagesx($this->image);
            $o_ht = imagesy($this->image);
            if($o_wd > $o_ht){
                // Image horizontale
                $new_height = $max;
                $new_width = ($max * $o_wd) / $o_ht;
            }else{
                // Image verticale
                $new_width = $max;
                $new_height = ($max * $o_ht) / $o_wd;
            }

            $tmp = imageCreateTrueColor($new_width,$new_height);
            imageCopyResampled($tmp, $this->image, 0, 0, 0, 0, $new_width, $new_height, $o_wd, $o_ht);
            $this->image = $tmp;
            unset($tmp);
        }

        public function resizeByFactor($factor = 64){
            $o_wd = imagesx($this->image);
            $o_ht = imagesy($this->image);
            $new_wd = round($o_wd / $factor);
            $new_ht = round($o_ht / $factor);

            $tmp = imageCreateTrueColor($new_wd, $new_ht);
            imageCopyResampled($tmp, $this->image, 0, 0, 0, 0, $new_wd, $new_ht, $o_wd, $o_ht);
            $this->image = $tmp;
        }

        public function cropSquare(){
            // Pour les plus rapide : Rendre une image carrée
            $o_wd = imagesx($this->image);
            $o_ht = imagesy($this->image);
            if($o_wd>$o_ht){
                // Image Horizontale
                $offset = round(($o_wd - $o_ht) / 2);
                $tmp = imageCreateTrueColor($o_ht,$o_ht);
                imageCopyResampled($tmp, $this->image, 0, 0, $offset, 0, $o_ht, $o_ht, $o_ht, $o_ht);
            }else{
                // Image Verticale
                $offset = round(($o_ht - $o_wd) / 2);
                $tmp = imageCreateTrueColor($o_wd,$o_wd);
                imageCopyResampled($tmp, $this->image, 0, 0, 0, $offset, $o_wd, $o_wd, $o_wd, $o_wd);
            }
            $this->image = $tmp;
            unset($tmp);
        }

        public function average() {
            $w = imagesx($this->image);
            $h = imagesy($this->image);
            $r = $g = $b = 0;
            for($y = 0; $y < $h; $y++) {
                for($x = 0; $x < $w; $x++) {
                    $rgb = imagecolorat($this->image, $x, $y);
                    $r += $rgb >> 16;
                    $g += $rgb >> 8 & 255;
                    $b += $rgb & 255;
                }
            }
            $pxls = $w * $h;
            $r = dechex(round($r / $pxls));
            $g = dechex(round($g / $pxls));
            $b = dechex(round($b / $pxls));
            if(strlen($r) < 2) {
                $r = 0 . $r;
            }
            if(strlen($g) < 2) {
                $g = 0 . $g;
            }
            if(strlen($b) < 2) {
                $b = 0 . $b;
            }
            return $r . $g . $b;
        }

        public function getSizeX(){
            return imagesx($this->image);
        }


        public function getSizeY(){
            return imagesy($this->image);
        }

        public function errorHandler($mess=''){
            echo "error Handler - ".$mess;
            exit();
        }
    }

?>