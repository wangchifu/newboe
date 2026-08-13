@extends('layouts.app')

@section('title','變更職稱')

@section('header')
<header class="py-5 bg-light border-bottom mb-4">

</header>
@endsection

@section('content')
<div class="col-lg-4 mx-auto">
    <div class="card">
        <div class="card-header text-center">
            <h3 class="py-2">
                變更職稱
            </h3>
        </div>
        <div class="card-body">
            <form action="{{ route('update_title') }}" method="post" onsubmit="return false" id="this_form">
                @csrf
                @method('patch')
                <input type="hidden" id="title" name="title" value="{{ auth()->user()->title }}">

                <div id="single_mode">
                    <div class="form-group">
                        <label for="title_select">請選擇正確的職稱</label>
                        <select class="form-control" id="title_select" tabindex="1">
                        @foreach($title_array as $k => $v)
                            <option value="{{ $v }}" {{ (auth()->user()->title == $v) ? 'selected' : '' }}>{{ $v }}</option>
                        @endforeach
                        </select>
                    </div>
                    @if(!in_array(auth()->user()->code, ['079999', '079998']) && empty(auth()->user()->section_id))
                    <button type="button" class="btn btn-info btn-sm mt-2" onclick="toggleMultiMode()">多職稱兼任</button>
                    @endif
                </div>

                <div id="multi_mode" style="display:none;">
                    <label>請依兼任順序勾選職稱</label>
                    <div class="list-group mt-2" id="checkbox_list">
                    @foreach($title_array as $k => $v)
                        <label class="list-group-item">
                            <input type="checkbox" class="form-check-input me-2 title_checkbox" value="{{ $v }}"> {{ $v }}
                        </label>
                    @endforeach
                    </div>
                    <div class="mt-2">
                        <strong>預覽：</strong><span id="preview" class="text-primary">（請勾選職稱）</span>
                    </div>
                    <button type="button" class="btn btn-secondary btn-sm mt-2" onclick="toggleSingleMode()">返回單一職稱</button>
                </div>

                <hr>
                <button type="submit" class="btn btn-primary btn-sm" onclick="submitForm()">送出</button>
            </form>
            @include('layouts.errors')
        </div>
    </div>
</div>

<script>
    var selectedTitles = [];

    document.getElementById('title_select').addEventListener('change', function() {
        document.getElementById('title').value = this.value;
    });

    document.querySelectorAll('.title_checkbox').forEach(function(cb) {
        cb.addEventListener('change', function() {
            if (this.checked) {
                selectedTitles.push(this.value);
            } else {
                selectedTitles = selectedTitles.filter(function(t) { return t !== cb.value; });
            }
            updatePreview();
        });
    });

    function updatePreview() {
        var preview = document.getElementById('preview');
        if (selectedTitles.length === 0) {
            preview.textContent = '（請勾選職稱）';
            document.getElementById('title').value = '';
        } else {
            var result = selectedTitles.join('兼');
            preview.textContent = result;
            document.getElementById('title').value = result;
        }
    }

    function toggleMultiMode() {
        document.getElementById('single_mode').style.display = 'none';
        document.getElementById('multi_mode').style.display = 'block';
        selectedTitles = [];
        document.querySelectorAll('.title_checkbox').forEach(function(cb) { cb.checked = false; });
        updatePreview();
    }

    function toggleSingleMode() {
        document.getElementById('multi_mode').style.display = 'none';
        document.getElementById('single_mode').style.display = 'block';
        document.getElementById('title').value = document.getElementById('title_select').value;
    }

    function submitForm() {
        var title = document.getElementById('title').value;
        if (!title) {
            alert('請選擇職稱');
            return;
        }
        sw_confirm2('確定？', 'this_form');
    }
</script>
@endsection
