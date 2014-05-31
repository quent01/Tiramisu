/**********************************/
/* JAVASCRIPT FILE FOR REQUIRE.JS */
/**********************************/


// création des alias/paths
requirejs.config({
	//By default load any module IDs from baseUrl
    baseUrl: 'templates/js',

    paths: {
        jquery : 'jquery.min',
        // instantclick : 'instantclick/instantclick.min',
        ractive : 'ractive/ractive'
    }
});

// on charge nos modules
require(['jquery', ], function($) {

});

// require(['instantclick', ], function(InstantClick) {
//     // Callback executé une fois app.js chargé
//     var instantclick = require(InstantClick)
//     instantclick.init('mousedown');
// });

// on charge le module commun
// require(['']);