$( function () {
  console.log("document loaded");
  checkCookie();
  checkDNT();
  checkBrowser();

  $('#response-message').html('').hide();

  let request;
  $('#sendEmail').click(function (event) {
    console.log('clicked');

    // Prevent default posting of form - put here to work in case of errors
    event.preventDefault();

    let dataFields = [];
    dataFields.push('Token: ' + $('#token-id span').text());

    $('.data-point').each(function(key, value) {
      //console.log(value);
      //console.log(key + ': ' + value.innerText);
      dataFields.push( value.innerText );
    });
    //console.log(dataFields);

    // Fire off the request to /form.php
    request = $.ajax({
      type: 'POST',
      url: 'sendmail.php',
      data: {
        content: dataFields
      },
    });

    let responseMessage_container = $('#response-message');
    let sendEmail_button = $('#sendEmail');

    // Callback handler that will be called on success
    request.done(function (response, textStatus, jqXHR) {
      // Log a message to the console
      console.log(response);
      $(responseMessage_container).show().html(response['message']);

      if(response['status'] == 'success') {
        $(responseMessage_container).addClass('success');
        // disable the click action via class to not submit multiple times
        $(sendEmail_button).addClass('disabled').attr('disabled', 'disabled');
      } else {
        $(responseMessage_container).addClass('error');
      }
    });

    // Callback handler that will be called on failure
    request.fail(function (jqXHR, textStatus, errorThrown) {
      // Log the error to the console
      console.error("The following error occurred: " + jqXHR.responseText);
    });
  });

  $('#captureScreen').on('click',function (event) {
    console.log('capture clicked');
    html2canvas(document.querySelector("#capture")).then(canvas => {

      // Screenshot als Data URL erhalten
      let dataURL = canvas.toDataURL();

      // Den href des Lightbox-Links auf die Data URL setzen
      let lightboxLink = document.getElementById('lightbox-link');
      lightboxLink.href = dataURL;

      // Lightbox öffnen
      $.fancybox.open({
        src: dataURL,
        type: 'image',
        opts : {
          caption : 'Please download or save the image to your device',
          /*toolbar  : false,
          smallBtn : true,*/
          buttons : [
            'download',
            'close'
          ]
        }
      });

    });
  });


  $('#accept-mail-terms').on('click', function() {
    if (this.checked) {
      $('#sendEmail').removeClass('disabled');
    } else {
      $('#sendEmail').addClass('disabled');
    }
  });

});

$( window ).on("load", function () {
  console.log("window loaded");
});

$( window ).on('resize', function () {
  console.log("window resized");
  checkBrowser();
});



/*-------------------------------------
 * Functions
 *------------------------------------/

/*
 * Check if cookies can be set, then javascript is also enabled
 */
function checkCookie() {
  let cookieEnabled = navigator.cookieEnabled;
  console.log(cookieEnabled);

  if (!cookieEnabled) {
    document.cookie = "testcookie";
    cookieEnabled = document.cookie.indexOf("testcookie") != -1;
  }

  return cookieEnabled || showCookieFail();
}

function showCookieFail() {
  $('.js-cookie-status .value').html('False');
}

/*
 * DNT - Do Not Track
 */
function checkDNT() {
  let dnt = navigator.doNotTrack || window.doNotTrack || navigator.msDoNotTrack;
  if (dnt === "1" || dnt === "yes") {
    dnt = true;
  } else {
    dnt = false;
  }

  console.log(dnt);
  $('.do-not-track .value').html(dnt.toString());

  return dnt;
}


/*
 * Check Browser useragent, window and document width and height
 */
function checkBrowser() {
  let browser = {
    ua: navigator.userAgent
    /*width: window.innerWidth,
    height: window.innerHeight*/
  };

  // Size of browser viewport.

  let browser_size = [];
  browser_size['height'] = $(window).height();
  browser_size['width'] = $(window).width();

  // Size of HTML document (same as pageHeight/pageWidth in screenshot).
  let document_size = [];
  document_size['height'] = $(document).height();
  document_size['width'] = $(document).width();

  $('.window-size .value').html(browser_size['width'] + ' x ' + browser_size['height']);
  //$('.ua-string').html(browser.ua);

  return browser;
}
