<!DOCTYPE html>

<html lang="en">

<head>
    <title>Humanities Class | Attendance System</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="{{ asset('css/attendancesystem.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body style="background-color:#eaebea;">
    <!-- PAGE HEADER -->
    <button onclick="toggleexitdialog()" id="btnexit"><i class="fas fa-xmark"></i></button>
    <div class="header">
        <div class="ctnlogo">
            <img src="{{ asset('images/logo.png') }}" id="headerlogo" alt="logo.png">
        </div>
        <div class="headertitle">
            <span style="color: #2e7c55;">Tzu Chi Philippines</span>
            <span style="color: #1a5319;">Educational Assistance Program</span>
        </div>
    </div>
    <!-- MAIN CONTENT -->
    <div class="maincontent">
        <div class="ctnmaintitle">
            <span class="maintitle">HUMANITIES CLASS ATTENDANCE</span>
        </div>
        <div class="ctnform">
            <form action="#">
                <div class="searchbar">
                    <span id="searchlabel">Search Name</span>
                    <input type="search" id="insearch" placeholder="Type here...">
                </div>
                <button type="submit" id="btnconfirm" onclick="toggleconfirmationdialog()">Confirm</button>
            </form>
        </div>
        <div class="ctnview">
            <button id="btnview" onclick="toggleviewdialog()">View List of Attendees</button>
        </div>
    </div>

    <!-- CONFIRMATION DIALOG -->
    <div class="ctndialog" id="confirmdialog" style="display: none;">
        <div class="groupA">
            <i class="dialogicon1 fas fa-circle-question"></i>
            <span id="label1">Are you sure this is you?</span>
            <span id="label2">"<span id="scholarname">JUAN DELA CRUZ</span>"</span>
            <span id="label3">This action cannot be undone.</span>

            <div class="groupA1">
                <button id="btnno" onclick="toggleconfirmationdialog()">No</button>
                <button id="btnyes">Yes</button>
            </div>
        </div>
    </div>

    <!-- EXIT DIALOG -->
    <div class="ctndialog" id="exitdialog" style="display: none;">
        <div class="groupA">
            <form action="#">
                <i class="dialogicon1 fas fa-circle-question"></i>
                <span id="label1">
                    Are you sure you want to exit?
                </span>
                <span id="label4">
                    Please enter your password to confirm.
                </span>
                <input type="password" placeholder="Password" id="inpassword1" class="inpassword" required>
                <span id="label3">This action cannot be undone.</span>

                <div class="groupA1">
                    <button type="button" id="btnno" onclick="toggleexitdialog()">No</button>
                    <button type="submit" id="btnyes">Yes</button>
                </div>
            </form>
        </div>
    </div>
    </div>

    <!-- SAVE DIALOG -->
    <div class="ctndialog" id="savedialog" style="display: none;">
        <div class="groupA">
            <form>
                <i class="dialogicon2 fas fa-circle-exclamation"></i>
                <span id="label2">
                    Restricted Section!
                </span>
                <span id="label4">This section is for authorized personnel only. Please enter your
                    password to continue.</span>
                <input type="password" placeholder="Password" id="inpassword2" class="inpassword" required>

                <div class="groupA1">
                    <button type="button" id="btncancel" onclick="toggleviewdialog()">Cancel</button>
                    <button type="submit" id="btnsubmit">Submit</button>
                </div>
            </form>
        </div>
    </div>

    <script src="{{ asset('js/hcattendancecontrol.js') }}"></script>
</body>

</html>
