@extends('admins.master')
@section('title','Import data')
@section('page_title','Import data')
@section('page_subtitle','Bring your Shopify catalog into this store')
@section('import_data','is-active')
@php
    $step = request('step', ($connection && $connection->isConnected()) ? 'select' : 'connect');
    if ($job && in_array($job->status, ['queued','running'])) { $step = 'progress'; }
    if ($job && $job->status === 'completed' && request('step') !== 'select') { $step = request('step', 'done'); }
    $cfg = $job ? $job->config() : [];
    $opt = $cfg['options'] ?? [];
    $sel = $cfg['types'] ?? ['products','collections','brands','images','variants','options','inventory','tags'];
    $map = $cfg['mapping'] ?? $mapping;
    $preview = $job ? $job->preview() : [];
    $counts = $job ? $job->counts() : [];
    $connected = $connection && $connection->isConnected();
@endphp
@section('content')
<div class="imp-wrap">
    <div class="sa-card imp-hero">
        <h2>Import your store data</h2>
        <p>Connect your Shopify store and bring your existing catalog into this store.</p>
        @if(!$connected)
            <div class="imp-actions">
                <button type="button" class="sa-btn sa-btn-primary" data-toggle="modal" data-target="#oauthModal">Connect Shopify</button>
                <button type="button" class="sa-btn sa-btn-secondary" data-toggle="modal" data-target="#manualModal">Use API credentials</button>
            </div>
            @if(!$oauthReady)
                <p class="sa-muted" style="margin-top:12px;">One-click connect needs a Shopify app. Until that’s set up, use API credentials from Shopify Admin → Settings → Apps → Develop apps.</p>
            @endif
        @endif
    </div>

    @if($connection)
    <div class="sa-card">
        <h3>Connected Shopify store</h3>
        <div class="imp-meta">
            <div><span>Store name</span><strong>{{ $connection->shop_name ?: '—' }}</strong></div>
            <div><span>Store domain</span><strong>{{ $connection->shop_domain }}</strong></div>
            <div><span>Status</span>
                @if($connection->status === 'connected') <em class="sa-badge">Connected</em>
                @elseif($connection->status === 'invalid') <em class="sa-badge is-paused">Needs reconnect</em>
                @else <em class="sa-badge is-draft">Disconnected</em>
                @endif
            </div>
            <div><span>Last imported</span><strong>{{ $connection->last_synced_at ? $connection->last_synced_at->diffForHumans() : 'Never' }}</strong></div>
        </div>
        <div class="imp-actions" style="margin-top:16px;">
            @if($connected)
                <button type="button" class="sa-btn sa-btn-secondary" data-toggle="modal" data-target="#oauthModal">Reconnect</button>
            @endif
            <form method="post" action="{{ url('/admin/import-data/shopify/disconnect') }}" style="display:inline;">
                @csrf
                <button class="sa-btn sa-btn-danger" type="submit">Disconnect</button>
            </form>
        </div>
    </div>
    @endif

    @if($connected)
        <div class="imp-steps">
            <span class="{{ in_array($step,['select','options','map','preview','progress','done']) ? 'on' : '' }}">1 Select</span>
            <span class="{{ in_array($step,['options','map','preview','progress','done']) ? 'on' : '' }}">2 Options</span>
            <span class="{{ in_array($step,['map','preview','progress','done']) ? 'on' : '' }}">3 Mapping</span>
            <span class="{{ in_array($step,['preview','progress','done']) ? 'on' : '' }}">4 Preview</span>
            <span class="{{ in_array($step,['progress','done']) ? 'on' : '' }}">5 Import</span>
        </div>

        @if($step !== 'progress' && $step !== 'done')
        <form method="post" action="{{ url('/admin/import-data/config') }}" id="importConfig">
            @csrf
            <div class="sa-card">
                <h3>Select data</h3>
                <p class="sa-muted">Only items this store can import are listed. Customers are skipped because accounts are not store-scoped yet.</p>
                <label class="sa-check"><input type="checkbox" id="selectAll"> Select all</label>
                <div class="imp-checks">
                    @foreach($types as $key => $label)
                        <label class="sa-check"><input type="checkbox" name="types[]" value="{{ $key }}" class="type-box" @if(in_array($key,$sel)) checked @endif> {{ $label }}</label>
                    @endforeach
                </div>
            </div>

            <div class="sa-card">
                <h3>Import options</h3>
                <div class="sa-grid sa-grid-2">
                    <div>
                        <p class="sa-section-title">Products</p>
                        <label class="sa-check"><input type="checkbox" name="opt_active" value="1" @if($opt['active'] ?? true) checked @endif> Import active products</label>
                        <label class="sa-check"><input type="checkbox" name="opt_draft" value="1" @if($opt['draft'] ?? false) checked @endif> Import draft products</label>
                        <label class="sa-check"><input type="checkbox" name="opt_archived" value="1" @if($opt['archived'] ?? false) checked @endif> Import archived products</label>
                        <label class="sa-check"><input type="checkbox" name="opt_descriptions" value="1" @if($opt['import_descriptions'] ?? true) checked @endif> Import product descriptions</label>
                        <label class="sa-check"><input type="checkbox" name="opt_images" value="1" @if($opt['import_images'] ?? true) checked @endif> Import images (downloaded locally)</label>
                        <label class="sa-check"><input type="checkbox" name="opt_variants" value="1" @if($opt['import_variants'] ?? true) checked @endif> Import variants</label>
                        <label class="sa-check"><input type="checkbox" name="opt_sku" value="1" @if($opt['import_sku'] ?? true) checked @endif> Import SKU</label>
                        <label class="sa-check"><input type="checkbox" name="opt_barcode" value="1" @if($opt['import_barcode'] ?? true) checked @endif> Import barcode</label>
                        <label class="sa-check"><input type="checkbox" name="opt_pricing" value="1" @if($opt['import_pricing'] ?? true) checked @endif> Import pricing</label>
                        <label class="sa-check"><input type="checkbox" name="opt_compare" value="1" @if($opt['import_compare'] ?? true) checked @endif> Import compare-at pricing</label>
                        <label class="sa-check"><input type="checkbox" name="opt_inventory" value="1" @if($opt['import_inventory'] ?? true) checked @endif> Import inventory</label>
                    </div>
                    <div>
                        <p class="sa-section-title">Categories & brands</p>
                        <label class="sa-check"><input type="checkbox" name="opt_map_collections" value="1" @if($opt['map_collections'] ?? true) checked @endif> Map collections to categories</label>
                        <label class="sa-check"><input type="checkbox" name="opt_create_categories" value="1" @if($opt['create_categories'] ?? true) checked @endif> Create missing categories automatically</label>
                        <label class="sa-check"><input type="checkbox" name="opt_create_brands" value="1" @if($opt['create_brands'] ?? true) checked @endif> Create missing brands automatically</label>
                        <p class="sa-section-title">If an item already exists</p>
                        <label class="sa-check"><input type="radio" name="duplicate_mode" value="update" @if(($job->duplicate_mode ?? 'update')==='update') checked @endif> Update existing</label>
                        <label class="sa-check"><input type="radio" name="duplicate_mode" value="skip" @if(($job->duplicate_mode ?? '')==='skip') checked @endif> Skip existing</label>
                        <label class="sa-check"><input type="radio" name="duplicate_mode" value="duplicate" @if(($job->duplicate_mode ?? '')==='duplicate') checked @endif> Create duplicate</label>
                    </div>
                </div>
            </div>

            <div class="sa-card">
                <h3>Data mapping</h3>
                <p class="sa-muted">Automatic mapping is applied. Change a field only if you need something different.</p>
                @php
                    $labels = [
                        'title' => ['Shopify product title', 'Our product name'],
                        'body_html' => ['Shopify description', 'Our product description'],
                        'vendor' => ['Shopify vendor', 'Our brand'],
                        'collection' => ['Shopify collection', 'Our category'],
                        'sku' => ['Shopify SKU', 'Our SKU'],
                        'price' => ['Shopify price', 'Our price'],
                        'compare_at_price' => ['Shopify compare-at price', 'Our compare price'],
                        'images' => ['Shopify images', 'Our product media'],
                    ];
                @endphp
                @foreach($labels as $from => $pair)
                    <div class="imp-map">
                        <div>{{ $pair[0] }}</div>
                        <div class="imp-arrow">↓</div>
                        <div>{{ $pair[1] }}</div>
                        <input type="hidden" name="mapping[{{ $from }}]" value="{{ $map[$from] ?? '' }}">
                    </div>
                @endforeach
            </div>

            <button class="sa-btn sa-btn-primary" type="submit">Continue</button>
        </form>
        @endif

        @if($step === 'preview' && $job)
        <div class="sa-card">
            <h3>Preview</h3>
            <div class="sa-grid sa-grid-4">
                <div class="sa-stat sa-card"><div class="label">Products</div><div class="value">{{ $preview['totals']['products'] ?? 0 }}</div></div>
                <div class="sa-stat sa-card"><div class="label">Categories</div><div class="value">{{ $preview['totals']['categories'] ?? 0 }}</div></div>
                <div class="sa-stat sa-card"><div class="label">Brands</div><div class="value">{{ $preview['totals']['brands'] ?? 0 }}</div></div>
                <div class="sa-stat sa-card"><div class="label">Images</div><div class="value">{{ $preview['totals']['images'] ?? 0 }}</div></div>
            </div>
            <p class="sa-muted">Variants (estimated): {{ $preview['totals']['variants'] ?? 0 }}</p>
            <table class="sa-table">
                <thead><tr><th>Title</th><th>Vendor</th><th>SKU</th><th>Price</th><th>Status</th></tr></thead>
                <tbody>
                @forelse(($preview['samples'] ?? []) as $row)
                    <tr>
                        <td>{{ $row['title'] }}</td>
                        <td>{{ $row['vendor'] }}</td>
                        <td>{{ $row['sku'] }}</td>
                        <td>{{ $row['price'] }}</td>
                        <td>{{ $row['status'] }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5">No sample products returned. Check your Shopify permissions.</td></tr>
                @endforelse
                </tbody>
            </table>
            <div class="imp-actions" style="margin-top:16px;">
                <a class="sa-btn sa-btn-secondary" href="{{ url('/admin/import-data?step=select') }}">Back</a>
                <form method="post" action="{{ url('/admin/import-data/start') }}" style="display:inline;">
                    @csrf
                    <button class="sa-btn sa-btn-primary" type="submit">Start import</button>
                </form>
            </div>
        </div>
        @endif

        @if($step === 'progress' && $job)
        <div class="sa-card" id="progressCard">
            <h3>Importing your store…</h3>
            <div class="imp-bar"><span id="impFill" style="width:8%"></span></div>
            <p id="impPct">8%</p>
            <ul class="imp-progress" id="impList">
                <li>Working…</li>
            </ul>
            <form method="post" action="{{ url('/admin/import-data/cancel') }}">@csrf<button class="sa-btn sa-btn-danger" type="submit">Cancel import</button></form>
        </div>
        @endif

        @if($step === 'done' && $job && $job->status === 'completed')
        <div class="sa-card">
            <h3>Import completed</h3>
            <ul class="imp-progress">
                <li>Products imported: {{ $counts['products']['imported'] ?? 0 }}</li>
                <li>Products updated: {{ $counts['products']['updated'] ?? 0 }}</li>
                <li>Categories imported: {{ ($counts['categories']['imported'] ?? 0) }}</li>
                <li>Brands imported: {{ $counts['brands']['imported'] ?? 0 }}</li>
                <li>Variants imported: {{ $counts['variants']['done'] ?? 0 }}</li>
                <li>Images imported: {{ $counts['images']['imported'] ?? 0 }}</li>
                <li>Skipped: {{ ($counts['products']['skipped'] ?? 0) + ($counts['categories']['skipped'] ?? 0) }}</li>
                <li>Failed: {{ ($counts['products']['failed'] ?? 0) + ($counts['images']['failed'] ?? 0) }}</li>
            </ul>
            <div class="imp-actions">
                <a class="sa-btn sa-btn-primary" href="{{ url('/admin/products') }}">View imported products</a>
                <a class="sa-btn sa-btn-secondary" href="{{ url('/admin/import-data/failed') }}">View failed items</a>
                <a class="sa-btn sa-btn-secondary" href="{{ url('/admin/import-data?step=select') }}">Import again</a>
            </div>
        </div>
        @endif
    @endif
</div>

<div class="modal fade" id="oauthModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="padding:20px;border-radius:12px;">
            <h3>Connect Shopify</h3>
            <p class="sa-muted">Enter your Shopify store address. You’ll be asked to approve access on Shopify.</p>
            <form method="post" action="{{ url('/admin/import-data/shopify/oauth') }}">
                @csrf
                <div class="sa-field">
                    <label>Shopify store URL</label>
                    <input name="shop" placeholder="your-store.myshopify.com" required>
                </div>
                <button class="sa-btn sa-btn-primary" type="submit">Continue to Shopify</button>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="manualModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="padding:20px;border-radius:12px;">
            <h3>Connect using API credentials</h3>
            <p class="sa-muted">Create a custom app in Shopify Admin, then paste the Admin API access token. The token is stored encrypted and never shown again.</p>
            <form method="post" action="{{ url('/admin/import-data/shopify/manual') }}">
                @csrf
                <div class="sa-field">
                    <label>Shopify store URL</label>
                    <input name="shop_url" placeholder="your-store.myshopify.com" value="{{ old('shop_url') }}" required>
                </div>
                <div class="sa-field">
                    <label>Admin API access token</label>
                    <input type="password" name="admin_api_token" autocomplete="new-password" required>
                </div>
                <button class="sa-btn sa-btn-primary" type="submit">Connect</button>
            </form>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
$('#selectAll').on('change', function(){ $('.type-box').prop('checked', this.checked); });
@if(($step ?? '') === 'progress')
function renderProgress(d){
  $('#impFill').css('width', (d.percent||0)+'%');
  $('#impPct').text((d.percent||0)+'%');
  var html = '';
  var c = d.counts || {};
  ['products','categories','brands','images','variants','orders'].forEach(function(k){
    if (!c[k]) return;
    var tot = c[k].total || 0;
    var n = (c[k].imported||0)+(c[k].updated||0)+(c[k].done||0)+(c[k].skipped||0);
    html += '<li>'+k.charAt(0).toUpperCase()+k.slice(1)+' '+n+(tot?(' / '+tot):'')+'</li>';
  });
  $('#impList').html(html || '<li>Starting…</li>');
  if (d.status === 'completed') { window.location = '{{ url('/admin/import-data?step=done') }}'; }
  if (d.status === 'cancelled' || d.status === 'failed') { window.location = '{{ url('/admin/import-data') }}'; }
}
function poll(){
  $.get('{{ url('/admin/import-data/progress') }}', renderProgress).always(function(){ setTimeout(poll, 2000); });
}
poll();
@endif
</script>
@endpush
