(function ($) {

  var main = {

    init: function () {
      if ($(window).width() >= 768) {
        // Set the height of the main image element.
        this.setHeaderToWindowHeight();
        // Set scrolling fancy background position magic.
        this.fancyHeaderBackgroundScrollingMagic();
        this.clusterActiveEvents();
      }
      this.fadeInHeaderIntro();
      this.scrollTopEvent();
      this.scrollDownEvent();
    },

    scrollDownEvent: function () {
      $('.scroll-down-link').on('click', function (e) {
        e.preventDefault();
        $('html, body').animate({scrollTop: 700}, 1000);
      });
    },

    clusterActiveEvents: function () {
      $(window).on('scroll', function () {
        $('.cluster').each(function (i, el) {
          var offset = $(el).offset().top - $(window).scrollTop()
          if (offset > 50 && offset < $(window).height() - 300) {
            $(el).find('i').addClass('active');
          } else {
            $(el).find('i').removeClass('active');
          }
        });
      });
    },

    scrollTopEvent: function () {
      $('.scroll').on('click', function (e) {
        e.preventDefault();
        $('html, body').animate({scrollTop: 0}, 500);
      });
    },

    fadeInHeaderIntro: function () {
      if ($(window).width() >= 600) {
        setTimeout(function () {
          $('.section__header .header-maincopy.animate .intro').animate({opacity: 1}, 1000, function () {
            $(this).delay(1000).animate({marginLeft: 0}, 500, function () {
              $('.section__header .header-maincopy.animate .second').animate({opacity: 1}, 1000, function () {
                $('.section__header .header-subcopy.animate').animate({opacity: 1, marginTop: 5}, 1000);
                $('.section__header .scroll-down-link').animate({opacity: 1, marginTop: 0}, 1200);
              });
            });
          });
        }, 500);
      } else {
        $('.section__header .intro').css({opacity: 1, marginLeft: 0});
        $('.section__header .second').css({opacity: 1});
        $('.section__header h2').css({opacity: 1, marginTop: 5});
      }
    },

    fancyHeaderBackgroundScrollingMagic: function () {
      $(window).on('scroll', function () {
//        $('.section__header').css('background-position-y', $(window).scrollTop() * .5);
//        $('.section__header').height($(window).scrollTop() + $(window).height());
      });
    },

    setHeaderToWindowHeight: function () {
      $('.section__header').height($(window).height());
    }

  }

  $(document).on('ready', function () {
    main.init();
  });


/*
 * JS for Handling Contact Form 
 */

var messageDelay = 2000;  // How long to display status messages (in milliseconds)

// Init the form once the document is ready
$( init );

// Initialize the form

function init() {
  $(".statusMessage").hide();
  $('#contactForm').submit( submitForm );
  /*
  // Hide the form initially.
  // Make submitForm() the form's submit handler.
  // Position the form so it sits in the centre of the browser window.
  $('#contactForm').hide().submit( submitForm ).addClass( 'positioned' );

  // When the "Send us an email" link is clicked:
  // 1. Fade the content out
  // 2. Display the form
  // 3. Move focus to the first field
  // 4. Prevent the link being followed

  $('a[href="#contactForm"]').click( function() {
    $('#content').fadeTo( 'slow', .2 );
    $('#contactForm').fadeIn( 'slow', function() {
      $('#senderName').focus();
    } )

    return false;
  } ); 
  
  // When the "Cancel" button is clicked, close the form
  $('#cancel').click( function() { 
    $('#contactForm').fadeOut();
    $('#content').fadeTo( 'slow', 1 );
  } );  

  // When the "Escape" key is pressed, close the form
  /*$('#contactForm').keydown( function( event ) {
    if ( event.which == 27 ) {
      $('#contactForm').fadeOut();
      $('#content').fadeTo( 'slow', 1 );
    }
  } ); */
}  

/*
 * Submit the form via Ajax
 */
function submitForm() {
  var contactForm = $(this);

  // Are all the fields filled in?

  if ( !$('#senderName').val() || !$('#senderEmail').val() || !$('#message').val() ) {

    // No; display a warning message and return to the form
    $('#incompleteMessage').fadeIn().delay(messageDelay).fadeOut();
    contactForm.fadeOut().delay(messageDelay).fadeIn();

  } else {

    // Yes; submit the form to the PHP script via Ajax

    $('#sendingMessage').fadeIn();
    contactForm.fadeOut();

    $.ajax( {
      url: contactForm.attr( 'action' ) + "?ajax=true",
      type: contactForm.attr( 'method' ),
      data: contactForm.serialize(),
      success: submitFinished
    } );
  }

  // Prevent the default form submission occurring
  return false;
}


// Handle the Ajax response

function submitFinished( response ) {
  response = $.trim( response );
  $('#sendingMessage').fadeOut();

  if ( response == "success" ) {

    // Form submitted successfully:
    // 1. Display the success message
    // 2. Clear the form fields
    // 3. Fade the content back in

    $('#successMessage').fadeIn().delay(messageDelay).fadeOut();
    $('#senderName').val( "" );
    $('#senderEmail').val( "" );
    $('#message').val( "" );

    $('#content').delay(messageDelay+500).fadeTo( 'slow', 1 );

  } else {

    // Form submission failed: Display the failure message,
    // then redisplay the form
    $('#failureMessage').fadeIn().delay(messageDelay).fadeOut();
    $('#contactForm').delay(messageDelay+500).fadeIn();
  }
}


}(jQuery));