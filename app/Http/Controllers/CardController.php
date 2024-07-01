<?php

namespace App\Http\Controllers;

use GDText\Box;
use GDText\Color;
use App\Models\Card;
Use App\Models\User;
use App\Http\Controllers\Controller;
Use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Imports\CardsImport;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\FinanceReminderMail;
use App\Mail\WorkAnniversaryMail;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class CardController extends Controller
{
    public function index()
    {
        $totalEmployees = Card::count(); // Get the total number of employees
        $employees = Card::select('id', 'name', 'start_date', 'email_sent')->get(); // Fetch employee data
        return view('card.index', compact('totalEmployees', 'employees'));
    }

    public function add()
    {
        return view('card.add');
    }

    public function profile()
    {
        return view('card.profile');
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'company' => 'nullable|string|max:255',
            'job' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'required|email|max:255',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validasi tipe file gambar
        ]);

        if ($user instanceof User) {
            if ($request->hasFile('profile_image')) {
                // Hapus gambar lama jika ada
                if ($user->profile_image) {
                    Storage::disk('public')->delete($user->profile_image);
                }

                // Simpan gambar baru
                $imagePath = $request->file('profile_image')->store('profile_images', 'public');
                $user->profile_image = $imagePath;
            }

            $user->update([
                'name' => $request->name,
                'company' => $request->company,
                'job' => $request->job,
                'country' => $request->country,
                'address' => $request->address,
                'phone' => $request->phone,
                'email' => $request->email,
            ]);

            return redirect()->route('user.profile')->with('success', 'Profile updated successfully');
        } else {
            return redirect()->route('user.profile')->with('error', 'User not found');
        }
    }

    public function removeProfileImage()
    {
        $user = Auth::user();

        if ($user instanceof User) {
            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
                $user->profile_image = null;
                $user->save();
            }

            return redirect()->route('user.profile')->with('success', 'Profile image removed successfully');
        } else {
            return redirect()->route('user.profile')->with('error', 'User not found');
        }
    }

    public function addProcess(Request $request)
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

        // ob_start();
        // imagejpeg($im);
        // $imageData = ob_get_contents();
        // ob_end_clean();

        // return $imageData;

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

            return redirect()->route('card.member')->with('success', 'Data updated succesfully');
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

    public function sendEmail(Request $request)
    {
        $user = Card::findOrFail($request->user_id);
        if (!$user) {
            return back()->with('error', 'User not found');
        }
        // $ecardContent = $this->getEcard($request);

        // Buat gambar e-card seperti pada metode getEcard
        ob_start();
        $this->getEcard($request);
        $ecardContent = ob_get_clean();

        // Encode e-card content to base64
        $base64 = base64_encode($ecardContent);

        // Kirim email
        try {
            Mail::to($user->email)->send(new WorkAnniversaryMail($user, $base64));
            // Set email_sent menjadi true setelah surel berhasil dikirim
            $user->email_sent = true;
            $user->save();
            
            // unlink($filePath);
            return back()->with('success', 'E-card has been sent successfully to ' . $user->email);
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to send email: ' . $e->getMessage());
        }
    }

    public function sendReminder(Card $user)
    {
        Mail::to('finance@example.com')->send(new FinanceReminderMail($user));

        return redirect()->back()->with('success', 'Reminder email sent successfully!');
    }

}
