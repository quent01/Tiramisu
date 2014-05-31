/**********************************/
/* JAVASCRIPT FILE FOR REQUIRE.JS */
/**********************************/


// création des alias/paths
requirejs.config({
	//By default load any module IDs from baseUrl
    baseUrl: 'templates/js',

    paths: {
        'jquery' : 'jquery.min',
        'instantclick' : 'instantclick/instantclick',
        'ractive' : 'ractive/ractive',
        'underscore' : 'underscore/underscore'
    },

    shim : {
    	'underscore' : {
    		exports : '_'
    	},
    	'ractive' : {
    		exports : 'Ractive'
    	},
    	'instantclick' : {
    		exports : 'InstantClick'
    	},
    	'jquery' : {
    		exports : '$'
    	}

    }
});

// on charge nos modules
require([
	'jquery',
	'CUSTOM/keypress_nav'
	], function($, Keypress_nav, InstantClick) {
	'use strict';
});

/* require(['instantclick', ], function(InstantClick) {
     // Callback executé une fois app.js chargé
     var instantclick = require(InstantClick)
     instantclick.init('mousedown');
});*/

// on charge le module commun
// require(['']);