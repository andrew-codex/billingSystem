<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{asset('/CSS_Styles/mainCss/base.css')}}">
    <link rel="stylesheet" href="{{asset('/CSS_Styles/mainCss/transferForm.css')}}">
    <title>Transfer Form</title>
</head>
<body class="dark-theme">

@include('includes.sidebar')


<a href="{{ route('consumer.index') }}" class="btn-back"> <i class="fa-solid fa-arrow-left"></i> Back</a>
<div class="page-wrapper">


    <div class="card form-card">
   
        <h2 class="card-title"> <i class="fa-solid fa-bolt"></i> Transfer Meter #{{ $meter->electric_meter_number ?? $meter->id }}</h2>
        <p class="subtitle">Select an action to proceed with the meter transfer</p>

        <form action="{{ route('meters.transferOrReplace', $meter->id) }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="mode">Choose Action</label>
                <select name="mode" id="mode" class="form-control" required onchange="toggleFields()">
                    <option value="">-- Select --</option>
                    <option value="transfer">Transfer Responsibility</option>
                    <option value="replacement">Replace Meter</option>
                </select>
            </div>

            <div id="transferFields" class="form-group hidden">
                <label for="consumer_id">Select New Consumer</label>
                <select name="consumer_id" id="consumer_id" class="form-control">
                    <option value="">-- Choose Consumer --</option>
                    @foreach($consumers as $consumer)
                        <option value="{{ $consumer->id }}">{{ $consumer->full_name }}</option>
                    @endforeach
                </select>
            </div>

            <div id="replacementFields" class="form-group hidden">
                <label for="new_meter_no">Select Replacement Meter</label>
                <select name="new_meter_no" id="new_meter_no" class="form-control">
                    <option value="">-- Choose Available Meter --</option>
                    @foreach($meters as $availableMeter)
                        <option value="{{ $availableMeter->electric_meter_number }}">
                            {{ $availableMeter->electric_meter_number }}
                        </option>
                    @endforeach
                </select>
                @error('new_meter_no')
                    <p class="error-text">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-actions">
                <button type="submit" id="submitBtn" class="btn-submit">Submit</button>
            </div>
        </form>
    </div>

    
    <div class="card history-card">
        <h3 class="card-title"> <i class="fa-solid fa-clock"></i> Recent Transactions</h3>
        <p class="subtitle">Latest meter transfer activities</p>

        <div class="history-list">
            @forelse($history as $log)
                <div class="history-item">
                    <span class="tag {{ $log->transaction_type }}">{{ ucfirst($log->transaction_type) }}</span>
                    <p class="remarks">{{ $log->remarks }}</p>
                    <small class="meta">
                        Meter: {{ $log->meter->electric_meter_number ?? $log->meter_id }} <br>
                        <i class="fa-solid fa-user-tie"></i> By: {{ $log->changedBy->name ?? 'System' }} |
                        <i class="fa-solid fa-calendar-days"></i> {{ $log->created_at->format('M d, Y h:i A') }}
                    </small>
                </div>
            @empty
                <p class="empty">No recent transactions.</p>
            @endforelse
        </div>
    </div>
</div>

<script>
function toggleFields() {
    const mode = document.getElementById('mode').value;
    document.getElementById('transferFields').classList.toggle('hidden', mode !== 'transfer');
    document.getElementById('replacementFields').classList.toggle('hidden', mode !== 'replacement');
}
</script>

</body>
</html>
