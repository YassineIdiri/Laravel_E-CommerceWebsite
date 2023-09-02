@if($unreadMessages > 0)
<div class='mini'>{{ $unreadMessages }}</div>
@else
<div style="display:none;">{{ $unreadMessages }}</div>
@endif

