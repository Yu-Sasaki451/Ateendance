@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/user/correction_request_index.css') }}">
@endsection

@section('header-menu')
<nav class="header-nav">
    <a class="nav-link" href="/attendance">勤怠</a>
    <a class="nav-link" href="/attendance/list">勤怠一覧</a>
    <a class="nav-link" href="/stamp_correction_request/list">申請</a>
    <form action="/logout" method="post">
        @csrf
        <input type="hidden" name="logout_from" value="user">
        <button class="nav-button" type="submit">ログアウト</button>
    </form>
</nav>
@endsection

@section('content')
<div class="request-list" data-tab-root>
    <h1 class="page-title">申請一覧</h1>
    <div class="tablist" role="tablist">
        <button 
            class="tab-button"
            type="button"
            role="tab"
            id="tab-pending"
            aria-controls="panel-pending"
            aria-selected="{{ $activeTab === 'pending' ? 'true' : 'false' }}"
            tabindex="{{ $activeTab === 'pending' ? '0' : '-1' }}"
        >
            承認待ち
        </button>

        <button 
            class="tab-button"
            type="button"
            role="tab"
            id="tab-approved"
            aria-controls="panel-approved"
            aria-selected="{{ $activeTab === 'approved' ? 'true' : 'false' }}"
            tabindex="{{ $activeTab === 'approved' ? '0' : '-1' }}"
        >
            承認済み
        </button>
    </div>
    <div
        class="tab-panel"
        role="tabpanel"
        id="panel-pending"
        aria-labelledby="tab-pending"
        tabindex="0" {{ $activeTab === 'pending' ? '' : 'hidden' }}>
        <div>
            <table>
                <tr>
                    <th>状態</th>
                    <th>名前</th>
                    <th>対象日時</th>
                    <th>申込理由</th>
                    <th>申込日時</th>
                    <th>詳細</th>
                </tr>
                @foreach($pendingRequests as $pendingRequest)
                <tr>
                    <td>{{ $pendingRequest->status_label }}</td>
                    <td>{{ $userName }}</td>
                    <td>{{ $pendingRequest->target_date }}</td>
                    <td>{{ $pendingRequest->reason }}</td>
                    <td>{{ $pendingRequest->applied_date }}</td>
                    <td>詳細</td>
                </tr>
                @endforeach
            </table>
        </div>

    </div>
    <div
        class="tab-panel"
        role="tabpanel"
        id="panel-approved"
        aria-labelledby="tab-approved"
        tabindex="0" {{ $activeTab === 'approved' ? '' : 'hidden' }}>

        <div>
            <table>
                <tr>
                    <th>状態</th>
                    <th>名前</th>
                    <th>対象日時</th>
                    <th>申込理由</th>
                    <th>申込日時</th>
                    <th>詳細</th>
                </tr>
                @foreach($approvedRequests as $approvedRequest)
                <tr>
                    <td>{{ $approvedRequest['status'] }}</td>
                    <td>{{ $userName }}</td>
                    <td>{{ $approvedRequest['requested_in_at'] }}</td>
                    <td>{{ $approvedRequest['reason'] }}</td>
                    <td>{{ $approvedRequest['requested_out_at'] }}</td>
                    <td>詳細</td>
                </tr>
                @endforeach
            </table>
        </div>

    </div>
</div>
@endsection

@section('js')
<script src="{{ asset('js/common/tab-switch.js') }}"></script>
@endsection
