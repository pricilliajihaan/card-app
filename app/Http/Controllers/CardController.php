<?php

namespace App\Http\Controllers;

use GDText\Box;
use GDText\Color;
use App\Models\Card;
use App\Http\Controllers\Controller;
Use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Imports\CardsImport;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class CardController extends Controller
{
    public function index()
    {
        return view('card.index');
    }
    public function register()
    {
        return view('card.register');
    }
    public function registerProcess(Request $request)
    {
        $request->validate([
            'name' =>'required',
            'email'=> 'required',
            'phone_number'=> 'required',
            'start_date'=> 'required',
            'gender'=>'required',
        ]);

        // Hitung tahun kerja
        $startDate = Carbon::parse($request->start_date);
        $yearsOfService = $startDate->diffInYears(Carbon::now());

        Card::create([
            'name' => $request->name,
            'email'=> $request->email,
            'phone_number'=> $request->phone_number,
            'start_date' => $request->start_date,
            'years_of_service' => $yearsOfService,
            'gender' => $request->gender,
        ]);
        return back()->with('success','Berhasil Mendaftar');

    }
    public function member()
    {
        return view('card.member', [
            'members' => Card::latest()->get()
        ]);
    }

    public function importData(Request $request)
    {
        $request->validate([
            'my_file' => 'required|file|mimes:xls,xlsx,csv',
            'fungsi' => 'required|integer',
        ]);

        if($request->fungsi == 1) {
            // fungsi 1
            Card::truncate();
            Excel::import(new CardsImport, $request->my_file);
            return back()->with('success','import berhasil & data sebelumnya berhasil dihapus');
        } else if($request->fungsi == 2) {
            //fungsi 2
            Excel::import(new CardsImport, $request->my_file);
            return back()->with('success','import berhasil ditambahkan');
        }else{
            abort(404);
        } 

        // if($request->fungsi == 1) {
        //     // fungsi 1
        //     Card::truncate();
        // }
        // Excel::import(new CardsImport, $request->my_file);
        // return back()->with('success','import berhasil');
        // } 
    }

    public function memberTruncate()
    {
        $truncate = Card::truncate();
        if($truncate){
            return back()->with('success','Truncate berhasil');
        }else{
            abort(404);
        }
    }

    public function getEcard(Request $request)
    {
        $user = Card::find($request->user_id);
        $name = $user->name;
        $yearsOfService = $user->years_of_service;

        // Function to determine the correct suffix
        function getNumberSuffix($number) {
            if (!in_array(($number % 100), array(11, 12, 13))) {
                switch ($number % 10) {
                    case 1: return "st";
                    case 2: return "nd";
                    case 3: return "rd";
                }
            }
            return "th";
        }

        $suffix = getNumberSuffix($yearsOfService);
        $yearsText = $yearsOfService;

         // Determine salutation based on gender
        $salutation = ($user->gender == 'male') ? 'Mr.' : 'Ms.';
        $fullName = $salutation . ' ' . $name; 

        $im = imagecreatefromjpeg(public_path('card/WorkAnniversary.jpg'));
        
        // Font paths
        $fontFamilyName = public_path('fonts/DistrictPro-Medium.otf');
        $fontFamilyYears = public_path('fonts/DistrictPro-DemiBold_0.otf');
        $fontFamilySuffix = public_path('fonts/DistrictPro-DemiBold_0.otf');


        // Menampilkan nama dengan sapaan
        $boxName = new Box($im);
        $boxName->setFontFace($fontFamilyName);
        $boxName->setFontColor(new Color(72, 76, 84));
        $boxName->setFontSize(48);
        $boxName->setBox(
            140,
            358,
            imagesx($im),
            imagesy($im)
        );
        $boxName->setTextAlign('left','top');
        $boxName->draw($fullName);

        // Menampilkan tahun kerja tanpa suffix
        $yearsText = $user->years_of_service;
        $boxYears = new Box($im);
        $boxYears->setFontFace($fontFamilyYears);
        $boxYears->setFontColor(new Color(36, 162, 137));
        $boxYears->setFontSize(70);
        $boxYears->setBox(
            277,
            126,
            imagesx($im),
            imagesy($im)
        );
        $boxYears->setTextAlign('left','top');
        $boxYears->draw($yearsText);

        // Calculate the new position for the suffix
        $yearsLength = strlen((string)$yearsText);
        $suffixPositionX = 282 + ($yearsLength * 40);

        // Menampilkan superscript suffix
        $boxSuffix = new Box($im);
        $boxSuffix->setFontFace($fontFamilySuffix);
        $boxSuffix->setFontColor(new Color(36, 162, 137));
        $boxSuffix->setFontSize(40); // Smaller font size for superscript effect
        $boxSuffix->setBox(
            $suffixPositionX, // Adjust horizontally based on the length of the years text
            130, // Adjust vertically to be higher (superscript effect)
            imagesx($im),
            imagesy($im)
        );
        $boxSuffix->setTextAlign('left', 'top');
        $boxSuffix->draw($suffix);

        header("content-type: image/jpeg");
        imagejpeg($im);
    }
    
    public function search(Request $request)
    {
        $query = $request->input('search');

        // Query untuk pencarian nama karyawan
        $members = Card::where('name', 'LIKE', "%{$query}%")->get();

        // Simpan query pencarian ke session flash
        session()->flash('search', $query);

        // Kembalikan view dengan hasil pencarian
        return view('card.member', ['members' => $members]);
    }

    public function edit($id)
    {
        $member = Card::find($id);
        return view('card.edit', compact('member'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' =>'required',
            'email'=> 'required',
            'phone_number'=> 'required',
            'start_date'=> 'required',
            'gender'=>'required',
        ]);

        $member = Card::find($id);
        if ($member) {
            $member->name = $request->name;
            $member->email = $request->email;
            $member->phone_number = $request->phone_number;
            $member->start_date = $request->start_date;
            $member->gender = $request->gender;

            // Hitung ulang years_of_service berdasarkan start_date yang baru
            $startDate = Carbon::parse($request->start_date);
            $currentDate = Carbon::now();
            $yearsOfService = $currentDate->diffInYears($startDate);

            $member->years_of_service = $yearsOfService;
            $member->save();

            return redirect()->route('card.member')->with('succes', 'Data updated succesfully');
        } else {
            return redirect()->route('card.member')->with('error', 'Karyawan not found');
        }
    }

    public function destroy($id)
    {
        $member = Card::find($id);
        $member->delete();

        return back()->with('success', 'Data deleted successfully');
    }

    public function downloadTemplate()
    {
        // Create a new Spreadsheet object
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Add headers to the first row
        $sheet->setCellValue('A1', 'Nama Lengkap');
        $sheet->setCellValue('B1', 'Email');
        $sheet->setCellValue('C1', 'Nomor Hp / Whatsapp');
        $sheet->setCellValue('D1', 'Jenis Kelamin');
        $sheet->setCellValue('E1', 'Tahun Masuk');

        // Set the headers to bold
        $sheet->getStyle('A1:E1')->getFont()->setBold(true);

        // Set "Nomor Hp / Whatsapp" column to text format
        $sheet->getStyle('C2:C1000')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);

        // Data validation for 'Jenis Kelamin'
        $genderValidation = $sheet->getCell('D2')->getDataValidation();
        $genderValidation->setType(DataValidation::TYPE_LIST);
        $genderValidation->setErrorStyle(DataValidation::STYLE_INFORMATION);
        $genderValidation->setAllowBlank(false);
        $genderValidation->setShowInputMessage(true);
        $genderValidation->setShowErrorMessage(true);
        $genderValidation->setShowDropDown(true);
        $genderValidation->setErrorTitle('Input error');
        $genderValidation->setError('Value is not in list.');
        $genderValidation->setPromptTitle('Jenis Kelamin');
        $genderValidation->setPrompt('Please pick a value from the drop-down list.');
        $genderValidation->setFormula1('"male,female"');

        // Apply validation to a range of cells (D2 to D1000 for example)
        for ($i = 2; $i <= 1000; $i++) {
            $sheet->getCell('D' . $i)->setDataValidation(clone $genderValidation);
        }

        // Set the date format for 'Tahun Masuk'
        $sheet->getStyle('E2:E1000')->getNumberFormat()->setFormatCode('DD/MM/YYYY');

        // Create a writer and save the spreadsheet to a temporary file
        $writer = new Xlsx($spreadsheet);
        $fileName = 'import_template.xlsx';
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);
        $writer->save($temp_file);

        // Return the file as a download response
        return response()->download($temp_file, $fileName)->deleteFileAfterSend(true);
    }

}
