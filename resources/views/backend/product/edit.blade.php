@extends('backend.layout.theme')
@section('main')
@php
  $serial_id =  (App\Models\Product::count() > 0) ? (App\Models\Product::latest()->first()->id) : (App\Models\Product::count());;
 
  $categories = App\Models\Category::where(['status' => 1, 'parent_id' => null,'master_id' => null])->get();
  $subcategories = App\Models\Category::where(['status' => 1,'master_id' => null])->whereNotNull('parent_id')->get();
  $childcategories = App\Models\Category::where(['status' => 1])->whereNotNull('master_id')->whereNotNull('parent_id')->get();

@endphp
<style>
label.btn {
  padding: 0;
}

label.btn input {
  opacity: 0;
  position: absolute;
}

label.btn span {
  text-align: center;
  padding: 6px 12px;
  display: block;
}

label.btn input:checked+span {
  background-color: rgb(80, 110, 228);
  color: #fff;
}
</style>

 <!--  Content Begin Here -->

    <div class="container-fluid">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title fw-semibold mb-4">Product Create</h5>

           
                  <!-- Top Start -->
                  <div class="row justify-content-end align-item-center">
                    <div class="col-md-6 text-right">
                        <a href="{{ url('product/manage') }}"><button class="btn btn-primary">Manage</button></a>
                    </div>
                </div>
                <!-- Top End-->
                
                <form action="{{ url('product/update') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="hidden_id" value="{{ $product->id }}">
                  <div class="row">
                    <div class="mb-3 col-md-6">
                        <label for="" class="form-label">Name</label>
                        <input type="text" name="name" value="{{old('name') ?? $product->name}}" id="" class="form-control" placeholder="" aria-describedby="helpId" />
                    </div>

                    <div class="mb-3 col-md-6">
                      <label for="" class="form-label">Product Code</label>
                      <input type="text" name="product_code" id="" class="form-control" placeholder="" value="{{ str_pad($product->product_code, 4, '0', STR_PAD_LEFT); }}" aria-describedby="helpId" />
                  </div>

      
                  <div class="mb-3 col-md-6">
                    <label for="" class="form-label">Category</label>
                    <select
                      class="form-control"
                      name="category_id"
                      id="category"
                    >
                      <option value="" selected>Select one</option>
                      @foreach ($categories as $category)
                        @if ($category->id == $product->category_id)
                        <option id="catex" value="{{ $category->id }}" selected>{{ $category->name }}</option>
                        @endif
                      @endforeach
                    </select>
                  </div>

                  <div class="mb-3 col-md-6">
                    <label for="" class="form-label">Sub-Category</label>
                    <select
                      class="form-control"
                      name="sub_category_id"
                      id="subcategory"
                      disabled
                    >
                      <option value="" selected>Select one</option>
                      @foreach ($subcategories as $subcategory)
                        @if ($subcategory->id == $product->sub_category_id)
                        <option id="subex" value="{{ $subcategory->id }}" selected>{{ $subcategory->name }}</option>
                        @endif
                      @endforeach
                    </select>
                  </div>

                  <div class="mb-3 col-md-6">
                    <label for="" class="form-label">Child-Category</label>
                    <select
                      class="form-control"
                      name="child_category_id"
                      id="childcategory"
                      disabled
                    >
                      <option value="" selected>Select one</option> 
                      @foreach ($childcategories as $childcategory)
                        @if ($childcategory->id == $product->child_category_id)
                        <option id="childex" value="{{ $childcategory->id }}" selected>{{ $childcategory->name }}</option>
                        @endif
                      @endforeach
                    </select>
                  </div>

                
                

  
                    <div class="mb-3 col-md-3">
                      <label for="" class="form-label">Quantity</label>
                      <input type="number" name="quantity" value="{{old('quantity') ?? $product->quantity}}" id="" class="form-control" placeholder="" aria-describedby="helpId" />
                  </div>

                  <div class="mb-3 col-md-3">
                    <label for="" class="form-label">Purchase Price</label>
                    <input type="number" name="purchase_price" value="{{old('purchase_price') ?? $product->purchase_price}}" id="" class="form-control" placeholder="" aria-describedby="helpId" />
                  </div>
                  <div class="mb-3 col-md-3">
                    <label for="" class="form-label">Old Price</label>
                    <input type="number" name="old_price" value="{{old('old_price') ?? $product->old_price}}" id="" class="form-control" placeholder="" aria-describedby="helpId" />
                  </div>
                  <div class="mb-3 col-md-3">
                    <label for="" class="form-label">Sale Price</label>
                    <input type="number" name="sale_price" value="{{old('sale_price') ?? $product->sale_price}}" id="" class="form-control" placeholder="" aria-describedby="helpId" />
                  </div>
       
                  <div class="mb-3 col-md-6">
                    <label for="" class="form-label">Choose file</label>
                    <input type="file" class="form-control" name="image" id="" placeholder="" aria-describedby="fileHelpId" />
                  </div>
                  <div class="mb-3 col-md-12">
                    <label for="" class="form-label">Description</label>
                    <textarea class="form-control" name="description" id="#inp_editor1" rows="3">{{old('description') ?? $product->description}}</textarea>
                  </div>


                    <div class="col-md-12">
                        <label for="" class="form-label">Status</label> <br>
                        <label class="btn" id="off"><input type="radio"  name="status" value="1" checked><img src="../../../backend/images/button/switch-off.png" height="50px" /></label>
                        <label class="btn" id="on"><input type="radio"  name="status" value="0"><img src="../../../backend/images/button/switch-on.png" height="50px" /></label>
                      </div>
                    </div>
                    
                    <button
                        type="submit"
                        class="btn btn-primary w-100"
                    >
                        Submit
                    </button>
                    

                    
                </fbackend
        </div>
      </div>
    </div>
  </div>

 <!--  Content End Here -->



@endsection

@section('scripts')


    <script>
        $(document).ready(function(){

            $('#off').hide();
            $('input[type="radio"]').click(function() {
                $('input[type="radio"]').not(this).prop('checked', false);
            });
            $('#off').on('click',function(){
                $('#on').show();
                $('#off').hide();
                console.log('On');
            });

            $('#on').on('click',function(){
                $('#off').show();
                $('#on').hide();
                console.log('Off');
            });
            
        });
    </script>
      <script>
        const data = {
            categories: @json($categories),
            subcategories: @json($subcategories),
            childcategories: @json($childcategories)
        };

        $(document).ready(function() {
            // Populate categories
            $.each(data.categories, function(index, category) {
                $('#category').append($('<option>', { value: category.id, text: category.name }));
            });

            // Handle category change
            $('#category').change(function() {
                const categoryId = $(this).val();
                $('#subcategory').empty().append($('<option>', { value: '', text: 'Select Sub Category' })).prop('disabled', true);
                $('#childcategory').empty().append($('<option>', { value: '', text: 'Select Child Category' })).prop('disabled', true);

                if (categoryId) {
                    $.each(data.subcategories, function(index, subcategory) {
                        if (subcategory.parent_id == categoryId) {
                            $('#subcategory').append($('<option>', { value: subcategory.id, text: subcategory.name }));
                        }
                    });
                    $('#subcategory').prop('disabled', false);
                }
            });

            // Handle subcategory change
            $('#subcategory').change(function() {
                const subcategoryId = $(this).val();
                $('#childcategory').empty().append($('<option>', { value: '', text: 'Select Child Category' })).prop('disabled', true);

                if (subcategoryId) {
                    $.each(data.childcategories, function(index, childcategory) {
                        if (childcategory.parent_id == subcategoryId) {
                            $('#childcategory').append($('<option>', { value: childcategory.id, text: childcategory.name }));
                        }
                    });
                    $('#childcategory').prop('disabled', false);
                }
            });
        });
    </script>
    
@endsection
