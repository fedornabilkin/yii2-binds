;$(document).ready(function () {
    $('button[data-request="ajax"]').on('click', function () {
        AjaxRequest($(this));
        return false;
    });
});

// --------- Prototype Response -----------
function StatusSar(element, data) {
    AjaxResponse.apply(this, arguments);
}
StatusSar.prototype = Object.create(AjaxResponse.prototype); // IE
StatusSar.prototype.constructor = StatusSar;

StatusSar.prototype.buttonPrepare = function(){
    for(var item in this.data){
        if (!this.data.hasOwnProperty(item)) continue;
        this.form[item] = this.data[item];
    }
    return true;
};

StatusSar.prototype.after = function(){
    AjaxResponse.prototype.after.apply(this);
    $el = $(this.element);
    $el.parent().find('button').removeClass('active');
    $el.addClass('active');
};