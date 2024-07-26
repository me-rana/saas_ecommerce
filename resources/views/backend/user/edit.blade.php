@extends('backend.layout.theme')
@section('main')
 <!--  Content Begin Here -->

    <div class="container-fluid">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title fw-semibold mb-4">User Edit</h5>

                
                <form action="{{ route('User Update') }}" method="post" enctype="multipart/form-data">
                    @csrf

                    <input name="hidden_id" value="{{ $user->id }}" hidden />

                    <div class="mb-3">
                        <label for="" class="form-label">Name</label>
                        <input type="text" name="name" id="" value="{{ $user->name ?? '' }}" class="form-control" placeholder="" aria-describedby="helpId"/>
                    </div>

                    <div class="mb-3">
                      <label for="" class="form-label">Choose file</label>
                      <input type="file" class="form-control" name="image" id="" placeholder="" aria-describedby="fileHelpId"/>
                      <img src="{{ asset($user->profile_photo_path ?? 'assets/backend/images/profile/user-1.jpg') }}" height="70px" width="70px" style="border-radius: 50%" />
                    </div>
                    

                    <div class="mb-3">
                        <label for="" class="form-label">Email</label>
                        <input type="text" name="email" value="{{ $user->email ?? '' }}" id="" class="form-control" placeholder="" aria-describedby="helpId"/>
                    </div>

                    <div class="mb-3">
                      <label for="" class="form-label">Phone</label>
                      <input type="text" name="phone" value="{{ $user->phone ?? '' }}" id="" class="form-control" placeholder="" aria-describedby="helpId"/>
                  </div>

                  
                    <div class="mb-3">
                        <label for="" class="form-label">User Type</label>
                        <select class="form-select" name="role_id" id="" @if($user->id == Auth::user()->id) disabled @endif>
                            <option selected>Select One</option>
                            @foreach($roles as $role)
                                <option value="{{$role->id}}">{{$role->name}}</option>
                            @endforeach
            
                        </select>
                    </div>
                    

                    <div class="mb-3">
                        <label for="" class="form-label">Password <span style="color:brown">[ *Enter to change password ] </span></label>
                        <input type="text" name="password" id="" class="form-control" placeholder="" aria-describedby="helpId"/>
                    </div>

                    <button type="submit" class="btn btn-primary"> Submit
                    </button>
                     
                </form>
                

    </div>
  </div>
</div>
</div>
 <!--  Content End Here -->

@endsection
