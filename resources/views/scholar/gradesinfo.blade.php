<!DOCTYPE html>
<html lang="en">

<head>
    <title>View Submitted Grades</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('css/partial.css') }}">
    <link rel="stylesheet" href="{{ asset('css/scholar.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body>
    <!-- Include Sidebar -->
    @include('partials._sidebar')

    <!-- Include Navbar -->
    @include('partials._navbar')

    <!-- MAIN -->
    <div class="ctn-main">
        <a href="" class="goback">&lt Go back</a>
        <div class="text-center">
            <h1 class="sub-title">Grades Submission</h1>
        </div>

        <div class="grades-view">
            <div class="grade-status">
                <h5 class="grade-stat">PASSED</h5>
            </div>

            <div class="grades-info">
                <h4>GRADES SUBMITTED</h4>
                <div class="info">
                    <div class="label">GWA</div>
                    <div class="value">: <span>1.25</span></div>

                    <div class="label">School Year</div>
                    <div class="value">: <span>S.Y. 2022-2023</span></div>

                    <div class="label">Semester</div>
                    <div class="value">: <span>1ST SEMESTER</span></div>

                    <div class="label">Grades</div>
                    <div class="value">: </div>
                </div>
                <div class="grades-img text-center">
                    <img src="{{ asset('images/tzu-chi-bg.jpg') }}" alt="">
                </div>
            </div>
        </div>


    </div>
    <script src="{{ asset('js/scholar.js') }}"></script>
</body>

</html>
