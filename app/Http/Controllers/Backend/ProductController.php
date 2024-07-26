<?php

namespace App\Http\Controllers\Backend;

use File;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class ProductController extends Controller
{
    //
    public function index(){
        $perpage = 50;
        $products = Product::latest()->paginate($perpage);
        return view('backend.product.index', compact('products'));
    }
    
    public function search(Request $request){
        $perpage = 50;
        $products = Product::where('name','LIKE','%'.$request->product_name.'%')->orWhere('product_code','LIKE','%'.$request->product_name.'%')->latest()->paginate($perpage);
        return view('backend.product.index', compact('products'));
    }
    public function create(){
        return view('backend.product.create');
    }
    public function store(Request $request):RedirectResponse{
  
        $validation = $request->validate([
            'name' => 'required',
            'category_id' => 'required',
            'sub_category_id' => 'required',

        ]);

        $counter = Product::count();
        $product = Product::create([
            'name' => $request->name,
            'slug' => str_replace('_', ' ', strtolower($request->name))."-".$counter+1,
            'product_code' => $request->product_code,
            'category_id' => $request->category_id,
            'sub_category_id' => $request->sub_category_id,
            'child_category_id' => $request->child_category_id ?? null,
            'brand_id' => $request->brand_id ?? null,
            'description' => $request->description,
            'quantity' => $request->quantity,
            'purchase_price' =>  $request->purchase_price,
            'old_price' =>  $request->old_price,
            'sale_price' =>  $request->sale_price,
            'status' =>  $request->status,
        ]);
        $request_image = $request->file('image');
        if (!is_null($request_image)){
            $name_gen = hexdec(uniqid()) . '.' . $request_image->getClientOriginalExtension();
            $request_image->move(public_path('frontend/images/product'), $name_gen);
            $product->update([
                'image' => 'frontend/images/product/'.$name_gen,
            ]);
        }
        if($product){
            noty()->info('Product created successfully.');
        }else{
            noty()->error("Product can't create!");
        }
        return redirect()->back();
    }

    public function edit($id){
        $product = Product::where('id',$id)->first();
        return view('backend.product.edit', compact('product'));
    }

    public function update(Request $request){
        $product = Product::where('id', $request->hidden_id)->first();
        $product->update([
            'name' => $request->name,
            'slug' => str_replace('_', ' ', strtolower($request->name))."-".$request->hidden_id,
            'product_code' => $request->product_code,
            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id ?? null,
            'description' => $request->description,
            'quantity' => $request->quantity,
            'purchase_price' =>  $request->purchase_price,
            'old_price' =>  $request->old_price,
            'sale_price' =>  $request->sale_price,
            'status' =>  $request->status,
        ]);
        $request_image = $request->file('image');
        if (!is_null($request_image)){
            $name_gen = hexdec(uniqid()) . '.' . $request_image->getClientOriginalExtension();
            $request_image->move(public_path('frontend/images/product'), $name_gen);
            $product->update([
                'image' => 'frontend/images/product/'.$name_gen,
            ]);
        }

        if($request->sub_category_id){
            $product->update([
                'sub_category_id' => $request->sub_category_id,
            ]);
        }
        if($request->child_category_id){
            $product->update([
                'child_category_id' => $request->child_category_id,
            ]);
        }

        if($product){
            noty()->info('Product updated successfully.');
        }else{
            noty()->error("Product can't update!");
        }
        return redirect()->back();
    }

    public function import(Request $request){
        $request_image = $request->file('file');
        $name_gen = hexdec(uniqid()) . '.' . $request_image->getClientOriginalExtension();
        $request_image->move(public_path('frontend/images/excel'), $name_gen);
        $link = 'frontend/images/excel/'.$name_gen;

        try {
            // Load the CSV file
            $inputFileName = $link;
            $inputFileType = ucfirst($request_image->getClientOriginalExtension());
    
            /** Create a new Reader of the type defined in $inputFileType **/
            $reader = IOFactory::createReader($inputFileType);
            $reader->setDelimiter(',');
            $reader->setEnclosure('"');
            $reader->setSheetIndex(0);
    
            /** Load $inputFileName to a Spreadsheet Object **/
            $spreadsheet = $reader->load($inputFileName);
    
            /** Convert Spreadsheet to Array **/
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = [];
            foreach ($worksheet->getRowIterator() as $row) {
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false); // Loop through all cells, even if no value is set
    
                $cells = [];
                foreach ($cellIterator as $cell) {
                    $cells[] = $cell->getValue();
                }
                $rows[] = $cells;
            }
            $header = true;
            // Insert data into the database
            // Assuming you have a model named 'YourModel' and columns match CSV headers
            foreach ($rows as $row) {
                
                // Skip the header row if present
                if ($header) {
                    $header = false;
                    continue;
                }
                Product::create([
                    'name' => $row[1],
                    'slug' => $row[2],
                    'category_id' => $row[3],
                    'sub_category_id' => $row[4],
                    'child_category_id' => $row[5],
                    'brand_id' => $row[6],
                    'product_code' => $row[7],
                    'description' => $row[8],
                    'image' => $row[9],
                    'quantity' => $row[10],
                    'purchase_price' => $row[11],
                    'old_price' => $row[12],
                    'sale_price' => $row[13],
                    'status' => $row[14],

                    // Add more columns as needed
                ]);
            }
    
            return response()->json(['success' => 'Data imported successfully.']);
    
        } catch (\PhpOffice\PhpSpreadsheet\Reader\Exception $e) {
            return response()->json(['error' => 'Error loading file: ' . $e->getMessage()], 500);
        }
    }
 

    public function destroy($id){
        $product = Product::where('id',$id)->first();
        $variants = ProductVariant::where('product_id', $id)->get();
        foreach($variants as $variant){
            if(File::exists($variant->image))
                {
                    File::delete($variant->image);
                }
            $variant->delete();

        }

        $images = ProductNImage::where('product_id', $id)->get();
        foreach($images as $image){
            if(File::exists($image->image))
                {
                    File::delete($image->image);
                }
            $image->delete();

        }
        $product->delete();
        if($product){
            noty()->info('Product deleted successfully.');
        }else{
            noty()->error("Product can't delete!");
        }
        return redirect()->back();
    }

    public function status(Request $request){
        $product =  Product::where('id', $request->hidden_id)->first();
        $product->update([
            'status' => $request->status,
        ]);
        if($product){
            noty()->info('Product updated successfully.');
        }else{
            noty()->error("Product can't update!");
        }
        return redirect()->back();
    }
}
