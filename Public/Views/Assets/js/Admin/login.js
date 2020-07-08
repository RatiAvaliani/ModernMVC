import Vea from "/ModernMVC/Public/Views/Assets/js/Modules/Vea.js";

class adminLogin {
    tags = {
        "loginForm" : "login-form"
    };
    ajax = {
        'login' : {
            "url"  : "login",
            "type" : "POST",
            "data" : {}
        }
    };

    validate () {
        this.submit();
         $(`#${this.tags.loginForm}`).validate({
            rules: {
                username : {
                    required: true,
                    minlength: 5
                },
                password : {
                    required: true,
                    minlength: 8
                }
            }
        });
    }

    checkLogin (username=null, password=null) {
        if (username === null || password === null) throw new Error('Username Or Password is null');

        this.ajax.login['data']['username'] = username;
        this.ajax.login['data']['password'] = password;

        $.ajax(this.ajax.login).done((data) => {
            let content = JSON.parse(data);

            if (content.status === 0) {
                (new Vea()).select(`#${this.tags.loginForm}`).append('p').addClass('error').text(content.message).reset().enter();
            } else {
                location.reload();
            }
        });

        return false;
    }

    submit () {
        $.validator.setDefaults({
            submitHandler : (_this) => {
                let username = $(_this).find('input[name="username"]').val();
                let password = $(_this).find('input[name="password"]').val();
                this.checkLogin(username, password);
            }
        });
    }
}

$(document).ready(() => {
    (new adminLogin()).validate();
});