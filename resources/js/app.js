require('./bootstrap');
window.Noty = require('noty');
Noty.overrideDefaults({
    theme: 'bootstrap-v4',
    type: 'warning',
    layout: 'center',
    modal: true,
});
// Make 'VinylShop' accessible inside the HTML pages
import VinylShop from "./vinylShop";
window.VinylShop = VinylShop;
// Run the hello() function
VinylShop.hello()

$('nav i.fas').addClass('fa-fw mr-1');

$('body').tooltip({
    selector: '[data-toggle="tooltip"]',
    html : true,
}).on('click', '[data-toggle="tooltip"]', function () {
    // hide tooltip when you click on it
    $(this).tooltip('hide');
});
