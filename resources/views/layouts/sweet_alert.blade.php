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

        function check_required(id) {
            let form = document.getElementById(id);
            let isValid = true;
            
            // 只檢查指定 form 內的 required input 和 textarea
            $(form).find('input[required], textarea[required]').each(function() {
                if ($(this).val().trim() === '') {
                    isValid = false;
                    $(this).css('border', '2px solid red'); // 標示未填寫的欄位
                } else {
                    $(this).css('border', ''); // 清除標示
                }
            });
            
            if (!isValid) {
                event.preventDefault(); // 阻止表單提交
                sw_alert('錯誤！', '請填寫所有必填欄位！');
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