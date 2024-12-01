<!DOCTYPE html>
<html lang="en">

<head>
    <title>Scholars Evaluation</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    @include('partials._pageheader')

    <div class="ctnmain">
        <div class="row align-items-center justify-content-between">
            <div class="col-auto">
                <span class="fw-bold text-success h2">Scholars Performance Summary</span>
            </div>
            <div class="col-auto">
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#reportModal">Generate Summary
                    Report</button>
            </div>
        </div>
        <div class="row gx-1">
            <div class="" style="width: max-content">
                <button class="filterlvl btn btn-sm btn-success w-100" id="toggleCollege">College</button>
            </div>
            <div class="" style="width: max-content">
                <button class="filterlvl btn btn-sm btn-outline-success w-100" id="toggleSHS">Senior High</button>
            </div>
            <div class="" style="width: max-content">
                <button class="filterlvl btn btn-sm btn-outline-success w-100" id="toggleJHS">Junior High</button>
            </div>
            <div class="" style="width: max-content">
                <button class="filterlvl btn btn-sm btn-outline-success w-100" id="toggleElem">Elementary</button>
            </div>
        </div>
        <div class="row">
            <span class="fw-bold text-success h3 text-center">Academic Year: {{ $acadyear }}</span>
        </div>
        <div class="ctn" id="college">
            @php
            $cycles = [
            'Semester' => ['columns' => ['1st Sem GWA', '2nd Sem GWA']],
            'Trimester' => ['columns' => ['1st Sem GWA', '2nd Sem GWA', '3rd Sem GWA']],
            ];
            @endphp
            @if ($colleges->isEmpty())
            <div class="row text-center fw-bold h6">
                <span>No Data Available</span>
            </div>
            @endif
            @foreach ($cycles as $cycle => $data)
            @php $cycleResults = $colleges->filter(fn($result) => $result->acadcycle == $cycle); @endphp

            @if (!$cycleResults->isEmpty())
            <div class="row">
                <span class="fw-bold text-success h5">Academic Cycle: {{ $cycle }}</span>
            </div>
            <div class="ctntable table-responsive">
                <table class="table table-bordered">
                    <thead class="">
                        <tr>
                            <th class="text-center align-middle">#</th>
                            <th class="text-center align-middle">Scholar Name</th>
                            @foreach ($data['columns'] as $column)
                            <th class="text-center align-middle">{{ $column }}</th>
                            @endforeach
                            <th class="text-center align-middle">Rendered CS Hours</th>
                            <th class="text-center align-middle">HC Absent Count</th>
                            <th class="text-center align-middle">Penalty Count</th>
                            <th class="text-center align-middle">Scholarship Status</th>
                            <th class="text-center align-middle">Remark</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $index = 1; @endphp
                        @foreach ($cycleResults as $result)
                        <tr>
                            <td class="text-center align-middle">{{ $index++ }}</td>
                            <td class="text-center align-middle">{{ $result->basicInfo->scLastname }},
                                {{ $result->basicInfo->scFirstname }}
                                {{ $result->basicInfo->scMiddlename }}
                            </td>
                            @foreach ($data['columns'] as $key => $column)
                            <td class="text-center align-middle">
                                {{ $result->{'gwasem' . ($key + 1)} ?? 'No Data' }}
                            </td>
                            @endforeach
                            <td class="text-center align-middle">{{ $result->cshours ?? 'No Data' }}</td>
                            <td class="text-center align-middle">{{ $result->hcabsentcount ?? 'No Data' }}
                            </td>
                            <td class="text-center align-middle">{{ $result->penaltycount ?? 'No Data' }}
                            </td>
                            <td class="text-center align-middle">
                                {{ $result->scholarshipinfo->scholarshipstatus ?? '' }}
                            </td>
                            <td class="text-center align-middle">{{ $result->remark ?? 'Incomplete Data' }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
            @endforeach
        </div>
        <div class="ctn" id="shs" style="display: none;">
            @php
            $cycles = [
            'Semester' => ['columns' => ['1st Sem GWA', '2nd Sem GWA']],
            'Trimester' => ['columns' => ['1st Sem GWA', '2nd Sem GWA', '3rd Sem GWA']],
            ];
            @endphp
            @if ($shs->isEmpty())
            <div class="row text-center fw-bold h6">
                <span>No Data Available</span>
            </div>
            @endif
            @foreach ($cycles as $cycle => $data)
            @php $cycleResults = $shs->filter(fn($result) => $result->acadcycle == $cycle); @endphp

            @if (!$cycleResults->isEmpty())
            <div class="row">
                <span class="fw-bold text-success h5">Academic Cycle: {{ $cycle }}</span>
            </div>
            <div class="ctntable table-responsive">
                <table class="table table-bordered">
                    <thead class="">
                        <tr>
                            <th class="text-center align-middle">#</th>
                            <th class="text-center align-middle">Scholar Name</th>
                            @foreach ($data['columns'] as $column)
                            <th class="text-center align-middle">{{ $column }}</th>
                            @endforeach
                            <th class="text-center align-middle">HC Absent Count</th>
                            <th class="text-center align-middle">Penalty Count</th>
                            <th class="text-center align-middle">Scholarship Status</th>
                            <th class="text-center align-middle">Remark</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $index = 1; @endphp
                        @foreach ($cycleResults as $result)
                        <tr>
                            <td class="text-center align-middle">{{ $index++ }}</td>
                            <td class="text-center align-middle">{{ $result->basicInfo->scLastname }},
                                {{ $result->basicInfo->scFirstname }}
                                {{ $result->basicInfo->scMiddlename }}
                            </td>
                            @foreach ($data['columns'] as $key => $column)
                            <td class="text-center align-middle">
                                {{ $result->{'gwasem' . ($key + 1)} ?? 'No Data' }}
                            </td>
                            @endforeach
                            <td class="text-center align-middle">{{ $result->hcabsentcount ?? 'No Data' }}
                            </td>
                            <td class="text-center align-middle">{{ $result->penaltycount ?? 'No Data' }}
                            </td>
                            <td class="text-center align-middle">
                                {{ $result->scholarshipinfo->scholarshipstatus ?? '' }}
                            </td>
                            <td class="text-center align-middle">{{ $result->remark ?? 'Incomplete Data' }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
            @endforeach
        </div>
        <div class="ctn" id="jhs" style="display: none;">
            @php
            $cycles = [
            'Quarter' => [
            'columns' => ['1st Quarter GWA', '2nd Quarter GWA', '3rd Quarter GWA', '4th Quarter GWA'],
            ],
            ];
            @endphp
            @if ($jhs->isEmpty())
            <div class="row text-center fw-bold h6">
                <span>No Data Available</span>
            </div>
            @endif
            @foreach ($cycles as $cycle => $data)
            @php $cycleResults = $jhs->filter(fn($result) => $result->acadcycle == $cycle); @endphp

            @if (!$cycleResults->isEmpty())
            <div class="row">
                <span class="fw-bold text-success h5">Academic Cycle: {{ $cycle }}</span>
            </div>
            <div class="ctntable table-responsive">
                <table class="table table-bordered">
                    <thead class="">
                        <tr>
                            <th class="text-center align-middle">#</th>
                            <th class="text-center align-middle">Scholar Name</th>
                            <th class="text-center align-middle">General Average</th>
                            <th class="text-center align-middle">HC Absent Count</th>
                            <th class="text-center align-middle">Penalty Count</th>
                            <th class="text-center align-middle">Scholarship Status</th>
                            <th class="text-center align-middle">Remark</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $index = 1; @endphp
                        @foreach ($cycleResults as $result)
                        <tr>
                            <td class="text-center align-middle">{{ $index++ }}</td>
                            <td class="text-center align-middle">{{ $result->basicInfo->scLastname }},
                                {{ $result->basicInfo->scFirstname }}
                                {{ $result->basicInfo->scMiddlename }}
                            </td>
                            <td class="text-center align-middle">{{ $result->gwa ?? 'No Data' }}
                            </td>
                            <td class="text-center align-middle">{{ $result->hcabsentcount ?? 'No Data' }}
                            </td>
                            <td class="text-center align-middle">{{ $result->penaltycount ?? 'No Data' }}
                            </td>
                            <td class="text-center align-middle">
                                {{ $result->scholarshipinfo->scholarshipstatus ?? '' }}
                            </td>
                            <td class="text-center align-middle">
                                {{ $result->remark ?? 'Incomplete Data' }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
            @endforeach
        </div>
        <div class="ctn" id="elem" style="display: none;">
            @php
            $cycles = [
            'Quarter' => [
            'columns' => ['1st Quarter GWA', '2nd Quarter GWA', '3rd Quarter GWA', '4th Quarter GWA'],
            ],
            ];
            @endphp
            @if ($elem->isEmpty())
            <div class="row text-center fw-bold h6">
                <span>No Data Available</span>
            </div>
            @endif
            @foreach ($cycles as $cycle => $data)
            @php $cycleResults = $elem->filter(fn($result) => $result->acadcycle == $cycle); @endphp

            @if (!$cycleResults->isEmpty())
            <div class="row">
                <span class="fw-bold text-success h5">Academic Cycle: {{ $cycle }}</span>
            </div>
            <div class="ctntable table-responsive">
                <table class="table table-bordered">
                    <thead class="">
                        <tr>
                            <th class="text-center align-middle">#</th>
                            <th class="text-center align-middle">Scholar Name</th>
                            <th class="text-center align-middle">General Average</th>
                            <th class="text-center align-middle">HC Absent Count</th>
                            <th class="text-center align-middle">Penalty Count</th>
                            <th class="text-center align-middle">Scholarship Status</th>
                            <th class="text-center align-middle">Remark</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $index = 1; @endphp
                        @foreach ($cycleResults as $result)
                        <tr>
                            <td class="text-center align-middle">{{ $index++ }}</td>
                            <td class="text-center align-middle">{{ $result->basicInfo->scLastname }},
                                {{ $result->basicInfo->scFirstname }}
                                {{ $result->basicInfo->scMiddlename }}
                            </td>
                            <td class="text-center align-middle">{{ $result->gwa ?? 'No Data' }}
                            <td class="text-center align-middle">{{ $result->hcabsentcount ?? 'No Data' }}
                            </td>
                            <td class="text-center align-middle">{{ $result->penaltycount ?? 'No Data' }}
                            </td>
                            <td class="text-center align-middle">
                                {{ $result->scholarshipinfo->scholarshipstatus ?? '' }}
                            </td>
                            <td class="text-center align-middle">
                                {{ $result->remark ?? 'Incomplete Data' }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
            @endforeach
        </div>
    </div>

    <div class="modal fade" id="reportModal" tabindex="-1" aria-labelledby="reportModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="reportModalLabel">Generate Summary Report</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" action="" id="summaryReportForm">
                        <div class="mb-3">
                            <label for="startdate" class="form-label">Start Date</label>
                            <input type="date" name="startdate" id="startdate" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="enddate" class="form-label">End Date</label>
                            <input type="date" name="enddate" id="enddate" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="schoollevel" class="form-label">School Level</label>
                            <select name="schoollevel" id="schoollevel" class="form-select" required>
                                <option value="" selected hidden>Select school level</option>
                                <option value="All">All</option>
                                <option value="College">College</option>
                                <option value="Senior High">Senior High</option>
                                <option value="Junior High">Junior High</option>
                                <option value="Elementary">Elementary</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" form="summaryReportForm" class="btn btn-success">Generate Report</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // document.addEventListener("DOMContentLoaded", function() {
        //     const evaluateLink = document.querySelector("#evaluate-link");

        //     if (evaluateLink) {
        //         evaluateLink.addEventListener("click", function(event) {
        //             const loadingModal = new bootstrap.Modal(document.getElementById('loadingModal'));
        //             loadingModal.show();
        //         });
        //     }
        // });

        $(document).ready(function() {
            function toggleSection(buttonId, containerId) {
                $(buttonId).click(function() {
                    // Fade out all containers, then fade in the selected one
                    $('.ctn').not(containerId).fadeOut('fast', function() {
                        $(containerId).fadeIn('slow');
                    });

                    // Update button classes to reflect active/inactive states
                    $('.filterlvl').not(buttonId).removeClass('btn-success').addClass(
                        'btn-outline-success');
                    $(buttonId).removeClass('btn-outline-success').addClass('btn-success');
                });
            }

            // Attach events to buttons
            toggleSection('#toggleCollege', '#college');
            toggleSection('#toggleSHS', '#shs');
            toggleSection('#toggleJHS', '#jhs');
            toggleSection('#toggleElem', '#elem');
        });
    </script>
    <script src="{{ asset('js/headercontrol.js') }}"></script>
</body>

</html>