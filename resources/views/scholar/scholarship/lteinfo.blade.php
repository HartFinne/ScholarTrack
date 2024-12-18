<!DOCTYPE html>
<html lang="en">

<head>
    <title>View LTE</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('css/overview.css') }}">
    <link rel="stylesheet" href="{{ asset('css/lteinfo.css') }}">
    <link rel="stylesheet" href="{{ asset('css/partial.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <!-- Include Sidebar -->
    @include('partials._sidebar')

    <!-- Include Navbar -->
    @include('partials._navbar')

    <!-- MAIN -->
    <div class="ctn-main">
        <a href="{{ route('sclte') }}" class="goback">&lt Go back</a>
        <div class="text-center">
            <h1 class="sub-title">Letter of Explanation</h1>

        </div>

        <div class="lte">
            <h6 class="text-center fw-bold">Buddhist Compassion Relief Tzu Chi Foundation Philippines, Inc.</h6>
            <p class="text-center">Educational Assistance Program</p>
            <p class="date" id="lte-date">{{ \Carbon\Carbon::parse($letter->dateissued)->format('F j, Y') }}</p>

            <div class="receipient">
                <p id="name">{{ $scholar->basicInfo->scLastname }}, {{ $scholar->basicInfo->scFirstname }}</p>
                <p id="casecode">{{ $scholar->caseCode }}</p>
                <p id="school">{{ $scholar->education->scSchoolName }}</p>
            </div>

            <div class="lte-subject">
                <p>Subject: <b>NOTICE TO EXPLAIN</b> </p>
            </div>

            <div class="salutation-lte">
                <p>Greetings!</p>
            </div>
            <div class="lte-body">
                <p style="text-align: justify">
                    @if ($letter->violation == 'Absent')
                        Last
                        {{ \Carbon\Carbon::parse($eventinfo->hcdate ?? $eventinfo->eventdate)->format('F j, Y') }}, was
                        the
                        {{ $eventinfo->topic ?? $eventinfo->title }} that took place at
                        {{ $eventinfo->hclocation ?? $eventinfo->eventloc }}. Upon checking the
                        attendance,
                        we noticed that you did not participate despite the Foundation's effort to inform you
                        beforehand.
                    @elseif ($letter->violation == 'Cancelled')
                        Last
                        {{ \Carbon\Carbon::parse($eventinfo->hcdate ?? $eventinfo->eventdate)->format('F j, Y') }}, was
                        the
                        {{ $eventinfo->topic ?? $eventinfo->title }} that took place at
                        {{ $eventinfo->hclocation ?? $eventinfo->eventloc }}. Upon checking the
                        attendance,
                        we noticed that you did not participate despite the Foundation's effort to inform you
                        beforehand.
                    @elseif ($letter->violation == 'Late')
                        Last
                        {{ \Carbon\Carbon::parse($eventinfo->hcdate ?? $eventinfo->eventdate)->format('F j, Y') }}, was
                        the
                        {{ $eventinfo->topic ?? $eventinfo->title }} that took
                        place at
                        {{ $eventinfo->hclocation ?? $eventinfo->eventloc }}. Upon reviewing the attendance,
                        we noticed that you arrived late to the event, despite the Foundation's prior communications and
                        efforts to ensure your timely participation.
                    @elseif ($letter->violation == 'Left Early')
                        Last
                        {{ \Carbon\Carbon::parse($eventinfo->hcdate ?? $eventinfo->eventdate)->format('F j, Y') }}, was
                        the
                        {{ $eventinfo->topic ?? $eventinfo->title }} that took
                        place at
                        {{ $eventinfo->hclocation ?? $eventinfo->eventloc }}.
                        Upon checking the
                        attendance,
                        we noticed that you left the event early, despite the Foundation's prior communication and
                        efforts
                        to ensure your full participation.
                    @elseif ($letter->violation == 'Failed GWA')
                        On {{ \Carbon\Carbon::parse($letter->dateissued)->format('F j, Y') }},
                        a review of your
                        academic performance was conducted, during which it was observed that your General Weighted
                        Average (GWA) of {{ $academicData->GWA }} for the {{ $academicData->SemesterQuarter }} of this
                        academic year ({{ $academicData->schoolyear }}) did not meet the minimum standard required for
                        satisfactory academic performance. Despite the Foundation's ongoing guidance and support
                        intended to assist you in achieving your academic objectives, your current GWA remains below the
                        established threshold.
                    @elseif ($letter->violation == 'Failed Grade')
                        On {{ \Carbon\Carbon::parse($letter->dateissued)->format('F j, Y') }},
                        a review of your
                        academic performance was conducted, and it was noted that you received a failing grade in
                        one of your subjects for the {{ $academicData->SemesterQuarter }} of this academic year
                        ({{ $academicData->schoolyear }}). This grade does not meet the minimum standard required
                        for satisfactory academic performance. Despite the Foundation's ongoing guidance and support
                        provided to assist you in achieving your academic objectives, your current performance
                        remains below the established threshold.
                    @elseif ($letter->violation == 'Mismatched GWA')
                        On {{ \Carbon\Carbon::parse($letter->dateissued)->format('F j, Y') }}, a review of your
                        academic performance was conducted, and it was noted that the General Weighted Average (GWA) you
                        provided does not align with the official GWA reflected on your report card for the
                        {{ $academicData->SemesterQuarter }} of this academic year ({{ $academicData->schoolyear }}).
                        This discrepancy does not align with the academic integrity and ethical standards that the
                        Foundation upholds for its scholars.
                    @endif
                </p>
                <p>
                    In connection with this, you are advised to <b>submit your written explanation letter within
                        three (3) days of receipt of this notice.</b>
                </p><br>
                <p>Kindly give this matter your priority attention.</p><br>
            </div>

            <div class="closing-lte">
                <div class="closing-1">
                    <p>Sincerely,</p>
                    <div class="signature">
                        <p>SIGNATURE</p>
                    </div>
                    <p><b>{{ $letter->workername }}</b><br>Social Welfare Officer</p>
                </div>
                <div class="closing-2">
                    <p>Noted by:</p>
                    <div class="signature">
                        <p>SIGNATURE</p>
                    </div>
                    <p><b>MARIA CRISTINA N. PASION, RSW</b><br>Department Head</p>
                </div>
            </div>
        </div>

        <div class="submit-lte text-center">
            <button type="button" class="btn-submit"
                onclick="window.location.href='{{ route('lteform', ['lid' => $letter->lid]) }}';">Submit
                your
                response here</button>
        </div>
    </div>

    <script src="{{ asset('js/scholar.js') }}"></script>
</body>

</html>
