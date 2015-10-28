var TipGroupMainOrderShow = function() {

    var Initialize = function(translations) {
        var orderDate = $('#orderDate').val();
        TipGroupMain.changeCurrency("currency",orderDate);
    };
        
    return {
        init: function(translations) {
            Initialize(translations);
        }
    };

}();