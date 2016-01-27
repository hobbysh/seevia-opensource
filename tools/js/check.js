window.onload = function () {
    setInputCheckedStatus();

    getid("js-pre-step").onclick = function() {
        location.href="/tools/installs/welcome?lang=" + getAddressLang() + "&step=welcome";
    };
    getid("js-recheck").onclick = function () {
        location.href="/tools/installs/index?lang=" + getAddressLang();
    };
    getid("js-submit").onclick = function () {
        this.form.action="/tools/installs/setting?lang=" + getAddressLang() + "&ui=" + getid('userinterface').value;
    };
    getid("js-recheck-second").onclick = function () {
        location.href="/tools/installs/index?lang=" + getAddressLang();
    };
};