window._ = require('lodash');

try {
    window.Popper = require('popper.js').default;
    window.$ = window.jQuery = require('jquery');

    require('bootstrap');
} catch (e) {}

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

//Interceptors - Request
window.axios.interceptors.request.use(req => {
    req.headers['Content-Type'] = 'application/json';
    req.headers['X-CSRF-TOKEN'] = $('meta[name="csrf-token"]').attr('content');
    return req;
});

//Interceptors - Response
window.axios.interceptors.response.use(function (response) {
    return response;
}, function (error) {
    // if (error.response.status === 404) {
    //     window.location.href = '/';
    // } else {
    //     return Promise.reject(error);
    // }
    return Promise.reject(error);
});
