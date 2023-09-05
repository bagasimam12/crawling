<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;

class EngineController extends Controller
{
    const API_FOLDER = 'api';
    const ENGINE_FOLDER = 'engine';
    const DELAY_TIME_IN_SECOND = 5;

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
}
