<?php

namespace App\Http\Controllers\Admin;

use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Traits\ActivationClass;
use App\Traits\UpdateClass;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Mockery\Exception;
use Ramsey\Uuid\Uuid;
use ZipArchive;
use function App\CPU\translate;

class SoftwareUpdateController extends Controller
{
    use ActivationClass;
    use UpdateClass;

    public function index()
    {
        return view('admin-views.system-settings.software-update');
    }

    public function activate_maintenance_mode(): string
    {
        $key = Uuid::uuid4();
        Artisan::call('down', ['--secret' => $key]);
        Toastr::success(translate('maintenance_mode_activated_for_others_you_can_update_now'));
        return redirect(route('home') . '/' . $key);
    }

    public function upload_and_update(Request $request)
    {
        $request->validate([
            'update_file' => 'required|mimes:zip',
            'username' => 'required',
            'purchase_key' => 'required|uuid'
        ]);

        Helpers::setEnvironmentValue('SOFTWARE_ID', 'MzE0NDg1OTc=');
        Helpers::setEnvironmentValue('BUYER_USERNAME', $request['username']);
        Helpers::setEnvironmentValue('PURCHASE_CODE', $request['purchase_key']);

        $data = $this->actch();
        try {
            if (!$data->getData()->active) {
                return redirect(base64_decode('aHR0cHM6Ly82YW10ZWNoLmNvbS9zb2Z0d2FyZS1hY3RpdmF0aW9u'));
            }
        } catch (Exception $exception) {
            Toastr::error('verification failed! try again');
            return back();
        }

        $file = $request->file('update_file');
        $fileName = 'update.' . $file->getClientOriginalExtension();
        $file->storeAs('uploads', $fileName);

        $execute = 0;
        $zip = new ZipArchive;
        if ($zip->open(base_path('storage/app/uploads/update.zip')) === TRUE) {
            for ($i = 0; $i < $zip->numFiles; $i++) {
                if (strpos($zip->getNameIndex($i), 'Library/Constant.php') && !strpos($zip->getNameIndex($i), '.env')) {
                    $text = 'SOFTWARE_VERSION = ';
                    preg_match("/$text(\d+\.\d+)/", $zip->getFromIndex($i), $matches);
                    if (isset($matches[1]) && $matches[1] > env('SOFTWARE_VERSION')) {
                        $execute = 1;
                    }
                }
            }
            $zip->close();
        }

        if ($execute){
            $zip = new ZipArchive;
            if ($zip->open(base_path('storage/app/uploads/update.zip')) === TRUE) {
                $zip->open(base_path('storage/app/uploads/update.zip'));
                $zip->extractTo(base_path('.'));
                $zip->close();

                if (file_exists(base_path('app/Providers/RouteServiceProvider.txt'))) {
                    $previousRouteServiceProvier = base_path('app/Providers/RouteServiceProvider.php');
                    $newRouteServiceProvier = base_path('app/Providers/RouteServiceProvider.txt');
                    copy($newRouteServiceProvier, $previousRouteServiceProvier);
                }

                Artisan::call('migrate', ['--force' => true]);
                Artisan::call('cache:clear');
                Artisan::call('view:clear');
                Artisan::call('config:cache');
                Artisan::call('config:clear');

                $this->insert_data_of('13.1');
            }

            Helpers::setEnvironmentValue('SOFTWARE_VERSION', SOFTWARE_VERSION);
            Helpers::setEnvironmentValue('APP_MODE', 'live');
            Helpers::setEnvironmentValue('SESSION_LIFETIME', '60');

            Toastr::success(translate('software_updated_successfully'));
        }else{
            Toastr::error(translate('invalid_update_file'));
        }

        return back();
    }
}
