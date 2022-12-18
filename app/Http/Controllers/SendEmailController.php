<?php

namespace App\Http\Controllers;

use App\Mail\NotifyMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SendEmailController extends Controller
{
    public function sendEmail()
    {
        $date = date_format(date_create('2021-02-12'), 'jS M Y');
        $time = date('h:i A', strtotime('10:23:11'));

        $data = [
            'subject' => 'Confirmation Email from TableSea',
            'email' => 'seifubini@gmail.com',
            'content' => 'Test Content Goes Here. Confirmed!',
            'status' => 'Confirmed',
            'date' => $date,
            'time' => $time,
            'guest_name' => 'Biniam',
            'reservation_code' => '12324234',
            'Restaurant_name' => 'Connolly',
            'restaurant_id' => '10',
            'Restaurant_address' => 'Addis Ababa, Ethiopia',
            'no_of_people' => '3',
            'country' => 'Ethiopia',
            'city' => 'Addis Ababa',
            'phone' => '0909090909'
        ];
        //Mail::to('biniam@ccsethiopia.com')->send(new NotifyMail());
        Mail::send('Emails.admin_mail', $data, function($message) use ($data) {
            $message->to($data['email'])
                ->subject($data['subject']);
        });

        if (Mail::failures()) {
            return response()->json(array('error' => true, 'message' => 'Sorry! Please try again latter.'));
        }else{
            return response()->json(array('success' => true, 'message' => 'Great! Email Successfully sent to '. $data['email']));
        }
    }
}

/**if (Mail::failures()) {
    return redirect()->route('create_reservation', $restaurant_id)
        ->with('success',
            'Reservation Created Successfully.But the system cannot create email notification at the moment, try notifying the guest via phone call.');
}else{
    return redirect()->route('create_reservation', $restaurant_id)
        ->with('success', 'Reservation Created Successfully. Email notification sent.');
}*/
