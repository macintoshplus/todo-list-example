import {useRegistration} from '@web-auth/webauthn-helper';

// We want to register new authenticators
const register = useRegistration({
    actionUrl: '/register',
    optionsUrl: '/register/options'
});
let userinfo = document.getElementById('user_info');
register({
    username: userinfo.dataset.name,
    displayName: userinfo.dataset.display
})
    .then(function(response){
        console.log('Registration success', response);
        setTimeout(function () {
            window.location='/user/key/register-success';
        }, 800)
    })
    .catch((error)=> console.log('Registration failure', error))
;
