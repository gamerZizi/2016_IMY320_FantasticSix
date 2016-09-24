$(function() {
  $( "[data-role='navbar']" ).navbar();
  $( "[data-role='header'], [data-role='footer']" ).toolbar();
  $( "[data-role='panel']" ).panel();

  $("#login-form").submit(function (evt) {
    evt.preventDefault();
    signOn();
  });

  $("#to-news").click(function (evt) {
    evt.preventDefault();
    loadNews();
  });

  $("#to-events").click(function (evt) {
    evt.preventDefault();
    loadEvents();
  });

  $("#to-logout").click(function (evt) {
    evt.preventDefault();
    signOut();
  });
});

$(document).ready(function(){
  // Update the contents of the toolbars
  $( document ).on( "pagecontainerchange", function() {

    // Remove active class from nav buttons
    $( "[data-role='navbar'] a.ui-btn-active" ).removeClass( "ui-btn-active" );
    // Each of the four pages in this demo has a data-title attribute
    // which value is equal to the text of the nav button
    // For example, on first page: <div data-role="page" data-title="Info">
    var current = $( ".ui-page-active" ).jqmData( "title" );
    // Change the heading
    $( "[data-role='header'] h1" ).text( current );
    // Hide Navs on Login page
    if (current === "Login") {
      $( "[data-role='header'], [data-role='footer']" ).css("display", "none");
    } else {
      $( "[data-role='header'], [data-role='footer']" ).css("display", "block");
    }
    // Add active class to current nav button
    $( "[data-role='navbar'] a" ).each(function() {
      if ( $( this ).text() === current ) {
        $( this ).addClass( "ui-btn-active" );
      }
    });

    // If logged in hide login page
    if ($("#logged-in").val() === "true") {
      $("#login").css("display", "none");
    } else {
      $("#login").css("display", "block");
    }

    $("#main-menu-panel").click(function(){
      $(this).panel("close");
    });
  });
});


var URL = "http://196.249.57.169/2016_IMY320_FantasticSix/web/wp-json/";
// var URL = "http://amanzimtoti.byethost33.com/wp-json";

function signOn() {
  if ( $("#uname").val() === "" || $("#pword").val() === "" ) {
    signOut();
    return;
  }

  // Load spinning loading icon
  displayLoadingSpinner({ message: "Logging in, please wait." });
  $("#logged-in").val("true");
  var paramObj = {
    type      : "GET",
    route     : "amanzimtoti-mobile/v1/auth/"+encodeURI($("#uname").val())+"/"+encodeURI($("#pword").val()),
    callback  : function(response) {
      if (response.ID === 0) {
        $("#login-error").html("Incorrect login details supplied.").show();
        $.mobile.loading( "hide" );
        return;
      }
      $("#login-error").html("").hide();
      createUserProfileSection(response.data);
      // window.location.href = "#news";
      loadNews();
    }
  };
  execJSONRESTRequest(paramObj, false);
}

function signOut() {
  // Load spinning loading icon
  displayLoadingSpinner({ message: "Logging out, bye!." });

  var paramObj = {
    type      : "GET",
    route     : "amanzimtoti-mobile/v1/logout",
    callback  : function(response) {
      $("#uid, #cat, #org").remove();
      $("#main-profile").html("");

      $("#uname, #pword").val("");
      $("#logged-in").val("false");
      window.location.href = "#login";
      navigator.app.exitApp();
    }
  };
  execJSONRESTRequest(paramObj, true);
}

function loadEvents() {

  if ($("#events-loaded").val() === "true") { window.location.href = "#events"; return; }

  // Load spinning loading icon
  displayLoadingSpinner({ message: "Loading events..." });
  var eventURL = "amanzimtoti-mobile/v1/events";
  eventURL += ($("#cat").val() == "0" && $("#org").val() == "0") ? "" : "/"+$("#cat").val()+"/"+$("#org").val();

  var paramObj = {
    type      : "GET",
    route     : eventURL,
    callback  : function(response) {
      var list = '<ul id="events-ul" data-role="listview" data-filter="true" data-filter-placeholder="Search Event..." date-inset="true">';
      $.each(response, function(index, obj){
        var date = obj.postmeta.event_start_date.split(" ");
        list  +=    '<li>';
        list  +=      '<a href="#event" onclick="loadEventByID(event, '+ obj.ID +', \''+ obj.post_title.trim() +'\');" class="ui-btn">';
        list  +=        '<div class="date-cl">';
        list  +=          '<div>';
        list  +=             '<span class="day-cl">' + date[0] + '</span>';
        list  +=             '<span class="month-cl">' + date[1] + '</span>';
        list  +=             '<span class="year-cl">' + date[2].replace(",", "") + '</span>';
        list  +=          '</div>';
        list  +=        '</div>';
        list  +=        '<div class="title-cat-cl">';
        list  +=          '<h3>' + obj.post_title.trim() + '</h3>';
        list  +=          '<p><b>category: </b><i>' + obj.event_category.trim() + '</i></p>';
        list  +=        '</div>';
        list  +=      '</a>';
        list  +=    '</li>';
      });
      list    += '</ul>';
      $("#calendar_wrap").html(list);
      $("#events-loaded").val("true");
      window.location.href = "#events";
    }
  };
  execJSONRESTRequest(paramObj, true);
}

function loadEventByID(evt, eventID, title) {
  evt = evt || window.event;
  evt.preventDefault();

  // Load spinning loading icon
  displayLoadingSpinner({ message: title });
  // $("#event").attr("data-title", title);

  var paramObj = {
    type      : "GET",
    route     : "amanzimtoti-mobile/v1/event/"+eventID,
    callback  : function(response) {
      var startDateTime = response.postmeta.event_start_date.split(",");
      var endDateTime = response.postmeta.event_end_date.split(",");
      var content = '<div class="ui-bar ui-bar-a top">';
      content    +=   '<h3>' + response.post_title.trim() + '</h3>';
      content    += '</div>';
      content    += '<div class="ui-body ui-body-a without-bottom">';
      content    +=   '<p>';
      content    +=     '<h3>Details:</h3>';
      content    +=     '<b>Date: </b>' + startDateTime[0].trim() + "<br />";
      content    +=     '<b>Time: </b>' + startDateTime[1].trim() + " - " + endDateTime[1].trim() + "<br />";
      content    +=     '<b>Duration: </b>' + response.postmeta.event_duration + " hrs" + "<br />";
      if (response.postmeta.event_currency_symbol !== "" && response.postmeta.event_cost !== "") {
        content    +=     '<b>Entrance Fee: </b>' + response.postmeta.event_currency_symbol + " " + response.postmeta.event_cost + "<br />";
      }
      if (response.postmeta.event_organizer != null && response.postmeta.event_organizer !== "") {
        content    +=     '<b>Organized By: </b>' + response.postmeta.event_organizer.trim() + "<br />";
      }
      if (response.postmeta.venue_address != null) {
        content    +=     '<h3>Venue:</h3>';
        content    +=     '<b>Address: </b>' + response.postmeta.venue_address + "<br />";
        if (response.postmeta.venue_city != null) {
          content    +=     '<b>City: </b>' + response.postmeta.venue_city + "<br />";
        }
        if (response.postmeta.venue_country != null) {
          content    +=     '<b>Country: </b>' + response.postmeta.venue_country + "<br />";
        }
        if (response.postmeta.venue_province != null) {
          content    +=     '<b>Province: </b>' + response.postmeta.venue_province + "<br />";
        }
        if (response.postmeta.venue_zip != null) {
          content    +=     '<b>Postal Code: </b>' + response.postmeta.venue_zip + "<br />";
        }
      }
      content    +=   '</p>';
      content    += '</div>';
      $("#event div.ui-content").html(content);
      // $("#event").attr("data-title", response.post_title.trim());
      window.location.href = "#event";
    }
  };
  execJSONRESTRequest(paramObj, true);
}

function loadNews() {
  if ($("#news-loaded").val() === "true") { window.location.href = "#news"; return; }

  // Load spinning loading icon
  displayLoadingSpinner({ message: "Loading news..." });

  var paramObj = {
    type      : "GET",
    route     : "amanzimtoti-mobile/v1/news",
    callback  : function(response) {
      var list =  '<ul data-role="listview" data-filter="true" data-filter-placeholder="Search News..." data-inset="true">';
      var dateTemp = "";
      $.each(response.news, function(index, obj){
        if (dateTemp !== obj.post_modified) {
          list  +=    '<li data-role="list-divider">' + obj.post_modified + '</li>';
          dateTemp = obj.post_modified;
        }
        list  +=    '<li>';
        list  +=      '<a href="#news-single" onclick="loadNewsByID(event, '+ obj.ID +', \''+ obj.post_title.trim() +'\');" class="ui-btn">';
        list  +=      '<p>' + obj.post_title.trim() + '</p>';
        //list  +=      '<span>' + obj.post_modified + '</span>';
        list  +=      '</a>';
        list  +=    '</li>';
      });
      list    += '</ul>';
      $("#news").html(list);
      $("#news-loaded").val("true");
      window.location.href = "#news";
    }
  };
  execJSONRESTRequest(paramObj, true);
}

function loadNewsByID(evt, id, title) {
  evt = evt || window.event;
  evt.preventDefault();

  // Load spinning loading icon
  displayLoadingSpinner({ message: title });
  // $("#news-single").attr("data-title", title);

  var paramObj = {
    type      : "GET",
    route     : "amanzimtoti-mobile/v1/news/"+id,
    callback  : function(response) {
      var content = '<div class="ui-bar ui-bar-a top">';
      content    +=   '<h3>' + response.news.post_title.trim() + '</h3>';
      content    +=   '<span><b>Updated: </b>' + response.news.post_modified + '</span>';
      content    += '</div>';
      content    += '<div class="ui-body ui-body-a without-bottom">';
      content    +=   '<p>' + response.news.post_content + '</p>';
      content    += '</div>';

      $("#news-content").html(content);
      // $("#news-single").attr("data-title", response.news.post_title.trim());
      window.location.href = "#news-single";
    }
  };
  execJSONRESTRequest(paramObj, true);
}

function execJSONRESTRequest(paramObj, hideSpinner) {
  $.ajax({
    dataType  : "json",
    type      : paramObj.type,
    url       : URL + paramObj.route
  })
  .done(function(response){
    paramObj.callback(response);
  })
  .fail(function(){

  })
  .always(function(){
    if (hideSpinner) {
      $.mobile.loading( "hide" );
    }
    $( "#main-menu-panel" ).panel( "close" );
  });
}

function displayLoadingSpinner(params) {
  // Load spinning loading icon
  $.mobile.loading("show", {
    text        : params.message,
    textVisible : true,
    theme       : "b",
    textonly    : false,
    html        : ""
  });
  // Prevent form submit
  // event.preventDefault();
}

function createUserProfileSection(data) {
  var hiddenInformation = '<input type="hidden" id="uid" value="'+ data.ID +'" />';
  var cat = (data.amanzimtoti_member_info.event_categories.length > 0) ? data.amanzimtoti_member_info.event_categories.toString().replace(",", "-") : "0";
  hiddenInformation +=    '<input type="hidden" id="cat" value="'+ cat +'" />';
  var org = (data.amanzimtoti_member_info.event_organizers.length > 0) ? data.amanzimtoti_member_info.event_organizers.toString().replace(",", "-") : "0";
  hiddenInformation +=    '<input type="hidden" id="org" value="'+ org +'" />';

  var profileSection =  '<div id="profile-pic">' +
                          '<img src="img/default.jpg" alt="" />' +
                        '</div>' +
                        '<h3>'+ data.display_name +'</h3>' +
                        '<a class="noshadow" href="mailto:'+ data.user_email +'">'+ data.user_email +'</a>';
  $("body").prepend(hiddenInformation);
  $("#main-profile").html(profileSection);
}
