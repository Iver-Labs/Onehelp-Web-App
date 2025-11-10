<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Volunteer Certificate</title>
    <style>
        body { font-family: 'Georgia', serif; text-align: center; padding: 50px; }
        .certificate { border: 10px solid #2C7A6E; padding: 40px; background: linear-gradient(to bottom, #fff, #f9f9f9); }
        .logo { font-size: 48px; color: #2C7A6E; font-weight: bold; margin-bottom: 20px; }
        h1 { font-size: 36px; color: #2C7A6E; margin: 20px 0; }
        .awarded-to { font-size: 18px; color: #666; margin: 20px 0; }
        .recipient { font-size: 32px; font-weight: bold; color: #000; margin: 20px 0; border-bottom: 2px solid #2C7A6E; display: inline-block; padding: 10px 50px; }
        .details { font-size: 16px; line-height: 2; color: #333; margin: 30px 0; }
        .signature { margin-top: 50px; }
        .sig-line { border-top: 2px solid #000; width: 200px; margin: 0 auto; }
        .date { margin-top: 30px; font-size: 14px; color: #666; }
    </style>
</head>
<body>
    <div class="certificate">
        <div class="logo">OneHelp</div>
        <h1>Certificate of Appreciation</h1>
        
        <div class="awarded-to">This certificate is proudly presented to</div>
        
        <div class="recipient">
            {{ $registration->volunteer->first_name }} {{ $registration->volunteer->last_name }}
        </div>
        
        <div class="details">
            For outstanding volunteer service and dedication<br>
            at <strong>{{ $registration->event->event_name }}</strong><br>
            hosted by <strong>{{ $registration->event->organization->organization_name ?? 'OneHelp' }}</strong><br>
            on {{ \Carbon\Carbon::parse($registration->event->event_date)->format('F d, Y') }}
        </div>
        
        @if($registration->hours_contributed)
            <div class="details">
                Total Hours Contributed: <strong>{{ $registration->hours_contributed }} hours</strong>
            </div>
        @endif
        
        <div class="signature">
            <div class="sig-line"></div>
            <div style="margin-top: 10px; font-size: 14px;">Organization Representative</div>
        </div>
        
        <div class="date">
            Issued on {{ now()->format('F d, Y') }}
        </div>
    </div>
</body>
</html>
