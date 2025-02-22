jQuery('document').ready(function($) {
    const url = window.wpmLoginPageAdmin.assets_url + 'images/logo-yellow.png';
    const homeUrl = window.wpmLoginPageAdmin.home_url;
    const loginTitle = window.wpmLoginPageAdmin.login_title;
    const loginDesc = window.wpmLoginPageAdmin.login_desc;
    const contentRight = `<div class='login-page-right'>
        <div class='login-page-right-inner'>
            <img width="200px;" alt="WPMINERS" src='${url}'>
            <h1>${loginTitle}</h1>
            <p>
            ${loginDesc}
            </p>
        </div>
        <div>
            <a style="text-decoration:none;font-size:16px; outline:none;" class="wpm-back-home-url" href="${homeUrl}">
                <span class="dashicons dashicons-admin-home">
                </span> Back to home
            </a>
        </div>
    </div>`;
    $('body').append(contentRight);

}, (jQuery))