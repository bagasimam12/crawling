<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class UserController extends Controller
{
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

    public function index()
    {
        try {
            $this->response['results'] = User::all();
        } catch (Exception $th) {
            $this->response['status'] = 500;
            $this->response['message'] = $th->getMessage();
        }
        return response()->json($this->response);
    }

    public function exportPdf($isCombinePdf = false)
    {
        $data = User::all();

        $pdf = PDF::loadView('UserPdf', compact('data'));
        $pdf->setPaper('a4', 'landscape');
        $fileName = 'daftar_user.pdf';

        if ($isCombinePdf == true) {
            $pdf->render();

            $output = $pdf->output();
            file_put_contents($fileName, $output);
            $this->response['results'] = $fileName;
            return $fileName;
        } else {
            return $pdf->download($fileName);
        }
    }
}
