<script>
function sw_confirm1(message,url) {
        Swal.fire({
            title: "操作確認",
            text: message,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText:"確定",
            cancelButtonText:"取消",
        }).then(function(result) {
            if (result.value) {
            window.location = url;
            }
            else {
                return false;
            }
        });
    }
    function sw_confirm2(message,id) {
        Swal.fire({
            title: "操作確認",
            text: message,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText:"確定",
            cancelButtonText:"取消",
        }).then(function(result) {
            if (result.value) {
            //document.getElementById(id).submit();
            check_required(id);
            }
            else {
                return false;
            }
        });
    }

    function sw_confirm3(message,fun) {
        Swal.fire({
            title: "操作確認",
            text: message,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText:"確定",
            cancelButtonText:"取消",
        }).then(function(result) {
            if (result.value) {
            if (typeof fun === 'function') {
                fun(); // 呼叫傳進來的 function                    
            }                
            }
            else {
                return false;
            }
        });
    }

    function sw_confirm4(button, msg, form_id, action_value) {
    // 先讓按鈕消失
        button.style.display = 'none';

        Swal.fire({
            title: msg,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: '確定',
            cancelButtonText: '取消',
        }).then((result) => {
            if (result.isConfirmed) {
                let form = document.getElementById(form_id);
                let hidden = document.createElement("input");
                hidden.type = "hidden";
                hidden.name = "form_action";
                hidden.value = action_value;
                form.appendChild(hidden);
            if (result.value) {
            //document.getElementById(id).submit();
                check_required(form_id,button);
            }
            else {
                return false;
            }
                //form.submit();
            } else {
                // 如果取消，要把按鈕再顯示回來
                button.style.display = '';
            }
        });
    }        

    function check_required(id,button) { 
        let form = document.getElementById(id); 
        let isValid = true; let missing = []; 
        // 記錄沒填的欄位名稱 
        $(form).find('input[required], select[required]').each(function() { 
            let val; 
            if ($(this).is('select')) { 
                val = $(this).find('option:selected').val(); 
            } else { 
                val = $(this).val().trim(); 
            } 
            let label = $(this).attr('id') ? $("label[for='" + $(this).attr('id') + "']").text().trim() : $(this).attr('name'); 
            if (val === '' || val === null) { 
                isValid = false; missing.push(label); $(this).css('border', '2px solid red'); 
            } else { 
                $(this).css('border', ''); 
            } 
        }); 
        if (!isValid) { event.preventDefault(); 
            let msg = "以下欄位尚未填寫：\r\n" + missing.join("\r\n"); button.style.display = ''; 
            sw_alert('錯誤！', msg); 
        } else { 
            form.submit(); 
        } 
    }

    function sw_alert(title,message){
        Swal.fire({
        title: title,
        text: message,
        icon: 'warning',
            });
        }
</script>