<?php
/**
 * author @tasiukwaplong
 */
// Remote download URL andForce download

class FileDownload {
    public $status = ['errored'=>false, 'message'=>''];
    public $url;
    public $fileName = null;
    public $redirectURL;
    public $fileInfo;
    
    public function __construct($url, $redirectURL = '') {
        $this->url = $url; // @TODO: get absolute url regardless if & and ? exist in it
        if(!empty($redirectURL)) $this->redirectURL = $redirectURL;
        $this->checkFileAvailability($url);
    }

    private function checkFileAvailability($url){
        // check if file exists
        $this->fileInfo = @get_headers($url, 1);
        if (!@fopen($url, 'r') || !isset($this->fileInfo['Content-Type'])) {
            return !$this->setStatus(true,
              "Specified URL does not contain a file of file has been deleted: <a href='$url'>$url</a>")['errored'];
        }
        return true;
    }

    public function setFileName($fileName){
        // explicit naming of file
        $this->fileName = $fileName;
    }

    private function getFileHeaderAndName(){
        // get file header
        if($this->status['errored']) return $this->status;
        $fileName = (!is_null($this->fileName)) ? $this->fileName.$this->getFileExtension() : basename($this->url);
        $headers = get_headers($this->url, 1)['Content-Type'];
        return [
            'name'=>$fileName,
            'headers'=>$headers
        ];
    }

    private function getFileExtension(){
        # get extension of file
        if($this->status['errored']) return $this->status;
        $arrayedStr = explode("/",$this->fileInfo['Content-Type']);
        if(gettype($arrayedStr) !== 'array' || count($arrayedStr) !== 2) return '';// extension could not be derived
        return '.'.$arrayedStr[1];
    }

    public function download(){
        // download file
        if($this->status['errored']) return $this->status;
        @header("Content-type: ".$this->getFileHeaderAndName()['headers']);
        @header("Content-Disposition: attachment; filename=".$this->getFileHeaderAndName()['name']);
        @ob_end_clean();
        @readfile($this->url);
        return (isset($this->redirectURL))
        //   ? "<script>window.location = '".urldecode($this->redirectURL)."'</script>"
          ? @header("Location: $this->redirectURL")
          : "If your download did not start, Click here to download <a href='$this->url'>$this->url</a>";
    }

    function setStatus($errored, $message){
        //set status message
        return $this->status = ['errored'=>$errored, 'message'=>$message]; 
    }
  
}
