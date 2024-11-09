<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TicketingController extends Controller
{
    public function index(){
        $seats = DB::table('seats')->get();
        return view("home", compact("seats"));
    }

    public function customer_data(){
        $payment = DB::table("payments")->select("seat_id")->get();
        return response()->json([
            "payment" => $payment
        ]);
    }

    public function add_customer(Request $req){
        $seat_id = $req->seat_id;
        $name = $req->name;
        $phone = $req->phone;
        $amount = $req->amount;
        $movie = $req->movie;
        if($phone != null && $name != null && $amount != null && $movie != null){
            $customer_exists = DB::table("customer")->where("phone",$phone)->exists();
            if($customer_exists != 1){
                DB::table("customer")->insert([
                    "name" => $name,
                    "phone" => $phone,
                    "created_at" => now()
                ]);
            }

            $cust_id = DB::table("customer")->where("phone",$phone)->first();

            //Deal with the payment
            DB::table("payments")->insert([
                "amount" => $amount,
                "customer_id" => $cust_id->id,
                "seat_id" => $seat_id,
                "movie" => $movie,
                "status" => "paid",
                "created_at" => now()
            ]);

            $response = "Booking complete";
        }else{
            $response = "All fields must be field!";
        }
        return response()->json([
            "message" => $response,
            "seat_no" => $seat_id
        ]);
    }

    public function customer_info(Request $req){
        $seat_no = $req->seat_no;
        $link = route("customer.qrcode.display",$seat_no);
        return response()->json([
            "seat_no" => $seat_no,
            "link" => $link
        ]);
    }

    public function display_customer_qrcode_info($seat_no){
        $data = DB::table("customer")->leftJoin("payments","payments.customer_id","=","customer.id")->where("seat_id",$seat_no)->first();
        return view("customer")->with("data",$data);
    }

    public function clear_seat(Request $req){
        $seat_no = $req->seat_no;
        DB::table("payments")->where("seat_id",$seat_no)->delete();
        return response()->json([
            "seat_no" => $seat_no
        ]);
    }
}
