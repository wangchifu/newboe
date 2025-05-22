<form action="{{ route('admins.user_search') }}" method="post" id="this_form">
    @csrf
    帳號/姓名/學校/職稱/科室：<input type="text" name="want" required>
    <input type="submit" value="搜尋">
</form>
<script>
    var validator = $("#this_form").validate();
</script>