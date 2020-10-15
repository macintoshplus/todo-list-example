import {useLogin} from '@web-auth/webauthn-helper';

const login = useLogin({
    actionUrl: '/security/authentication/login',
    optionsUrl: '/security/authentication/options'
});

let userinfo = document.getElementById('user_info');

login({
    username: userinfo.dataset.name
})
    .then(function (response) {
        console.log('Login success');
        document.getElementById('success').style.display = 'block';
        setTimeout(function () {
            window.location='/';
        }, 1000)
    })
    .catch((error) => console.log('Login failure'))
;
