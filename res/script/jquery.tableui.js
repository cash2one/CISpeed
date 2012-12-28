(function($){
    $.fn.tableUI = function(options){
        var defaults = {activeRowClass:"active"};
        var opts = $.extend(defaults,options);
        this.each(function(){
            var thisTable = this;
            $(thisTable).find("tr").bind("mouseover",function(){
                $(this).addClass(opts.activeRowClass);
            });
            $(thisTable).find("tr").bind("mouseout",function(){
                $(this).removeClass(opts.activeRowClass);
            });
        });
    };
})(jQuery);