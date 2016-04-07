define(['require', 'exports', 'jquery', 'Front'], function (require, exports){
    $(function (){
        Cntysoft.Front.callApi('Utils', 'getProvinces', {
        }, function (response){
            if(response.status){
                $.each($('.province'), function (index, item){
                    appendDom($(item), response.data);
                });
                init();
            }
        },true);

        $('.province').change(function (){
            var $this = $(this);
            getChildArea($(this).val(), function (data){
                appendDom($this.siblings('.city'), data);
            });
            $this.siblings('.city').val(0);
            $this.siblings('.district').val(0);
        });

        $('.city').change(function (){
            var $this = $(this);
            if($(this).val() == 0){
                return false;
            }
            getChildArea($(this).val(), function (data){
                appendDom($this.siblings('.district'), data);
            });
        });


        function appendDom($dom, data){
            $($dom).find('option').not(':first').remove();
            for(var key in data) {
                $($dom).append('<option value="' + key + '">' + data[key] + '</option>');
            }
        }

        function getChildArea(code, callback){
            if(code == 0){
                return false;
            }
            Cntysoft.Front.callApi('Utils', 'getChildArea', {
                code : code
            }, function (response){
                if(response.status){
                    callback(response.data);
                }
            },true);
        }
        function init(){
            $.each($('.province'), function (index, item){
                var $this = $(item);
                if($this.attr('fh-value') > 0){
                    $this.val($this.attr('fh-value'));
                    if($this.siblings('.city').attr('fh-value') > 0){
                        getChildArea($this.attr('fh-value'), function (data){
                            appendDom($this.siblings('.city'), data);
                            $this.siblings('.city').val($this.siblings('.city').attr('fh-value'));
                        });
                    }
                    if($this.siblings('.district').attr('fh-value') > 0){
                        getChildArea($this.siblings('.city').attr('fh-value'), function (data){
                            appendDom($this.siblings('.district'), data);
                            $this.siblings('.district').val($this.siblings('.district').attr('fh-value'));
                        });
                    }
                }
            });
        }
        exports.getChildArea = getChildArea;
        exports.appendDom = appendDom;
        exports.init = init;
    });
});