<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ItemPostRequest;
use Illuminate\Http\Request;
use App\Models\Auction;
use App\Models\Item;
use App\Http\Resources\AuctionRes;
use App\Http\Resources\HistoryAuctionRes;
use App\Models\HistoryAuction;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use illuminate\Support\Str;


class AuctionController extends Controller
{
    public function index()
    {
        $auction = Auction::join('items','items.id_item','=','auctions.id_item')->leftJoin('users','users.id','=','auctions.id');
        if(request()->status){
            $auction = $auction->where('status_item',request()->status);
        }
        if(request()->id_auction){
            $auction = $auction->where('id_auction',request()->id_auction);
        }
        if(request()->top == true){
            $auction = $auction->orderBy('auctions.final_price','DESC');
        }
        if(request()->limit){
            $auction = $auction->take(request()->limit);
        }

        $auction = $auction->get();
        $colAuction = AuctionRes::collection($auction);

        return response()->json([
            'message' => "List Auction",
            "status" => '200',
            'data' => $colAuction,
            'count' => $auction->count()
        ]);
    }

    public function history_auction(){
        $id_auction = request()->id_auction;
        if($id_auction == null){
            return response()->json([
                'message' => 'Please insert id auction'
            ]);
        }

        $history = HistoryAuction::join('items','items.id_item','=','history_auctions.id_item')->join('auctions','auctions.id_auction','=','history_auctions.id_auction')->join('users','users.id','=','history_auctions.id');
        $history = $history->where('history_auctions.id_auction',$id_auction);
        if(request()->user == true){
            $history = $history->where('history_auctions.id',auth('sanctum')->user()->id);
        }
        $history = $history->orderBy('history_auctions.bid_price','DESC');
        $history = $history->get();

        $historyCol = HistoryAuctionRes::collection($history);

        return response()->json([
            'message' => 'History Auction',
            'id_auction' => $id_auction,
            'data' => $historyCol
        ],200);
    }

    public function detailAuction($id_auction){
        $auction = Auction::join('items','items.id_item','=','auctions.id_item')->leftJoin('users','users.id','=','auctions.id')->get();

        $id_auction = $auction->where('id_auction',request()->id_auction);

        $colAuction = AuctionRes::collection($auction);
        // if(request()->id_auction){
        //     $auction = $auction->where('id_auction',request()->id_auction);
        // }

        return response()->json([
            'message'=> "Detail Auction",
            "status" => '200',
            'data' => $colAuction
        ]);
        

    }

    public function insertData(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'image_item' => 'required',
            'name_item' => 'required',
            'opening_price' => 'required|numeric',
            'description_item' => 'required'
        ]);

       /* if(auth('sanctum')->user()->level != 'staff'){
            return response()->json([
                "message" => "Kamu tidak memiliki akses untuk itu"
            ],403);
        }*/

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors(),
                'data' => [],
                'debug' => $request->all()
            ]);
        }

        // if($file = $request->file('image_item')){
        //     $path = $file->store('item_auction_image');
        //     $image_item = $file->getClientOriginalName();

        //     //Store your file into directory and db
        //     $save = new Item();
        //     $save->image_item = $file;
        //     $save-> $path;
        //     $save->save();
        // }

        //Mengirim image menggunakan api
        // $file = request()->file('image_item');
        // $file_name = now().'_'.request()->name_item.'.png';
        // $path_upload = 'item_auction_image';

        // $file->move($path_upload,$file_name);


      //  $imageName = Str::random(32).".".$request->image_item->getClientOriginalExtension();

        $data_item = [
            'image_item' => request()->image_item,
            'name_item' => request()->name_item,
            'date_item' => now(),
            'opening_price'=> request()->opening_price,
            'description_item' => request()->description_item
        ];

      //  Storage::disk('public')->put($imageName, file_get_contents($request->image_item));

        $id_item = Item::create($data_item)->id;

        $data_auction = [
            'id_item' => $id_item,
            'date_open_auction' => now(),
            'date_close_auction' => date('Y-m-d',strtotime(now()."+ ".request()->date_close_auction."days")),
            'id' => null,
            'id_staff' => auth('sanctum')->user()->id_staff,
            'status_item' => "available"
        ];

        Auction::insert($data_auction);
        $auction = Auction::join('items','items.id_item','=','auctions.id_item')->leftJoin('users','users.id','=','auctions.id')->where('auctions.id_item',$id_item)->get();
        $ItemGet = AuctionRes::collection($auction);
        return response()->json([
            'message' => "Auction item successfululy to add and ready to auction",
            'data' => $auction[0]
        ]);
    }
    

    public function bids(){
        $id_auction = request()->id_auction;
        $bids = intval(request()->bid);
        if($id_auction == null){
            return response()->json([
                'message' => 'Id auction not found'
            ]);
        }
        $auction = Auction::join('items','items.id_item','=','auctions.id_auction')->where('id_auction',$id_auction)->first();
        $history = HistoryAuction::where('id_auction',$id_auction)->where('id',auth('sanctum')->user()->id)->orderBy('history_auctions.bid_price','DESC')->first();
        if($history){
            if($history->bid_price >= $auction->final_price){
                return response()->json([
                    'message'=> "You can't bid if you're highest bidder!"
                ]);
            }
        }
        $min = $auction->final_price;
        if($auction->status == 'sold'){
            return response()->json([
                'message' => "Auction has been closed!"
            ]);
        }
        if($auction->opening_price >= $bids){
            $min = $auction->opening_price;
        }
        if(auth('sanctum')->user() == null){
            return response()->json([
                'message' => "Please login first before bidding!"
            ]);
        }
        if(auth('sanctum')->user()->level == 'administrator' || auth('sanctum')->user()->level == 'staff'){
            return response()->json([
                'message' => "Administrartor or staff can't bid an auction!"
            ]);
        }
        if($bids <= $min){
            return response()->json([
                'message' => "You must bid higher!"
            ]);
        }
        if(strtotime($auction->date_close_auction)<= time() || $auction->status == 'sold'){
            return response()->json([
                'message' => "You can't bid because this auction is close"
            ]);
        }
        $data_history = [
            'id_item' => $auction->id_item,
            'id_auction' => $id_auction,
            'id' => auth('sanctum')->user()->id,
            'bid_price' => $bids
        ];

        HistoryAuction::insert($data_history);

        Auction::where('id_auction',$id_auction)->update(['id'=>auth('sanctum')->user()->id,'final_price'=>$bids]);

        return response()->json([
            'message' => "Thanks bro for submitting the bid"
        ]);
        
    }
    //handle base64 encoded images here 

}
