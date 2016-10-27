/*
 * Licensed to the Apache Software Foundation (ASF) under one
 * or more contributor license agreements.  See the NOTICE file
 * distributed with this work for additional information
 * regarding copyright ownership.  The ASF licenses this file
 * to you under the Apache License, Version 2.0 (the
 * "License"); you may not use this file except in compliance
 * with the License.  You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing,
 * software distributed under the License is distributed on an
 * "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
 * KIND, either express or implied.  See the License for the
 * specific language governing permissions and limitations
 * under the License.
 */
var app = {
    // Application Constructor
    initialize: function() {
        document.addEventListener('deviceready', this.onDeviceReady, false);
    },

    onDeviceReady: function() {

        $( "[data-role='navbar']" ).navbar();
        $( "[data-role='header'], [data-role='footer']" ).toolbar();
        $( "[data-role='panel']" ).panel();

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

        // app.isOnline();
        //document.addEventListener('backbutton', this.onBackKeyDown, false);
    },

    /*onBackKeyDown: function() {
        if ($("#logged-in").val() === "false") {
            if ($.mobile.activePage.is('#home') ||
                $.mobile.activePage.is('#news') || $.mobile.activePage.is('#news-single') ||
                $.mobile.activePage.is('#events') || $.mobile.activePage.is('#event') ||
                $.mobile.activePage.is('#notifications') || $.mobile.activePage.is('#notification'))
            {
                signOut();
            } else {
                navigator.app.backButton();
            }
        } else {
            navigator.app.backButton();
        }
    },*/

    isOnline: function () {
        if (navigator.network.connection.type !== Connection.NONE) {
            $("#connection-test").replaceWith("Online");
        }
    }
};

app.initialize();
