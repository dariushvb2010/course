<?php

/**
 * jFramework File and Download Manager
 * 
 * @author AbiusX
 * @version 1.5
 */
class jFileManager
{
    /**
     * Returns MIME type for different extensions
     *
     * @param String $File filename
     * @return String MIME-Type
     */
    function MIME ($File)
    {
    	$a=explode('.', $File);
        $extension = array_pop($a);
        $ex=strtolower($extension);
        if ($ex=='htm') return'text/html';
        elseif ($ex=='html') return'text/html';
        elseif ($ex=='txt') return'text/plain';
        elseif ($ex=='asc') return'text/plain';
        elseif ($ex=='bmp') return'image/bmp';
        elseif ($ex=='gif') return'image/gif';
        elseif ($ex=='jpeg') return'image/jpeg';
        elseif ($ex=='jpg') return'image/jpeg';
        elseif ($ex=='jpe') return'image/jpeg';
        elseif ($ex=='png') return'image/png';
        elseif ($ex=='ico') return'image/vnd.microsoft.icon';
        elseif ($ex=='mpeg') return'video/mpeg';
        elseif ($ex=='mpg') return'video/mpeg';
        elseif ($ex=='mpe') return'video/mpeg';
        elseif ($ex=='qt') return'video/quicktime';
        elseif ($ex=='mov') return'video/quicktime';
        elseif ($ex=='avi') return'video/x-msvideo';
        elseif ($ex=='wmv') return'video/x-ms-wmv';
        elseif ($ex=='mp2') return'audio/mpeg';
        elseif ($ex=='mp3') return'audio/mpeg';
        elseif ($ex=='rm') return'audio/x-pn-realaudio';
        elseif ($ex=='ram') return'audio/x-pn-realaudio';
        elseif ($ex=='rpm') return'audio/x-pn-realaudio-plugin';
        elseif ($ex=='ra') return'audio/x-realaudio';
        elseif ($ex=='wav') return'audio/x-wav';
        elseif ($ex=='css') return'text/css';
        elseif ($ex=='zip') return'application/zip';
        elseif ($ex=='pdf') return'application/pdf';
        elseif ($ex=='doc') return'application/msword';
        elseif ($ex=='bin') return'application/octet-stream';
        elseif ($ex=='exe') return'application/octet-stream';
        elseif ($ex=='class') return'application/octet-stream';
        elseif ($ex=='dll') return'application/octet-stream';
        elseif ($ex=='xls') return'application/vnd.ms-excel';
        elseif ($ex=='ppt') return'application/vnd.ms-powerpoint';
        elseif ($ex=='wbxml') return'application/vnd.wap.wbxml';
        elseif ($ex=='wmlc') return'application/vnd.wap.wmlc';
        elseif ($ex=='wmlsc') return'application/vnd.wap.wmlscriptc';
        elseif ($ex=='dvi') return'application/x-dvi';
        elseif ($ex=='spl') return'application/x-futuresplash';
        elseif ($ex=='gtar') return'application/x-gtar';
        elseif ($ex=='gzip') return'application/x-gzip';
        elseif ($ex=='js') return'text/javascript';
        elseif ($ex=='swf') return'application/x-shockwave-flash';
        elseif ($ex=='tar') return'application/x-tar';
        elseif ($ex=='xhtml') return'application/xhtml+xml';
        elseif ($ex=='au') return'audio/basic';
        elseif ($ex=='snd') return'audio/basic';
        elseif ($ex=='midi') return'audio/midi';
        elseif ($ex=='mid') return'audio/midi';
        elseif ($ex=='m3u') return'audio/x-mpegurl';
        elseif ($ex=='tiff') return'image/tiff';
        elseif ($ex=='tif') return'image/tiff';
        elseif ($ex=='rtf') return'text/rtf';
        elseif ($ex=='wml') return'text/vnd.wap.wml';
        elseif ($ex=='wmls') return'text/vnd.wap.wmlscript';
        elseif ($ex=='xsl') return'text/xml';
        elseif ($ex=='xml') return'text/xml';
        else return 'application/octet-stream'; //Download if not known
    }
    function CheckModified($File,$SendHeader=true)
    {
            //Cache-Control
    	$cond = isset($_SERVER['http_if_modified_since']) ? $_SERVER['http_if_modified_since'] : getenv("http_if_modified_since");
        $if_modified_since = preg_replace('/;.*$/', '', $cond);
        $mtime = filemtime($File);
        $gmdate_mod = gmdate('D, d M Y H:i:s', $mtime) . ' GMT';
        if ($if_modified_since == $gmdate_mod)
        {
            if ($SendHeader) header("HTTP/1.0 304 Not Modified");
            return true;
        }
        if ($SendHeader)
        {
            header("Last-Modified: $gmdate_mod"); //Set the time this resource is modified
        	header("Cache-Control: must-revalidate"); //Browser should ask me for cache
        	
        }
    	
    }
    /**
     * This function feeds a file to the client. By chaning the 
     * FILE_DOWNLOAD_LIMIT option, you can limit download bandwidth automatically.
     *
     * @param String $File
     */
    function Feed ($RealFile,$OutputFile=null)
    {
		if ($OutputFile===null)
			$OutputFile=basename($RealFile);
		$File=$RealFile;
    	//file exists
    	if (! file_exists($File) or ! is_file($File))
            return false;

		if ($this->CheckModified($File)) return true;	
        header("Content-Type: " . $this->MIME($OutputFile));
        //saved filename
//        $DownloadedFilename = array_pop(explode("/", $File));
		if (strpos($OutputFile," ")!==false)
			$OutputFile="'{$OutputFile}'";
        header("Content-Disposition: filename={$OutputFile}"); //add attachment; here to force download
        $FileSize = filesize($File);
        
        //Resumable
        if(isset($_SERVER['HTTP_RANGE']))
        {
            list($size_unit, $range_orig) = explode('=', $_SERVER['HTTP_RANGE'], 2);
    
            if ($size_unit == 'bytes')
            {
                //multiple ranges could be specified at the same time, but for simplicity only serve the first range
                //http://tools.ietf.org/id/draft-ietf-http-range-retrieval-00.txt
                list($range, $extra_ranges) = explode(',', $range_orig, 2);
            }
            else
            {
                $range = '';
            }
        }
        else
        {
            $range = '';
        }
    
        //figure out download piece from range (if set)
        $a=explode('-', $range);
        if (isset($a[0]))
        	$seek_start=$a[0];
        if (isset($a[1]))
        	$seek_end=$a[1];
    
        //set start and end based on range (if set), else set defaults
        //also check for invalid ranges.
        $seek_end = (empty($seek_end)) ? ($FileSize - 1) : min(abs(intval($seek_end)),($FileSize - 1));
        $seek_start = (empty($seek_start) || $seek_end < abs(intval($seek_start))) ? 0 : max(abs(intval($seek_start)),0);
        //add headers if resumable
        $Resumable=false;
        if ($seek_start > 0 || $seek_end < ($FileSize - 1))
        {
            $Resumable=true;
            header('HTTP/1.1 206 Partial Content');
        }
    
        header('Accept-Ranges: bytes');
        header('Content-Range: bytes '.$seek_start.'-'.$seek_end.'/'.$FileSize);
    
        header('Content-Length: '.($seek_end - $seek_start + 1));
        //Apply Download Limit
        if ((reg("jf/fileman/BandwidthLimit") && $FileSize > reg("jf/fileman/BandwidthLimitOn")))
        {
            $f = fopen($File, "rb");
            fseek($f, $seek_start);
            set_time_limit(0);
            while (! feof($f))
            {
                echo fread($f, reg("jf/fileman/BandwidthLimit"));
                flush();
                ob_flush();
                sleep(1);
            }
            fclose($f);
            return true;
        }
        else //No download limit 
        {
            if ($Resumable)
            {
                $f=fopen($File,"rb");
                fseek($f, $seek_start);
                set_time_limit(0);
                while (! feof($f))
                {
                    echo fread($f, 1024*8);
                    flush();
                    ob_flush();
                }
                fclose($f);
            }
            else readfile($File);
            return true;
        }
    }
    function FeedData($Data,$OutputFilename)
    {
    	$Filename=$OutputFilename;
        header("Content-Type: " . $this->MIME($Filename));
        header('Content-Disposition: attachment; filename=' . $Filename); //add attachment; here to force download
        header('Content-Length: '.strlen($Data));
        
		echo $Data;
        return true;
    	
    }
}
?>