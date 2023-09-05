<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use Exception;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class BookController extends Controller
{
    private $validation_rules = [
        'name' => 'required|max:255',
        'price' => 'required|numeric',
        'status' => 'required',
        'desc' => 'required|max:255',
    ];

    public function basic_response()
    {
        return [
            'status' => 200,
            'message' => '',
            'results' => []
        ];
    }

    public $response = [];

    const STATUS_VALID = [
        'new', 'second'
    ];

    public function __construct()
    {
        $this->response = $this->basic_response();
    }

    public function index()
    {
        try {
            $this->response['results'] = Book::all();
        } catch (Exception $th) {
            $this->response['status'] = 500;
            $this->response['message'] = $th->getMessage();
        }
        return response()->json($this->response);
    }

    public function show($id)
    {
        $validator = $this->getValidationFactory()->make(['id' => $id], ['id' => 'required|numeric']);
        if ($validator->fails()) {
            $this->response['status'] = 422;
            $this->response['message'] = $validator->errors();
        } else {
            try {
                $this->response['results'] = Book::find($id);
            } catch (Exception $th) {
                $this->response['status'] = 500;
                $this->response['message'] = $th->getMessage();
            }
        }
        return response()->json($this->response);
    }

    public function create(Request $request)
    {
        $validator = $this->getValidationFactory()->make($request->post(), $this->validation_rules);
        if ($validator->fails()) {
            $this->response['status'] = 422;
            $this->response['message'] = $validator->errors();
        } else {
            $status = $request->status;
            $isUbah = strlen($request->id) > 0;

            if (!in_array($status, self::STATUS_VALID)) {
                $this->response['status'] = 422;
                $this->response['message'] = "status yang dipilih tidak valid";
            } else {
                try {
                    $data = $isUbah ? Book::find($request->id) : new Book();
                    $data->name = $request->name;
                    $data->price = $request->price;
                    $data->desc = $request->desc;
                    $data->status = $status;
                    if ($data->save()) {
                        $this->response['message'] = 'book data saved successfully!';
                    } else {
                        $this->response['status'] = 500;
                        $this->response['message'] = 'book data failed to save!';
                    }
                } catch (Exception $th) {
                    $this->response['status'] = 500;
                    $this->response['message'] = $th->getMessage();
                }
            }
        }
        return response()->json($this->response);
    }

    public function destroy($id)
    {
        $validator = $this->getValidationFactory()->make(['id' => $id], ['id' => 'required|numeric']);
        if ($validator->fails()) {
            $this->response['status'] = 422;
            $this->response['message'] = $validator->errors();
        } else {
            try {
                $book = Book::find($id);
                if (!$book) {
                    return response()->json(['message' => 'Data not found'], 404);
                }

                if ($book->delete()) {
                    $this->response['message'] = 'book data removed successfully!';
                } else {
                    $this->response['status'] = 500;
                    $this->response['message'] = 'book data failed to remove!';
                }
                $this->response['message'] = 'book data removed successfully!';
            } catch (Exception $th) {
                $this->response['status'] = 500;
                $this->response['message'] = $th->getMessage();
            }
        }

        return response()->json($this->response);
    }

    public function exportExcel()
    {
        $data = Book::all();

        $namaFile = 'daftar_buku_' . date('Y-m-d') . '.xlsx';
        $spreadSheet = new Spreadsheet();
        $spreadSheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(10);
        $spreadSheet->getActiveSheet()->getStyle("A3:E3")->getFont()->setBold(true);
        $sheet = $spreadSheet->getActiveSheet();
        // set header
        $sheet->setCellValue('A1', "Daftar Buku");
        $mulaiBarisKe = 4;
        $barisSekarang = $mulaiBarisKe;
        $sheet->setTitle('cetak_daftar_buku');
        if (count($data) > 0) {
            $sheet->setCellValue('A3', "No.");
            $sheet->setCellValue('B3', "Nama");
            $sheet->setCellValue('C3', "Harga");
            $sheet->setCellValue('D3', "Deskripsi");
            $sheet->setCellValue('E3', "Status");

            foreach ($data as $key => $value) {
                $dataLog = [
                    'no_urut' => $key + 1,
                    'name' => $value->name,
                    'price' => $value->price,
                    'desc' => $value->desc,
                    'status' => $value->status,
                ];
                $sheet->setCellValue('A' . $barisSekarang, (string) $dataLog["no_urut"] ?? "-");
                $sheet->setCellValue('B' . $barisSekarang, $dataLog["name"] ?? "-");
                $sheet->setCellValue('C' . $barisSekarang, $dataLog["price"] ?? "-");
                $sheet->setCellValue('D' . $barisSekarang, $dataLog["desc"] ?? "-");
                $sheet->setCellValue('E' . $barisSekarang, $dataLog["status"] ?? "-");
                $barisSekarang++;
            }
        }

        $styleArray = array(
            'borders' => array(
                'outline' => array(
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => array('argb' => '00000000'),
                ),
                'inside' => array(
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => array('argb' => '00000000'),
                ),
            ),
        );
        $sheet->mergeCells('A1:E1');
        $sheet->getStyle("A1:E1")->applyFromArray([
            'alignment' => array(
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ),
            'font' => [
                'size' => 16
            ],
            'borders' => array(
                'bottom' => array(
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DASHED,
                    'color' => array('argb' => '00000000'),
                ),
            ),
        ]);
        $spreadSheet->getActiveSheet()->getRowDimension('1')->setRowHeight(30);
        $spreadSheet->getActiveSheet()->getStyle("A1:G1")->getFont()->setBold(true);
        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(30);
        $sheet->getColumnDimension('C')->setWidth(17);
        $sheet->getColumnDimension('D')->setWidth(50);
        $sheet->getColumnDimension('E')->setWidth(17);
        $sheet->getStyle("A3:E3")->applyFromArray($styleArray);
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers:  Content-Type, X-Auth-Token, Authorization, Origin');
        header('Access-Control-Allow-Methods:  POST, PUT');
        header('Content-Type: application/openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $namaFile . '"');
        $writer = new Xlsx($spreadSheet);
        $writer->save('php://output');
    }

    public function exportPdf($isCombinePdf = false)
    {
        $data = Book::all();

        $pdf = PDF::loadView('LoadPdf', compact('data'));
        $pdf->setPaper('a4', 'landscape');
        $fileName = 'daftar_buku.pdf';

        if ($isCombinePdf == true) {
            $pdf->render();

            $output = $pdf->output();
            file_put_contents($fileName, $output);
            $this->response['results'] = $fileName;
            return response()->json($this->response);
        } else {
            return $pdf->download($fileName);
        }
    }
}
