<table>
    <tr>
        <td>
            <form action="{{ route('school_report.search') }}" method="post" id="this_form">
                @csrf
                編號/名稱/說明：<input type="text" maxlength="8" name="want" required>
                <input type="submit" value="搜尋填報">
            </form>
        </td>
    </tr>
</table>