<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrCodeController extends Controller
{
    public function index()
    {
        $url = route('chat-with-assistant');
        $qrCode = QrCode::size(200)->generate($url);

        return view('generate-qrcode', ['qrCode' => $qrCode]);
    }

    public function chatWithAssistant()
    {
        event(new MessageSent('hibb'));
      
        return back();
    }
}
