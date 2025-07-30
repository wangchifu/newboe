<form action="{{ route('admins.user_search') }}" method="post">
    @csrf
    <table>
        <tr>
            <td>
                帳號/姓名/學校/職稱/科室：
            </td>
            <td>
                <input type="text" name="want" class="form-control" required>
            </td>
            <td>
                <input class="btn btn-primary btn-sm" type="submit" value="搜尋">                            
            </td>                        
        </tr>
    </table>    
</form>