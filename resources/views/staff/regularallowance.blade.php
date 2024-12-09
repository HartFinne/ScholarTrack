<!DOCTYPE html>
<html lang="en">

<head>
    <title>Allowance Requests | Regular</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
    <link rel="stylesheet" href="{{ asset('css/allowance.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <!-- PAGE HEADER -->
    @include('partials._pageheader')

    <div class="ctnmain">
        <span class="text-success fw-bold h2">Regular Allowance Requests</span>
        <div class="groupA">
            <div class="groupA1">
                <span class="label">Total Requests</span>
                <span class="data" id="totalrequests">0</span>
            </div>
            <div class="groupA1">
                <span class="label">Pending</span>
                <span class="data" id="pending">0</span>
            </div>
            <div class="groupA1">
                <span class="label">Completed</span>
                <span class="data" id="completed">0</span>
            </div>
            <div class="groupA1">
                <span class="label">Accepted</span>
                <span class="data" id="completed">0</span>
            </div>
            <div class="groupA1">
                <span class="label">Rejected</span>
                <span class="data" id="rejected">0</span>
            </div>
        </div>
        <div class="divider"></div>
        <span class="text-success fw-bold h2">List of Requests</span>
        <div class="row align-items-center justify-content-between">
            <div class="col-md-3">
                <input type="search" class="border-success form-control" placeholder="Search">
            </div>
            <div class="col-auto">
                <div class="row gx-2 align-items-center">
                    <div class="col-auto">
                        <button class="filter btn btn-sm btn-success w-100" id="toggleAll">All</button>
                    </div>
                    <div class="col-auto">
                        <button class="filter btn btn-sm btn-outline-success w-100" id="toggleCollege">First
                            Year</button>
                    </div>
                    <div class="col-auto">
                        <button class="filter btn btn-sm btn-outline-success w-100" id="toggleSHS">Second Year</button>
                    </div>
                    <div class="col-auto">
                        <button class="filter btn btn-sm btn-outline-success w-100" id="toggleJHS">Third Year</button>
                    </div>
                    <div class="col-auto">
                        <button class="filter btn btn-sm btn-outline-success w-100" id="toggleElem">Fourth Year</button>
                    </div>
                    <div class="col-auto">
                        <button class="filter btn btn-sm btn-outline-success w-100" id="toggleElem">Fifth Year</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="ctntable table-responsive">
            <table class="table table-bordered" id="tblscholarslist">
                <thead>
                    <tr>
                        <th class="text-center align-middle">Name</th>
                        <th class="text-center align-middle">Request Date</th>
                        <th class="text-center align-middle">Year Level</th>
                        <th class="text-center align-middle">Release Date</th>
                        <th class="text-center align-middle">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($requests as $request)
                        <tr>
                            <td class="text-center align-middle">{{ $request->scLastname ?? 'N/A' }},
                                {{ $request->scFirstname ?? 'N/A' }}</td>
                            <td class="text-center align-middle">
                                {{ $request->created_at ? \Carbon\Carbon::parse($request->created_at)->format('F j, Y') : 'N/A' }}
                            </td>
                            <td class="text-center align-middle">{{ $request->scYearGrade }}</td>
                            <td class="text-center align-middle">
                                {{ $request->date_of_release ?? 'N/A - Update to include release date' }}
                            </td>
                            <td class="text-center align-middle">
                                <a href="{{ route('allowancerequests-regular-info', ['id' => $request->id]) }}"
                                    class="btn btn-success btn-sm"><i class="fas fa-eye"></i></a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="text-center align-middle" colspan="6">No Records Found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Links -->
        <div class="d-flex justify-content-center mt-3">
            {{ $requests->appends(request()->query())->links('pagination::bootstrap-4') }}
        </div>
    </div>

    <script src="{{ asset('js/headercontrol.js') }}"></script>
</body>

</html>
