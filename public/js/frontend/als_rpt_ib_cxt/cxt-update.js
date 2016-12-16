$(function () {
    var holdOnOptions = {
        message:"储存中请稍候..."
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

    var alertPools = [];

    function isDisabled() {
        var isDisabled = false;
        var total = 0;
        alertPools = [];
        
        $radioDiv = $('fieldset');

        $radioDiv.each(function () {
            var $div = $(this);
            var unckeckedLength = $div.find('input[type="radio"]:not(:checked)').length;
            var fullLength = $div.find('input[type="radio"]').length;

            total += fullLength;

            if (0 < fullLength && unckeckedLength === fullLength) {
                alertPools.push($(this).closest('tr').find('td').eq(2).text());

                isDisabled = true;
            }
        });

        if (0 === total) {
            isDisabled = false;
        }

        return isDisabled;
    }

    $('#_submit').click(function () {
        if (true === isDisabled()) {
            alert('您還有尚未作答的題目喔!' + "\n" + alertPools.join("\n------------------------\n"));

            return false;
        } else {
            $('input[name="content"]').val(getContent());

            return $('form').submit();
        }

        return false;
    });

    /*
    |--------------------------------------------------------------------------
    | 每 intervalSeconds 秒自动做刷新动作
    |--------------------------------------------------------------------------
    | 当使用者作答时, 每 intervalSeconds 秒自动会呼叫 updateCxt() 储存作答结果.
    | updateCxt() 并非每次呼叫都会送出请求, 此函式会先检查 hasEditStack,
    | hasEditStack 为 true 时才会送出请求.
    | 
    | hasEditStack 在使用者于页面进行 "click" 动作时皆会刷新
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
        $('input[name="content"]').val(getContent());

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
            "content": $('input[name="content"]').val()
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