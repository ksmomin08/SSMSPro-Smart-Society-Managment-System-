$(document).ready(function() {
    var baseUrl = $('#base_url').val();
    var userType = $('#adminUserType').val();
    if (userType === 'Staff' || userType === 'Franchise') {
        baseUrl += '/' + userType.toLowerCase();
    }
    localStorage.setItem('adminId', $('#adminId').val());
    localStorage.setItem('adminUserType', userType);
    
    var sessionVal = $('#adminSessionVal').val();

    var sessionTimeout = sessionVal * 60; // Session Time:
    var warningTime = 2 * 60; // Warning Time
    var timer;
    var countdownTimer;
    // Check if the start time is already set in localStorage
    if (!localStorage.getItem('adminSessionStartTime')) {
        localStorage.setItem('adminSessionStartTime', Date.now());
    }
    // Function to calculate remaining time
    function calculateRemainingTime() {
        var adminSessionStartTime = parseInt(localStorage.getItem('adminSessionStartTime'));
        var elapsedTime = Math.floor((Date.now() - adminSessionStartTime) / 1000);
        var remainingTime = sessionTimeout - elapsedTime;
        // Ensure the remaining time doesn't go below zero
        return remainingTime >= 0 ? remainingTime : 0;
    }
    // Update the timer every second
    function startTimer() {
        countdownTimer = setInterval(function() {
            timer = calculateRemainingTime();
            var hours = Math.floor(timer / 3600);
            var minutes = Math.floor((timer % 3600) / 60);
            var seconds = timer % 60;
            $('#adminRemainingTime').text(formatTime(hours) + ":" + formatTime(minutes) + ":" + formatTime(seconds));
            // Show session timeout modal when time is running low
            if (timer <= warningTime) {
                showSessionTimeoutModal();
            }
            // console.log(timer);
            if (timer <= 0) {
                clearInterval(countdownTimer);
                localStorage.removeItem('adminId');
                localStorage.removeItem('adminUserType'); 
                localStorage.removeItem('adminSessionStartTime');
                window.location.href = baseUrl+'/auto-logout';
            }
        }, 1000);
    }
    // Function to format time (00:00)
    function formatTime(time) {
        return time < 10 ? "0" + time : time;
    }
    // Show the session timeout modal
    function showSessionTimeoutModal() {
        if (!$('#sessionExpiredModal').is(':visible')) {
            $('#sessionExpiredModal').modal('show');
        }
    }
    // Extend session time when the user clicks the button
    $('#extendSessionTime').click(function() {
        extendSession();
    });
    function extendSession() {
        var baseUrlAdmin = $('#base_url').val();
        $.get(baseUrlAdmin+'/extend-session', function() {
            localStorage.setItem('adminSessionStartTime', Date.now()); // Reset session start time
            $('#sessionExpiredModal').modal('hide');
        });
    }
    extendSession();
    
    // Logout functionality
    $('#logoutBtn').click(function() {
        window.location.href = baseUrl+'/logout';
    });

    // Listen for changes to localStorage to update timer across tabs
    window.addEventListener('storage', function(event) {
        if (event.key === 'adminSessionStartTime') {
            // Restart the timer when session start time changes in another tab
            clearInterval(countdownTimer);
            startTimer();
        }
    });
    // Start the session timeout countdown
    startTimer();
    // Initialize the modal
    $('#sessionExpiredModal').modal({
        backdrop: 'static',
        keyboard: false
    });

    window.addEventListener("beforeunload", function (event) {
        var userType = $('#adminUserType').val();
        if (userType === 'Staff' || userType === 'Franchise') {
            baseUrl += '/' + userType.toLowerCase();
        }
        var logoutUrl = baseUrl+'/auto-logout';
        navigator.sendBeacon(logoutUrl);
    });
});
