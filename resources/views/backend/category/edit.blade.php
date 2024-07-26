@extends('backend.layout.theme')
@section('main')
@php
  $categories = App\Models\Category::where('status', 1)->get();
  $subcategories = App\Models\Category::where(['status' => 1])->where('parent_id','!=',null)->get();
  $childcategories = App\Models\Category::where(['status' => 1])->where('master_id','!=',null)->get();
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
          <h5 class="card-title fw-semibold mb-4">Category Update</h5>

           
                  <!-- Top Start -->
                  <div class="row justify-content-end align-item-center">
                    <div class="col-md-6 text-right">
                        <a href="{{ url('category/manage') }}"><button class="btn btn-primary">Manage</button></a>
                    </div>
                </div>
                <!-- Top End-->
                
                <form action="{{ url('category/update') }}" method="post" enctype="multipart/form-data">
                    @csrf

                    <input name="hidden_id" value="{{ $category->id }}" hidden />
                    <div class="mb-3">
                        <label for="" class="form-label">Name</label>
                        <input type="text" name="name" value="{{ $category->name }}" id="" class="form-control" placeholder="" aria-describedby="helpId" />
                    </div>


                    <div class="mb-3">
                      <label for="" class="form-label">Parent Category</label>
                      <select
                        class="form-control"
                        name="parent_id"
                        id=""
                      >
                        <option value="" selected>Select one</option>
                        @foreach ($categories as $xcategory)
                        @if ($xcategory->master_id == null && $xcategory->parent_id == null)
                          <option value="{{ $xcategory->id }}" @if($xcategory->id == $category->parent_id) selected @endif>{{ $xcategory->name }}</option>
                          @foreach ($subcategories as $subcategory)
                            @if ($subcategory->parent_id == $xcategory->id)
                            <option value="{{ $subcategory->id }}" @if($subcategory->id == $category->parent_id) selected @endif>-- {{ $subcategory->name }}</option>
                            @endif
                          @endforeach
                        @endif
                      @endforeach
                        
                      </select>
                    </div>

                  <div class="mb-3">
                    <label for="" class="form-label">Choose file</label>
                    <input type="file" class="form-control" name="image" id="" placeholder="" aria-describedby="fileHelpId" />
                    <img src="{{ asset($category->image) }}" height="120px" width="120px" />
                  </div>


                  <div>
                    <label for="" class="form-label">Status</label> <br>
                    <label class="btn" id="off"><input type="radio"  name="status" value="1" @if($category->status == 1) checked  @endif><img src="../../../backend/images/button/switch-off.png" height="50px" /></label>
                    <label class="btn" id="on"><input type="radio"  name="status" value="0" @if($category->status == 0) checked  @endif><img src="../../../backend/images/button/switch-on.png" height="50px" /></label>
                  </div>
                    
                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        Submit
                    </button>
                    

                    
                </form>

        </div>
      </div>
    </div>
  </div>

 <!--  Content End Here -->



@endsection

@section('scripts')
<script>
    $(document).ready(function(){
        var status = {{ $category->status }}
        if(status == 0){
            $('#on').hide();
        }
        else{
            $('#off').hide();
        }
        
        
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
@endsection