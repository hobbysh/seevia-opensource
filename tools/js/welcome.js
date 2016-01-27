window.onload = function () {
    setInputCheckedStatus();

    var agree = getid("js-agree");
    var submit = getid("js-submit");
    submit.disabled=!agree.checked;
    agree.onclick = function () {
        submit.disabled=!this.checked;
    };
    submit.onclick=function () {
        this.form.action = "/install/tests/index?lang=" + getAddressLang();
    };
};