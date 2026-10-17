@php
  use App\Support\SurveyStats as S;
@endphp
@extends('admin.layout', ['title' => 'Răspunsuri', 'active' => 'resp'])

@section('content')
<h1>Răspunsuri</h1>

<form class="filters" method="get" action="{{ route('admin.responses') }}">
  <div>
    <label for="f-status">Stare</label>
    <select id="f-status" name="status">
      @foreach ($statuses as $k => $v)
        <option value="{{ $k }}" @selected($k === $status)>{{ $v }}</option>
      @endforeach
    </select>
  </div>
  <div class="grow">
    <label for="f-q">Caută în trasee și comentarii</label>
    <input id="f-q" name="q" type="search" value="{{ $q }}" placeholder="de exemplu: Copou">
  </div>
  <label class="check"><input type="checkbox" name="comentarii" value="1" @checked($onlyComments)> Doar cu comentariu</label>
  <button class="btn btn-small" type="submit">Filtrează</button>
</form>

<p class="muted">{{ $rows->total() }} răspunsuri{{ $rows->hasPages() ? ' · pagina '.$rows->currentPage().' din '.$rows->lastPage() : '' }}</p>

<div class="table-wrap">
<table class="a-table">
  <thead>
    <tr><th>#</th><th>Data</th><th>Stare</th><th>Zile</th><th>Cum au ajuns (zile)</th><th>Drum</th><th>Trafic</th><th>Traseu</th><th>Alternativă</th><th>Ce ar ajuta</th><th>Comentariu</th><th></th></tr>
  </thead>
  <tbody>
  @forelse ($rows as $r)
    @php
      $modes = [];
      foreach (['foot', 'bike', 'public', 'car', 'taxi', 'other'] as $k) {
          if ((int) $r->{'m_'.$k} > 0) {
              $modes[] = S::label('modes', $k).' '.(int) $r->{'m_'.$k};
          }
      }
    @endphp
    <tr>
      <td>{{ $r->id }}</td>
      <td class="nw">{{ $r->created_at->format('d.m.Y H:i') }}</td>
      <td><span class="pill pill-{{ $r->status }}">{{ S::label('status', $r->status) }}</span></td>
      <td>{{ $r->days ?? '–' }}</td>
      <td>{{ $modes ? implode(', ', $modes) : '–' }}</td>
      <td class="nw">{{ S::label('dist', $r->dist) }}</td>
      <td class="nw">{{ S::label('traffic', $r->traffic) }}</td>
      <td class="wide">{{ $r->route ?: '–' }}</td>
      <td>{{ $r->alt === null ? '–' : S::label('alt', $r->alt).($r->replace_days !== null ? ' ('.$r->replace_days.' dim.)' : '') }}</td>
      <td>{{ S::labelList('help', $r->help_opts) }}</td>
      <td class="wide">{{ $r->comment ?: '–' }}</td>
      <td>
        <form method="post" action="{{ route('admin.responses.destroy', ['response' => $r->id] + request()->query()) }}" onsubmit="return confirm('Ștergi definitiv răspunsul #{{ $r->id }}?');">
          @csrf
          @method('DELETE')
          <button class="link-danger" type="submit">Șterge</button>
        </form>
      </td>
    </tr>
  @empty
    <tr><td colspan="12" class="muted">Nu există răspunsuri pentru filtrele alese.</td></tr>
  @endforelse
  </tbody>
</table>
</div>

@if ($rows->hasPages())
<nav class="pager" aria-label="Pagini">
  @if (! $rows->onFirstPage())<a class="btn btn-ghost btn-small" href="{{ $rows->previousPageUrl() }}">← Înapoi</a>@endif
  <span class="muted">Pagina {{ $rows->currentPage() }} din {{ $rows->lastPage() }}</span>
  @if ($rows->hasMorePages())<a class="btn btn-ghost btn-small" href="{{ $rows->nextPageUrl() }}">Înainte →</a>@endif
</nav>
@endif
@endsection
