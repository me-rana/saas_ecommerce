@extends('backend.layout.theme')
@section('main')
    <div class="container-fluid">
        <div class="card py-2 px-3">
            <h4 class="text-center text-dark"><strong>Roles and Permissions</strong></h4>
            <p class="text-center" style="font-size: 10px; color:red">[* Roles and Permission are created form Seeder,Yet updating wasn't implemented]</p>
               <div class="row justify-content-center text-aligns-center">
                    @foreach($roles as $role)
                    <div class="col-md-6">
                        <h5 class="text-center"><strong><u>{{ $role->name }}</u></strong></h5>
                        @php
                            $arr = [];
                            $data = Spatie\Permission\Models\Permission::get(); 
                            $hasPermissions = DB::table('role_has_permissions')->where('role_id',$role->id)->pluck('permission_id');
                            foreach($hasPermissions as $item){
                                $arr[] = $item;
                            }
                        @endphp
                        <div class="row">
                        @foreach($data as $item)
                        
                        <div class="col-6">
                            <form action="{{ route('Assigned Permission') }}" method="post">
                            
                                @csrf
                                <input type="hidden" name="role" value="{{ $role->id }}">
                                <input type="hidden" name="permission_id" value="{{ $item->id }}">
                                <input type="checkbox" name="permission" value="{{ $item->id }}" class="permission_id" id="" @if($role->name == 'Super-Admin') checked disabled @else @if(in_array($item->id, $arr)) checked @endif @endif>
                                <label for="permissions">{{ $item->name }}</label>
                            </form>
                        </div>
                        @endforeach 
                    </div>
                    </div>
                    @endforeach
               </div>
            
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function(){
            $('.permission_id').on('click',function(){
                $(this).closest('form').submit();
            });
        });
    </script>
@endsection
