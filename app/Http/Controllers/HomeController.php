<?php

namespace App\Http\Controllers;

use File;
use ZipArchive;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    const API_FOLDER = 'api';
    const ENGINE_FOLDER = 'engine';
    const DELAY_TIME_IN_SECOND = 5;
    const TDS = DIRECTORY_SEPARATOR;

    public $jumlahDataDisimpan;

    public function basic_response()
    {
        return [
            'status' => 200,
            'message' => '',
            'results' => []
        ];
    }

    public $response = [];

    public function __construct()
    {
        $this->response = $this->basic_response();
    }

    public function cetakSemuaPdf()
    {
        $cetakUser = new UserController();
        $cetakUser->exportPdf(true);
        $response = $cetakUser->response;
        $namaFileUser = $response['results'];

        $cetakBuku = new BookController();
        $cetakBuku->exportPdf(true);
        $response = $cetakBuku->response;
        $namaFileBuku = $response['results'];

        $cetakPetugas = new PetugasController();
        $cetakPetugas->exportPdf(true);
        $response = $cetakPetugas->response;
        $namaFilePetugas = $response['results'];

        $pdf = new \Jurosh\PDFMerge\PDFMerger;
        $pdf->addPDF('D:\Project\Belajar\export-data\public/' . $namaFileUser, 'all', 'horizontal')
            ->addPDF('D:\Project\Belajar\export-data\public/' . $namaFileBuku, 'all', 'horizontal')
            ->addPDF('D:\Project\Belajar\export-data\public/' . $namaFilePetugas, 'all', 'horizontal');

        $pdf->merge('file', 'D:\Project\Belajar\export-data\public\HasilPdf/mergePdf.pdf');
    }

    public function createZip()
    {
        $zip = new ZipArchive;
        if ($zip->open('test_new.zip', ZipArchive::CREATE) === TRUE) {

            $zip->addFile('daftar_buku.pdf');
            $zip->addFile('daftar_petugas.pdf');
            $zip->addFile('daftar_user.pdf');

            $zip->addFromString('info.txt', 'ini adalah testing untuk buat file berformat zip cuy');

            $zip->close();
        }
    }

    public function runEngine(Request $request)
    {
        $keyword = $request['keyword'] ?? "";
        if (strlen($keyword) < 1) {
            $this->response['status'] = 500;
            $this->response['message'] = "Harap masukkan keyword terlebih dahulu";
            return response()->json($this->response);
        }

        $filePath = base_path() . self::TDS . self::ENGINE_FOLDER . self::TDS . 'detik.com.py';
        if (!file_exists($filePath)) {
            $this->response['status'] = 500;
            $this->response['message'] = "File tidak ditemukan";
            return response()->json($this->response);
        }
        Log::info(env('PYTHON_RUN_USING', 'python') . " $filePath $keyword");
        $result = shell_exec(
            escapeshellcmd(
                env('PYTHON_RUN_USING', 'python') . " $filePath $keyword"
            )
        );
        $this->jumlahDataDisimpan += substr_count($result, 'berhasil disimpan');
        sleep(self::DELAY_TIME_IN_SECOND);

        $this->response['status'] = 200;
        $this->response['message'] = "Data berhasil disimpan";
        return response()->json($this->response);
    }
}
