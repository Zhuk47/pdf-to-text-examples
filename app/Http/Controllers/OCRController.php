<?php

namespace App\Http\Controllers;

use GoogleCloudVision\GoogleCloudVision;
use GoogleCloudVision\Request\AnnotateImageRequest;
use GuzzleHttp\Client;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\PdfToImage\Exceptions\PdfDoesNotExist;
use Spatie\PdfToImage\Pdf;
use Spatie\PdfToText\Exceptions\PdfNotFound;
use Illuminate\Support\Facades\Storage;
use thiagoalessio\TesseractOCR\TesseractOCR;
use thiagoalessio\TesseractOCR\TesseractOcrException;

class OCRController extends Controller
{
    protected $googleApiKey;

    protected $ocrSpacesKey;

    public function __construct()
    {
        $this->googleApiKey = 'Your Google API key';
        $this->ocrSpacesKey = 'Your OCR Spaces API key';
    }

    /**
     * @return Factory|View
     */
    public function index(): View
    {
        return view('test');
    }

    /**
     * @param Request $request
     * @return bool|Factory|View
     */
    public function store(Request $request)
    {
        // store pdf
        $originalName = $request->file->getClientOriginalName();
        Storage::putFileAs("tmp/", $request->file, $originalName);
        $url = Storage::path("tmp/$originalName");

        // convert to image
        try {
            $pdf = new Pdf($url);
        } catch (PdfDoesNotExist $e) {
            report($e);

            return false;
        }
        $imagePath = storage_path() . '/app/tmp/' . str_replace('.pdf', '.png', $originalName);
        $pdf->saveImage($imagePath);

        switch ($request->submit) {
            case 'tesseract':

                // Tesseract OCR
                $ocr = new TesseractOCR();
                $ocr->image($imagePath);
                try {
                    $res = $ocr->run();
                } catch (TesseractOcrException $e) {
                    report($e);

                    return false;
                }

                break;
            case 'pdf-to-text':

                // Spatie pdf-to-text
                try {
                    $res = (new \Spatie\PdfToText\Pdf())
                        ->setPdf($url)
                        ->text();
                } catch (PdfNotFound $e) {
                    report($e);

                    return false;
                }

                break;
            case 'api-ocr-space':

                // api.ocr.space
                $fileData = fopen($url, 'r');
                $client = new Client();
                $jsonResult = $client->request('POST', 'https://api.ocr.space/parse/image', [
                    'headers' => ['apiKey' => '' . $this->ocrSpacesKey . ''],
                    'multipart' => [
                        [
                            'name' => 'file',
                            'contents' => $fileData
                        ]
                    ]
                ]);
                $response = json_decode($jsonResult->getBody(), true);
                if (isset($response['ErrorMessage'][0])) {
                    $res = '---Error: ' . $response['ErrorMessage'][0];
                } elseif (isset($response['ParsedResults'])) {
                    $res = '';
                    foreach ($response['ParsedResults'] as $parseValue) {
                        $res .= $parseValue['ParsedText'];
                    }
                }

                break;
            case 'google-cloud-vision':

                // Google Cloud Vision API
                $image = file_get_contents($imagePath);
                $base64image = base64_encode($image);
                //prepare request
                $request = new AnnotateImageRequest();
                $request->setImage($base64image);
                $request->setFeature("DOCUMENT_TEXT_DETECTION");
                $gcvRequest = new GoogleCloudVision([$request], '' . $this->googleApiKey . '');
                //send annotation request
                $response = $gcvRequest->annotate();
                $res = $response->responses[0]->fullTextAnnotation->text;

                break;
            default:

                $res = '---Error---';
                break;
        }

        // delete temporary files
        $files = Storage::allFiles('/tmp');
        Storage::delete($files);

        return view('test-result', compact('res'));
    }
}
