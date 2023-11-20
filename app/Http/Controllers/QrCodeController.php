<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\QrCode;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode as FacadesQrCode;

class QrCodeController extends Controller
{
    public function index()
    {

        $uniqueId = Str::random(10);
        $model = QrCode::where('code', $uniqueId)?->first();
        while ($model !== null) {
            $uniqueId = Str::random(10);
            $model = QrCode::where('code', $uniqueId)?->first();
        }
        QrCode::create([
            'code' => $uniqueId,
            'period' => 2,
            'start_date' => Carbon::now()->format('Y-m-d H:i:s')
        ]);

        $url = route('chat-with-assistant', ['qrcode' => $uniqueId]);

        $qrCode = FacadesQrCode::size(200)->generate($url);
        return view('generate-qrcode', ['qrCode' => $qrCode, 'uniqueId' =>  $uniqueId]);
    }

    public function chatWithAssistant(Request $request)
    {
        $qrcode = $request->qrcode;
        $qrModel = QrCode::where('code', $qrcode)?->first();

        if (!$qrModel) {
            abort(403);
        }
        // Calculate the difference in days
        $daysDifference = Carbon::parse($qrModel->start_date)->diffInDays(Carbon::now());

        // Check if the difference is greater than the period
        if ($daysDifference > $qrModel->period) {
            abort(419);
        }

        return view('chat', [
            'uniqueId' => $qrcode
        ]);
    }

    // MessageController.php
    public function sendMessage(Request $request, $uniqueId)
    {
        $message = $request->input('message');
        event(new MessageSent($message, $uniqueId));
        return response()->json(['status' => 'success']);
    }
}
