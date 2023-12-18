$(function() {
    var uri = window.location.pathname.replace(URI, "");
    var breadcrumbs = {
        '/enquiry': "#menu-enquiry",
        '/user/me': '#menu-me',
        '/user': '#menu-user',
        '/mailer': '#menu-mailer'
    };
    
    for(var i in breadcrumbs) {
        if(uri.indexOf(i) === 0) {
            $(breadcrumbs[i]).addClass('active');
            break;
        }
    }
});