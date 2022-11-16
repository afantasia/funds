<?php

namespace App\Http\Controllers;

use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\PdfToImage\Pdf;

class UtilController extends Controller
{
    //
    public function Index(Request $request)
    {
        $params=[];
        return view("util.main",$params);
    }
    
    public function Upload(Request $request)
    {
        $file=$request->file('pdfFile');
        
        $svFileName=$file->getFilename();
        $dnFileName=str_replace(".".$file->getClientOriginalExtension(),"",$file->getClientOriginalName());
        $pdfFile=Storage::disk('local')->putFileAs("util/pdf/".date("Ymd"),$file,date("His")."_".$svFileName.".".$file->getClientOriginalExtension());
        $pdfFilePath=storage_path("app/").$pdfFile;
        Storage::disk('local')->makeDirectory('util/pdf/'.date("Ymd")."/".$svFileName);
        $imgDir=Storage::disk('local')->path('util/pdf/'.date("Ymd")."/".$svFileName);
        $pdf=new Pdf($pdfFilePath);
        foreach (range(1, $pdf->getNumberOfPages()) as $pageNumber) {
            $pdf->setPage($pageNumber)->setCompressionQuality($request->quality)->saveImage($imgDir."/page".sprintf("%03d",$pageNumber).'.jpg',);
        }
        $imgLists=Storage::disk('local')->allFiles('util/pdf/'.date("Ymd")."/".$svFileName);
    
        if($request->fileType=='zip'){
            $zip=new \ZipArchive();
            $downFileLoc=storage_path("app/".'util/pdf/'.date("Ymd")."/".$svFileName).".zip";
            if ($zip->open($downFileLoc, \ZipArchive::CREATE)== TRUE){
                foreach($imgLists as $imgList) {
                    $relativeName = basename($imgList);
                    $zip->addFile(storage_path("app/").$imgList,$relativeName);
                }
            }
            $zip->close();
        }else if($request->fileType=='pdf'){
            $dnFileName="(변환)".$dnFileName;
            $downFileLoc=storage_path("app/".'util/pdf/'.date("Ymd")."/NEW_".$svFileName).".pdf";
            $imgLists=Storage::disk('local')->allFiles('util/pdf/'.date("Ymd")."/".$svFileName);
            foreach($imgLists as $k=> $imgList){
                $imgLists[$k]=storage_path("app/").$imgList;
            }
            $im = new \Imagick($imgLists);
            $im->writeImages($downFileLoc, true);
        }
        return response()->download($downFileLoc,$dnFileName.".".$request->fileType)->deleteFileAfterSend(true);
    }
    
}
