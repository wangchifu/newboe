@extends('layouts.app_clean')

@section('title','編輯學校帳號')

@section('content')
<div class="col-lg-12 mx-auto">    
    <div class="card shadow-sm my-3">
        <div class="card-header">            
            <img class="card-img-top img-responsive" src="{{ asset('images/small/school_power.png') }}">
        </div>
        <div class="card-body">
            <form action="{{ route('school_acc.update', $user->id) }}" method="POST" onsubmit="return false" id="this_form">
                @csrf
                @method('POST')
                <table class="table table-hover">
                    <tr>
                        <td>
                            {{ $user->name }}({{ $user->username }})
                        </td>
                        <td>
                            {{ $user->title }}
                        </td>
                        <td>
                            <?php
                            $user_power = check_a_user($user->code,$user->id);

                            $a_checked = ($user_power)?"checked":null;
                            ?>
                            <input type="checkbox" name="a_user" id="a_user" {{ $a_checked }} onclick="check_another()"> <label for="a_user">管理+審核權</label>
                        </td>
                        <td>
                            <?php
                            $user_power = check_b_user($user->code,$user->id);

                            $b_checked = ($user_power)?"checked":null;
                            ?>
                            <input type="checkbox" name="b_user" id="b_user" {{ $b_checked }}> <label for="b_user">簽收+填報權</label>
                        </td>                        
                        <td>
                            <button type="button" id="closeVeno" class="btn btn-secondary btn-sm">不存離開</button>
                            <button class="btn btn-success btn-sm" onclick="sw_confirm2('確定送出？','this_form')">儲存</button>
                        </td>
                    </tr>
                </table>
            </form>                     
        </div>
    </div>
</div>
<script>
    function check_another(){
        if($('#a_user').prop('checked')){
            $('#b_user').prop("checked", true);
        }
    }
</script>
@endsection