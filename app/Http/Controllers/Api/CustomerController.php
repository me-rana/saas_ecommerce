<?php

namespace App\Http\Controllers\Api;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use App\Models\Customer;
use App\Events\DashbEvent;
use App\Events\NotifyEvent;
use App\Models\OrderDetail;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\ShippingAddress;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Validator;
use App\Library\SslCommerz\SslCommerzNotification;

class CustomerController extends Controller
{
    
    public function register() {
        $validator = Validator::make(request()->all(), [
            'name' => 'required',
            'phone' => 'required|unique:customers',
            'email' => 'required|unique:customers',
            'password' => 'required|confirmed|min:8',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }
  
        $user = new Customer;
        $user->name = request()->name;
        $user->phone = request()->phone;
        $user->email = request()->email;
        $user->password = bcrypt(request()->password);
        $user->status = 1;
        $user->save();
  
        return response()->json([
            'message' => 'User Created Successfully',
            'data' => $user
        ], 201);
    }


    public function redirect()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    public function callback()
    {
        
            
        try {
            $socialUser = Socialite::driver('google')->stateless()->user();
            
            // Find or create the user in the database
            $user = Customer::updateOrCreate(
                ['email' => $socialUser->getEmail()],
                [
                    'name' => $socialUser->getName(),
                    'provider' => 'google',
                    'provider_id' => $socialUser->getId(),
                    'avatar' => $socialUser->getAvatar()
                ]
            );
    
            // Generate JWT token
            $token = JWTAuth::fromUser($user);
    
            return response()->json(['token' => $token]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Unable to authenticate'], 401);
        }
    }

  
  

    public function login()
    {

        $phone = request()->phone;
        $check = Customer::where('phone', request()->phone)->orWhere('email',request()->phone)->first();
        if($check != null && $check->email == request()->phone){
           $credentials = ['email' => $check->email, 'password' => request()->password];
        }
        else{
            $credentials = request(['phone', 'password']);
        }
        
        
  
        if (! $token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'Authentication Failed!', 'data' => 'n/A'], 401);
        }
        
        $myTTL = 43200; //minutes
        JWTAuth::factory()->setTTL($myTTL);
        
        
  
        return $this->respondWithToken($token);
    }
  
    /**
     * Get the authenticated Customer.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function profile()
    { 
        return response()->json(auth('api')->user()->makeHidden(['forget','password'])); 
    }

    public function forget(Request $request){
        $validator = Validator::make(request()->all(), [
            'phone' => 'required',
        ]);
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }
        $forget_key = rand(1111,9999);
        $user = Customer::where('phone',$request->phone)->first();
        if($user == null){
            return response()->json([
                'message' => 'No User Found',
                'data' => 'n/A',
            ], 400);
        }
        $user->update([
                'forget' => $forget_key,
            ]);
            $url = env('SMS_URL');
            $data = [
                "api_key" => env('SMS_API_KEY'),
                "senderid" => env('SMS_SENDER_ID'),
                "number" => $request->phone,
                "message" => 'পাসওয়ার্ড রিসেটের জন্য আপনার ওটিপি '.$forget_key.'.'
            ];
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($ch);
            curl_close($ch);
        return response()->json([
            'message' => 'OTP Submitted to Reset Password',
            'otp' => $forget_key,
        ], 201);
    }

    public function reset(Request $request){
        $validator = Validator::make(request()->all(), [
            'phone' => 'required',
            'otp' => 'required',
            'password' => 'required'
        ]);
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }
        $user = Customer::where('phone', $request->phone)->first();
        if($user->forget == $request->otp){
            $user->update([
                'forget' => rand(1111,9999),
                'password' => Hash::make($request->password),
            ]);
            return response()->json([
                'message' => 'Password Reset Successfully',
                'data' => $user->makeHidden(['forget']),
            ], 201);
        }
        else{
            return response()->json([
                'message' => 'Wrong OTP Submitted',
                'data' => $request->otp,
            ], 400);
        }
    }

    public function updateProfile(Request $request){
        $validator = Validator::make(request()->all(), [
            'name' => 'required',
            'email' => 'required',
            'address' => 'required',
            'image' => 'nullable|mimes:jpg,jpeg,png,webp,gif,svg',
        ]);
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }
        $user = Customer::where('id',auth('api')->user()->id)->first();

        $request_image = $request->file('image');
        if (!is_null($request_image)){
            $name_gen = hexdec(uniqid()) . '.' . $request_image->getClientOriginalExtension();
            $request_image->move(public_path('frontend/images/concern'), $name_gen);
            $user->update([
                'image' => 'frontend/images/concern/'.$name_gen,
            ]);
            }
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'district' => $request->district ?? '',
            'area' => $request->area ?? '',
            'address' => $request->address,
        ]);
        return response()->json([
            'message' => 'Profile update Successfully',
            'data' => $user->makeHidden(['forget', 'password']),
        ], 201);
    }

    public function changePassword(Request $request){
        $validator = Validator::make(request()->all(), [
            'password' => 'required|confirmed|min:8',
        ]);
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }
        $user = Customer::where('id',auth('api')->user()->id)->first();
        if(Hash::check($request->password, $user->password)){
            $user->update([
                'password' => Hash::make($request->new_password),
            ]);
        }
        return response()->json([
            'message' => 'Password update Successfully',
            'data' => $user->makeHidden(['forget', 'password']),
        ], 201);
    }

    public function categories(){
        $categories = Category::where(['parent_id' => null, 'master_id' => null, 'status' => 1])->get();
        if($categories->count() > 0){
            return response()->json(['categories' => $categories],200);
        }
        else{
            return response()->json(['categories' => 'n/A'],404);
        }
    }

    public function subCategories(Request $request){
        $subcategories = Category::where(['parent_id' => $request->category, 'master_id' => null, 'status' => 1])->get();
        if($subcategories->count() > 0){
            return response()->json(['subcategories' => $subcategories],200);
        }
        else{
            return response()->json(['subcategories' => 'n/A'],404);
        }
    }

    public function childCategories(Request $request){
        $childcategories = Category::where(['parent_id' => $request->sub_category , 'master_id' => $request->category, 'status' => 1])->get();
        if($childcategories->count() > 0){
            return response()->json(['childcategories' => $childcategories],200);
        }
        else{
            return response()->json(['childcategories' => 'n/A'],404);
        }
    }

    public function categoryProduct(Request $request){
        $perpage = 50;
        $products = Product::where(['category_id' => $request->category, 'sub_category_id' => $request->subcategory,'child_category_id' => $request->childcategory, 'status' => 1]) 
                    ->orWhere(['category_id' => $request->category, 'sub_category_id' => $request->subcategory, 'status' => 1]) 
                    ->orWhere(['category_id' => $request->category, 'status' => 1 ]);
        if($request->filter_by == 'a-z'){
            $products = $products->orderBy('name','asc');; 
        }
        elseif($request->filter_by == 'z-a'){
            $products = $products->orderBy('name','desc'); 
        }
        elseif($request->filter_by == 'older'){
            $products = $products->orderBy('id','asc');
        }
        elseif($request->filter_by == 'low-to-high'){
            $products = $products->orderBy('sale_price','asc'); 
        }
        elseif($request->filter_by == 'high-to-low'){
            $products = $products->orderBy('sale_price','desc'); 
        }
        else{
            $products = $products->orderBy('id','desc'); 
        }
        
        $products = $products->paginate($perpage);

        if($products->count() > 0){
            return response()->json(['products' => $products],200);
        }
        else{
            return response()->json(['products' => 'n/A'],404);
        }
    }     
    
    public function products(Request $request){
        $perpage = 50;
        $products = Product::where('status',1);

        if($request->filter_by == 'a-z'){
            $products = $products->orderBy('name','asc');; 
        }
        elseif($request->filter_by == 'z-a'){
            $products = $products->orderBy('name','desc'); 
        }
        elseif($request->filter_by == 'older'){
            $products = $products->orderBy('id','asc');
        }
        elseif($request->filter_by == 'low-to-high'){
            $products = $products->orderBy('sale_price','asc'); 
        }
        elseif($request->filter_by == 'high-to-low'){
            $products = $products->orderBy('sale_price','desc'); 
        }
        else{
            $products = $products->orderBy('id','desc'); 
        }


        $products = $products->paginate($perpage);
        if($products->count() > 0){
            return response()->json(['products' => $products],200);
        }
        else{
            return response()->json(['products' => 'n/A'],404);
        }
    } 

    public function addToCart(Request $request){
        $product = Product::where('id', $request->product_id)->first();
        $check = Cart::where(['product_id' =>  $request->product_id, 'user_id' => auth('api')->user()->id])->first();
        if($check != null){
            $total_qty = $check->qty + $request->qty;
            $sub_total =  $check->price * $total_qty;
            if($product->quantity < $total_qty){
                return response()->json(['message' => 'We have only '.$product->quantity.' products'], 404);
            }

            $check->update([
                'qty' => $total_qty,
                'sub_total' => $sub_total,
            ]);
            return response()->json(['message' => 'Cart Updating Successful!'],200);
        }
        
        if($product == null){
            return response()->json(['message' => 'Product Not Found!'], 404);
        }

        if($product->quantity < $request->qty){
            return response()->json(['message' => 'We have only '.$product->quantity.' products'], 404);
        }
        Cart::create([
            'user_id' => auth('api')->user()->id,
            'product_id' =>  $request->product_id,
            'name' => $product->name,
            'qty' =>  $request->qty,
            'price' =>  $product->sale_price,
            'image' => $product->image,
            'slug' => $product->slug ?? '',
            'old_price' => $product->old_price,
            'purchase_price' => $product->purchase_price,
            'sub_total' => $request->qty * $product->sale_price,
        ]);
        return response()->json(['message' => 'Cart Including Successful!'],200);
    }

    public function carts(){
        $cart_list = Cart::where('user_id',auth('api')->user()->id)->get();
        $total_amount = Cart::where('user_id',auth('api')->user()->id)->sum('sub_total'); 
        $carts = compact('cart_list','total_amount');
        if($cart_list->count() > 0){
            return response()->json(['data' => $carts],200);
        }
        else{
            return response()->json(['data' => 'n/A'],404);
        }
    }

    public function cartIncrement(Request $request){
        $cart = Cart::where(['user_id' => auth('api')->user()->id, 'id' => $request->cart_id])->first();
        $qty = $cart->qty + 1;
        $price = $cart->price;
        $cart->update([
            'qty' => $qty,
            'sub_total' => $qty * $price,
        ]);
        if($cart != null){
            return response()->json(['message' => 'Cart Increment Successful!'],200);
        }
        else{
            return response()->json(['data' => 'n/A'],404);
        }
    }

    public function cartDecrement(Request $request){
        $cart = Cart::where(['user_id' => auth('api')->user()->id, 'id' => $request->cart_id])->first();
        $qty = $cart->qty - 1;
        if($qty <= 0){
            $cart->delete();
            return response()->json(['message' => 'Cart Removed Successfully'],200);
        }
        
        $price = $cart->price;
        $cart->update([
            'qty' => $qty,
            'sub_total' => $qty * $price,
        ]);
        if($cart != null){
            return response()->json(['message' => 'Cart Decrement Successful!'],200);
        }
        else{
            return response()->json(['data' => 'n/A'],404);
        }
    }

    Public function cartRemove(Request $request){
        $cart = Cart::where(['user_id' => auth('api')->user()->id, 'id' => $request->cart_id])->delete();
        return response()->json(['message' => 'Cart Removed Successfully'],200);
    }

    public function cartClear(){
        $cart = Cart::where(['user_id' => auth('api')->user()->id])->delete();
        return response()->json(['message' => 'Cart Cleared Successfully'],200);
    }

    public function placeOrder(Request $request){
        $carts = Cart::where('user_id',auth('api')->user()->id)->get();
        $total_amount = Cart::where('user_id',auth('api')->user()->id)->sum('sub_total'); 
        $serial_id = (Order::count() > 0) ? (Order::latest()->first()->id) : (Order::count());
        if($carts->count() > 0){
            //Customer Get or Generate
            $existCustomer  =  Customer::where('phone',$request->phone)->first();
            
            if($existCustomer){
                if($existCustomer->status != 1){
                    return response()->json(['message' => 'Customer Account has been disabled', 'data' =>'n/A'],400);
                }
                $customerId = $existCustomer->id;
            }
            else{
                $customer = Customer::create([
                    'name' => $request->name,
                    'phone' => $request->phone,
                    'password' => Hash::make($request->phone.'2024'),
                    'address' => $request->address,
                    'status' => 1
                ]);
                $customerId = $customer->id;
            }

            //Shipping Data Store
            $shipping = ShippingAddress::create([
                'name' =>  $request->name,
                'customer_id' => $customerId,
                'phone' => $request->phone,
                'address' => $request->address
            ]);

           $order = Order::create([
                'invoice_id' => 'A'.date('y').str_pad($serial_id+1, 4, '0', STR_PAD_LEFT) ?? '',
                'customer_id' => $customerId ?? '',
                'shipping_id' => $shipping->id ?? '',
                'total_amount' => $total_amount ?? '',
                'status' => 1 ?? '', 
                'ip_address' => $request->ip() ?? '',
            ]);

            foreach($carts as $cart){
                $productInfo = Product::where('id', $cart->product_id)->first();
                $order_details = OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $cart->product_id,
                    'product_name' =>  $cart->name,
                    'purchase_price' => $cart->purchase_price ?? '',
                    'sale_price' =>  $cart->price,
                    'image' => $cart->image ?? '',
                    'quantity' => $cart->qty,
                    'sub_total' => $cart->sub_total,
                ]);
                $productInfo->update([
                    'quantity' => $productInfo->quantity - $cart->qty,
                ]);
            }

            $customerInfo = Customer::where('id', auth('api')->user()->id)->first();
            if($request->method != 1){
                
                $post_data = array();
                $post_data['total_amount'] = $total_amount;
                $post_data['currency'] = "BDT";
                $post_data['tran_id'] = uniqid();
        
                # CUSTOMER INFORMATION
                $post_data['cus_name'] = $request->name;
                $post_data['cus_email'] = 'dev@rana.my.id';
                $post_data['cus_add1'] = $request->address;
                $post_data['cus_add2'] = "";
                $post_data['cus_city'] = "";
                $post_data['cus_state'] = "";
                $post_data['cus_postcode'] = "";
                $post_data['cus_country'] = "Bangladesh";
                $post_data['cus_phone'] = $request->phone;
                $post_data['cus_fax'] = "";
        
                # SHIPMENT INFORMATION
                $post_data['ship_name'] = "Store Test";
                $post_data['ship_add1'] = "Dhaka";
                $post_data['ship_add2'] = "Dhaka";
                $post_data['ship_city'] = "Dhaka";
                $post_data['ship_state'] = "Dhaka";
                $post_data['ship_postcode'] = "1000";
                $post_data['ship_phone'] = "";
                $post_data['ship_country'] = "Bangladesh";
        
                $post_data['shipping_method'] = "NO";
                $post_data['product_name'] = "Grocery";
                $post_data['product_category'] = "Goods";
                $post_data['product_profile'] = "physical-goods";
        
                # OPTIONAL PARAMETERS
                $post_data['value_a'] = "ref001";
                $post_data['value_b'] = "ref002";
                $post_data['value_c'] = "ref003";
                $post_data['value_d'] = "ref004";

            $payment = Transaction::where('transaction_id',$post_data['tran_id'])
            ->updateOrCreate([
                'transaction_id' => $post_data['tran_id'],
                'amount' => $post_data['total_amount'],
                'currency' => $post_data['currency'],
                'customer_id' => auth('api')->user()->id,
                'payment_type' => 'SSLCOMMERZ',
                'order_id' => $order->id,
                'status' => 0
            ]);

            $sslc = new SslCommerzNotification();
            # initiate(Transaction Data , false: Redirect to SSLCOMMERZ gateway/ true: Show all the Payement gateway here )
            $payment_options = $sslc->makePayment($post_data);

            if (!is_array($payment_options)) {
                print_r($payment_options);
                $payment_options = array();
            }
        

            }
            else{
                Transaction::create([
                    'amount' => $total_amount,
                    'currency' => 'BDT',
                    'payment_type' => 'COD',
                    'customer_id' => auth('api')->user()->id,
                    'order_id' => $order->id,
                    'status' => 0
                ]);

   
            }
            $cart = Cart::where('user_id',auth('api')->user()->id)->delete();
            event(new NotifyEvent($order->invoice_id." ordered placed successfully and total amount ".$total_amount));
            event(new DashbEvent());
            return response()->json(['message' => 'Order Placed Successfully'],200);
        }
        else{
            return response()->json(['data' => 'n/A'],404);
        }
    }

    public function orderList(){
        $perpage = 20;
        $orders = Order::where('customer_id',auth('api')->user()->id)->with(['getOrderDetails' => function($query){
            $query->select('id','order_id','product_name','image','quantity');
        },'getOrderStatus' => function ($query) {
            $query->select('id','name');
        }])->paginate($perpage);
        if($orders->count() > 0){
            return response()->json(['data' => $orders],200);
        }
        else{
            return response()->json(['data' => 'Empty Order'],404);
        }
    }

    public function orderDetails(Request $request){
        
        $orders = Order::where(['customer_id' => auth('api')->user()->id,'id' => $request->order_id])->with(['getOrderDetails.getProduct' => function($query) {
            $query->select('id','name', 'image', 'quantity', 'sale_price', 'slug');
            },'getCustomer','getShipping','getPayment'=>function($query){ $query->select('order_id','payment_type','transaction_id', 'status', 'amount'); }])->first();
        return response()->json(['orders' => $orders],200);
    }








    public function logout()
    {
        if(auth('api')->user() == null){
            return response()->json(['message' => 'You are not logged In'],404);
        }
        auth('api')->logout();
        return response()->json(['message' => 'Successfully logged out'],200);
    }
  

    public function refresh()
    {
        return $this->respondWithToken(auth('api')->refresh());
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }
}
