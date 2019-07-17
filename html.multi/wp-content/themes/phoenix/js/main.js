var $ = jQuery.noConflict();

/*Empty p tags fix*/
var p = document.querySelectorAll( 'p:empty' );
for( var i = p.length - 1; i > -1; i-- ) {
	p[i].parentNode.removeChild( p[i] );
}

$( document ).ready( function(){


	$(".flexnav").flexNav();


	if ( $('nav.mm-menu').length === 0 ) {
		addSiteNavigation();
	}

	callAutoCalculate();

});

/*Popover*/
$( "[rel=popover]" ).popover({
	"placement": "auto",
	"container": "body",
	"html":true
});


$( 'body' ).on( 'click', function (e) {
	$( '[rel=popover]' ).each( function () {
		if (!$( this ).is( e.target ) && $( this ).has( e.target ).length === 0 && $( '.popover' ).has( e.target ).length === 0) {
			$( this ).popover( 'hide' );
		}
	});
});

/*Tooltip*/
$( "[rel=tooltip]" ).tooltip();


/* Add Select Option for Main Navigation */

function addSiteNavigation() {
	var $menu_select = $( "<select />" ); 
	$( "<option />", {"selected": "selected", "value": "", "text": "Site Navigation"} ).appendTo( $menu_select );
	
	$menu_select.appendTo( "nav[role='navigation']" );
	$( "nav[role='navigation'] ul li a" ).each( function() {
		var menu_url = $( this ).attr( "href" );
		var menu_text = $( this ).text();
		if ( $( this ).parents( "li" ).length == 2 ) { menu_text = '- ' + menu_text; }
		if ( $( this ).parents( "li" ).length == 3 ) { menu_text = "-- " + menu_text; }
		if ( $( this ).parents( "li" ).length > 3 ) { menu_text = "--- " + menu_text; }
		$( "<option />", { "value": menu_url, "text": menu_text } ).appendTo( $menu_select )
	})
	field_id = "nav[role='navigation'] select";
	$( field_id ).change( function() {
		value = $( "nav[role='navigation'] select option:selected" ).val();
		window.location = value; 
	});

}

/*Auto-height segement scripts*/

function fullHeight() {

	var sumSegment = 0;

	$( "#header > div" ).each( function() {
		sumSegment += ( $( this ).outerHeight() );
	});

	//console.log(sumSegment);

	t = $( window ).height() - sumSegment;
	e = $( ".intro" ).outerHeight();

	if ( $( "#header" ).hasClass( "navbar-fixed-top" ) ) {  

		$( "body" ).css( { paddingTop:sumSegment } );

	} else {
		$( "body" ).css( { paddingTop:0 } );
	}

	$( ".auto-height" ).css( { minHeight:t } );

	$( ".intro" ).css( { top:( t*0.4 - e*0.5 ) } ); 
};


function autoWrapNavigation(){
	var total = 0;

	$( "header nav" ).find( "li" ).each( function() {
		total += ( $( this ).width() ) ;
	})

	var container = $( "header nav ul" ).parent().width();
	if( total >= container ) {
		$( "header nav select" ).show();
		$( "header nav ul" ).hide();
	}

}

function removeNavFixTopMobile() {
	if( $( window ).width() < 768 ) {
		if (  true == $( "#header" ).hasClass( "navbar-fixed-top" ) ) {
			$( "#header" ).removeClass( "navbar-fixed-top" ).addClass( "_navbar-fixed-top" );
		}
	} else {
		if ( true == $( "#header" ).hasClass( "_navbar-fixed-top" ) ) {
			$ ( "#header" ).removeClass( "_navbar-fixed-top" ).addClass( "navbar-fixed-top" );
		}
	}
}


function footerHeight(){

	var sumSegment = 0;

	$( "body > div > div .page-wrap +  footer > div" ).each( function() {
		sumSegment += ( $( this ).outerHeight() );
	});


	//footer_h = $("body > div > div .page-wrap +  footer").outerHeight();
	footer_h = sumSegment;

	$( "body > div > div .page-wrap +  footer" ).css( { height:footer_h } );
	$( ".page-wrap" ).css( { marginBottom:-footer_h } );

	$( 'head' ).append( "<style>.page-wrap:after{ height:" + footer_h + "px;z-index: 99; }</style>" );
}

jQuery.fn.exists = function(){ return jQuery( this ).length > 0; }

function callAutoCalculate(){
	removeNavFixTopMobile();
	fullHeight();
	footerHeight();
	//autoWrapNavigation();
}

var resizeTimer;
$( window ).resize( function() {
	clearTimeout( resizeTimer );
	resizeTimer = setTimeout( callAutoCalculate, 100 );
});

/* Add Twitter Bootstrap classes to Gravity Forms */
$(document).bind( 'gform_post_render', function(){
  $( '.button' ).removeClass( 'button' ).addClass( 'btn btn-primary' );
  $( '.gform_button' ).addClass( 'controls btn' );
/* Remove function 'removeClass' from .gform_next_button and .gform_page
According to gravityform have new class 'gform_next_button' at version 2.3.3.10 
https://trello.com/c/BoDuTJvm/3891-bug-gform-automatically-update-turned-on-for-some-new-clients
*/
$( '.gform_next_button' ).addClass( 'pull-right' );
$( '.gform_page' ).addClass( 'row' );
$( '.gform_page_fields' ).addClass( 'col-md-12' );
$( '.validation_error' ).removeClass( 'validation_error' ).addClass( 'alert alert-error' );
$( '.gform_body input[type="text"]' ).addClass( 'form-control' );
$( '.gform_body input[type="email"]' ).addClass( 'form-control' );
$( '.gform_body input[type="tel"]' ).addClass( 'form-control' );
$( '.gform_body input[type="password"]' ).addClass( 'form-control' );
$( '.gform_body select' ).addClass( 'form-control' );
$( '.gform_body textarea' ).addClass( 'form-control' );
$( 'input#recaptcha_response_field' ).removeClass( 'form-control' );
});

/* Remove <br> in hidden field at Gravity Forms*/
$(document).ready(function(){
	$( '.gform_footer' ).find( 'br' ).remove();
});
