<!-- NAVBAR -->
<div class="ctn-navbar">
    <div class="logo">
        <img src="{{ asset('images/logo.png') }}" alt="Logo">
        <h6 class="fw-bold">Tzu Chi Philippines<br>Educational Assistance Program</h6>
    </div>
    <button id="showprofmenu" onclick="showprofilemenu()"><i class="fas fa-user"></i></button>
</div>

<div class="ctn-profilemenu" id="profilemenu" style="display: none;">
    <a href="{{ route('manageprofile') }}"><i class="fa-solid fa-user"></i>Profile</a><br>
    <a href=""><i class="fa-solid fa-key"></i>Change Password</a><br>
    <span><i class="fa-solid fa-language"></i>Language</span></a>
    <button class="toggle-btn active">English</button>
    <button class="toggle-btn">Tagalog</button><br>
    <span><i class="fa-solid fa-bell"></i>Notification</span>
    <button class="toggle-btn active">SMS</button>
    <button class="toggle-btn">Email</button><br>
    <hr>
    <a href="" id="btn-signout"><i class="fa-solid fa-right-from-bracket"></i>Sign out</a>
</div>
