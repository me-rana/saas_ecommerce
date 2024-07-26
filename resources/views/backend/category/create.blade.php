@extends('backend.layout.theme')
@section('main')
@php
  $categories = App\Models\Category::where(['status' => 1])->get();
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
          <h5 class="card-title fw-semibold mb-4">Category Create</h5>

           
                  <!-- Top Start -->
                <div class="row justify-content-end align-item-center mb-5">
                    <div class="col-md-6 text-right">
                        <a href="{{ url('category/manage') }}"><button class="btn btn-primary">Manage</button></a>
                    </div>
                </div>
                <!-- Top End-->
                
                <form action="{{ url('category/store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="" class="form-label">Name</label>
                        <input type="text" name="name" id="" class="form-control" placeholder="" aria-describedby="helpId" />
                    </div>



                  <div class="mb-3">
                    <label for="" class="form-label">Parent Category</label>
                    <select
                      class="form-control"
                      name="parent_id"
                      id=""
                    >
                      <option value="" selected>Select one</option>
                      @foreach ($categories as $category)
                        @if ($category->master_id == null && $category->parent_id == null)
                          <option value="{{ $category->id }}">{{ $category->name }}</option>
                          @foreach ($subcategories as $subcategory)
                            @if ($subcategory->parent_id == $category->id)
                            <option value="{{ $subcategory->id }}">-- {{ $subcategory->name }}</option>
                            @endif
                          @endforeach
                        @endif
                      @endforeach
                      
                    </select>
                  </div>
                  

                  <div class="mb-3">
                    <label for="" class="form-label">Choose file</label>
                    <input type="file" class="form-control" name="image" id="" placeholder="" aria-describedby="fileHelpId" />
                  </div>
                  



                    <div>
                        <label for="" class="form-label">Status</label> <br>
                        <label class="btn" id="off"><input type="radio"  name="status" value="1" checked><img src="../../../backend/images/button/switch-off.png" height="50px" /></label>
                        <label class="btn" id="on"><input type="radio"  name="status" value="0"><img src="../../../backend/images/button/switch-on.png" height="50px" /></label>
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
     /*        $('#on').hide(); */
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
@endsection