$(function () {
    var holdOnOptions = {
        message:"儲存中請稍候..."
    };

    var hasEditStack = false; 
    var intervalSeconds = 15000;
    var cxtId = $('input[name="cxt_id"]').val();
    var privateKey = $('input[name="private_key"]').val();
    var $content = $('input[type="radio"]').add('textarea');

    /**
     * {
     *   0_0: value,
     *   0_1: value,
     *   .
     *   .
     *   0_7: value,
     *   .
     *   .
     *   .
     *   5_10: value
     * }
     * 
     * @type {object}
     */
    var content = initContent();

    /*
    |--------------------------------------------------------------------------
    | 每 intervalSeconds 秒自動做刷新動作
    |--------------------------------------------------------------------------
    | 當使用者作答時, 每 intervalSeconds 秒自動會呼叫 updateCxt() 儲存作答結果.
    | updateCxt() 並非每次呼叫都會送出請求, 此函式會先檢查 hasEditStack,
    | hasEditStack 為 true 時才會送出請求.
    | 
    | hasEditStack 在使用者於頁面進行 "click" 動作時皆會刷新
    */
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    function initContent() {
        var content = {};

        $content.each(function () {
            if (typeof content[$(this).attr('name')] === 'undefined') {
                content[$(this).attr('name')] = false;
            }

            if (($(this).prop('checked') && $(this).is('input[type="radio"]'))
                || $(this).is('textarea')) {
                content[$(this).attr('name')] = $(this).val();
            }
        });

        return content;
    }

    function updateContent(name, value) {
        return content[name] = value;
    }

    function getContent() {
        return JSON.stringify(content);
    }

    function doUpdateJob() {
        return setTimeout(function () {
            return updateCxt();
        }, intervalSeconds);
    }

    function syncProcess(success, error) {
        let data = {
            "private_key": privateKey,
            "_method": 'put',
            "child_name": $('input[name="child_name"]').val(),
            "child_sex": $('select[name="child_sex"]').val(),
            "child_birthday": $('input[name="child_birthday"]').val(),
            "school_name": $('input[name="school_name"]').val(),
            "grade_num": $('input[name="grade_num"]').val(),
            "filler_name": $('input[name="filler_name"]').val(),
            "relation": $('input[name="relation"]').val(),
            "phone": $('input[name="phone"]').val(),
            "email": $('input[name="email"]').val(),
            "content": getContent()
        };

        $.ajax({
            type: 'post',
            url: '/analysis/r/i/cxt/' + cxtId,
            data: data,
            success: success,
            error: error
        });
    }

    function execSync(hasRecursive) {
        return syncProcess(function (data) {
            hasEditStack = false;
            
            if (true === hasRecursive) {
                return doUpdateJob();
            }

            HoldOn.close();   
        }, function (data) {
            console.log('Error:', data);
            
            if (true === hasRecursive) {
                return doUpdateJob();
            }

            HoldOn.close();
        });
    }

    function updateCxt() {
        return true === hasEditStack ? execSync(true) : doUpdateJob();
    }

    updateCxt();

    $('body').click(function () {
        hasEditStack = true;
    });

    $('#sync-result').click(function () {
        HoldOn.open(holdOnOptions);

        return execSync(false);
    });

    $content.change(function () {
        updateContent($(this).attr('name'), $(this).val());
    }).blur(function () {
        updateContent($(this).attr('name'), $(this).val());
    }).keyup(function () {
        updateContent($(this).attr('name'), $(this).val());
    });

    initContent();
});