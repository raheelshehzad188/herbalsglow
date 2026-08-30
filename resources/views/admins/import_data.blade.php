@extends('admins.master')
@section('title','Import data')
@section('page_title','Import data')
@section('page_subtitle','Import a catalog from Shopify or WooCommerce')
@section('import_data','is-active')
@php
    $source = ($source ?? request('source')) === 'woocommerce' ? 'woocommerce' : 'shopify';
    $isWoo = $source === 'woocommerce';
    $sourceName = $isWoo ? 'WooCommerce' : 'Shopify';
    $pageQs = $isWoo ? 'source=woocommerce' : '';
    $pageUrl = url('/admin/import-data' . ($pageQs ? '?' . $pageQs : ''));
    $stepUrl = function ($step) use ($isWoo) {
        return url('/admin/import-data?' . ($isWoo ? 'source=woocommerce&' : '') . 'step=' . $step);
    };
    $testUrl = $isWoo ? url('/admin/import-data/woocommerce/test') : url('/admin/import-data/shopify/test');
    $fetchUrl = $isWoo ? url('/admin/import-data/woocommerce/fetch-products') : url('/admin/import-data/shopify/fetch-products');
    $disconnectUrl = $isWoo ? url('/admin/import-data/woocommerce/disconnect') : url('/admin/import-data/shopify/disconnect');
    $connectUrl = $isWoo ? url('/admin/import-data/woocommerce/connect') : url('/admin/import-data/shopify/connect');
    $configUrl = $isWoo ? url('/admin/import-data/woocommerce/config') : url('/admin/import-data/config');
    $startUrl = $isWoo ? url('/admin/import-data/woocommerce/start') : url('/admin/import-data/start');
    $tickUrl = $isWoo ? url('/admin/import-data/woocommerce/tick') : url('/admin/import-data/tick');
    $cancelUrl = $isWoo ? url('/admin/import-data/woocommerce/cancel') : url('/admin/import-data/cancel');
    $failedUrl = $isWoo ? url('/admin/import-data/woocommerce/failed') : url('/admin/import-data/failed');
    $storeLabel = '';
    $storeHost = '';
    if ($connection) {
        $storeLabel = $isWoo
            ? ($connection->shop_name ?: $connection->shop_host ?: $connection->shop_url)
            : ($connection->shop_name ?: $connection->shop_domain);
        $storeHost = $isWoo ? ($connection->shop_url ?: $connection->shop_host) : $connection->shop_domain;
    }
    $step = request('step', ($connection && $connection->isConnected()) ? 'select' : 'connect');
    if ($job && in_array($job->status, ['queued','running'])) { $step = 'progress'; }
    if ($job && $job->status === 'completed' && request('step') !== 'select') { $step = request('step', 'done'); }
    $cfg = $job ? $job->config() : [];
    $opt = $cfg['options'] ?? [];
    $sel = $cfg['types'] ?? ['products','collections','brands','images','variants','inventory'];
    $map = $cfg['mapping'] ?? $mapping;
    $preview = $job ? $job->preview() : [];
    $counts = $job ? $job->counts() : [];
    $connected = $connection && $connection->isConnected();
    $imports = $imports ?? collect();
@endphp
@section('content')
<div class="imp-wrap">
    <div class="imp-source-tabs">
        <a class="{{ !$isWoo ? 'on' : '' }}" href="{{ url('/admin/import-data?source=shopify') }}">Shopify</a>
        <a class="{{ $isWoo ? 'on' : '' }}" href="{{ url('/admin/import-data?source=woocommerce') }}">WooCommerce</a>
    </div>

    <div class="sa-card imp-hero">
        @if(!$connected)
            <h2>Connect {{ $sourceName }}</h2>
            @if($isWoo)
                <p>Enter this store’s WooCommerce URL, Consumer Key and Consumer Secret. Keys are stored encrypted and never shown in the browser.</p>
            @else
                <p>Enter this store’s Shopify URL, Client ID and Client Secret. ShopUS exchanges them for an access token on the server. The token is never shown in the browser.</p>
            @endif
        @else
            <h2>{{ $sourceName }} connected ✓</h2>
            <p>Store: <strong>{{ $storeLabel }}</strong></p>
            <p class="sa-muted">{{ $storeHost }}</p>
            <div class="imp-actions">
                <form method="post" action="{{ $testUrl }}" style="display:inline;">
                    @csrf
                    <button class="sa-btn sa-btn-secondary" type="submit">Test connection</button>
                </form>
                <form method="post" action="{{ $fetchUrl }}" style="display:inline;" id="fetchForm">
                    @csrf
                    <button class="sa-btn sa-btn-primary" type="submit" id="fetchBtn">Fetch products</button>
                </form>
                <form method="post" action="{{ $disconnectUrl }}" style="display:inline;">
                    @csrf
                    <button class="sa-btn sa-btn-danger" type="submit">Disconnect {{ $sourceName }}</button>
                </form>
            </div>
        @endif
    </div>

    @if(!$connected || request()->has('reconnect'))
    <div class="sa-card" id="store-keys">
        @if($isWoo)
            <h3>WooCommerce REST API</h3>
            <p class="sa-muted">Create a REST API key with <strong>Read</strong> permission. Do not paste WordPress admin passwords here.</p>
            <ol class="imp-howto">
                <li>Open WooCommerce → Settings → Advanced → REST API.</li>
                <li>Add key. Description: ShopUS import. Permissions: <strong>Read</strong> (or Read/Write if you also import orders that need extra access).</li>
                <li>Copy the <strong>Consumer key</strong> (<code>ck_</code>) and <strong>Consumer secret</strong> (<code>cs_</code>).</li>
                <li>Store URL example: <code>https://yourstore.com</code></li>
            </ol>
            <form method="post" action="{{ $connectUrl }}" style="margin-top:20px;" id="shopifyConnectForm">
                @csrf
                <div class="sa-field">
                    <label>WooCommerce store URL</label>
                    <input name="shop_url" placeholder="https://yourstore.com" value="{{ old('shop_url') }}" required>
                    <small>Accepted: yourstore.com or https://yourstore.com</small>
                </div>
                <div class="sa-field">
                    <label>Consumer key</label>
                    <input name="consumer_key" value="{{ old('consumer_key') }}" autocomplete="off" required>
                </div>
                <div class="sa-field">
                    <label>Consumer secret</label>
                    <input type="password" name="consumer_secret" autocomplete="new-password" required>
                    <small>Stored encrypted for this ShopUS store only. It is never shown again.</small>
                </div>
                <button class="sa-btn sa-btn-primary" type="submit" id="connectBtn">Connect WooCommerce</button>
            </form>
        @else
            <h3>Shopify Client Credentials</h3>
            <p class="sa-muted">Client Credentials authentication works for stores in the <strong>same Shopify organization</strong> as this app. Do not paste an Admin API access token (<code>shpat_</code>).</p>
            <ol class="imp-howto">
                <li>Open <a href="https://dev.shopify.com/dashboard" target="_blank" rel="noopener">Shopify Dev Dashboard</a> and create (or open) your app.</li>
                <li>Create an app version and enable <strong>Client credentials</strong> grant.</li>
                <li>Admin API scopes: <code>read_products</code>. Add an inventory read scope only if you need stock import.</li>
                <li>Install / release the version onto the Shopify store in the same organization.</li>
                <li>Copy <strong>Client ID</strong> and <strong>Client secret</strong> from the app settings.</li>
                <li>Store URL example: <code>zenmart.myshopify.com</code></li>
            </ol>
            <form method="post" action="{{ $connectUrl }}" style="margin-top:20px;" id="shopifyConnectForm">
                @csrf
                <div class="sa-field">
                    <label>Shopify store URL</label>
                    <input name="shop_url" placeholder="zenmart.myshopify.com" value="{{ old('shop_url') }}" required>
                    <small>Accepted: zenmart.myshopify.com or https://zenmart.myshopify.com</small>
                </div>
                <div class="sa-field">
                    <label>Client ID</label>
                    <input name="client_id" value="{{ old('client_id') }}" autocomplete="off" required>
                </div>
                <div class="sa-field">
                    <label>Client secret</label>
                    <input type="password" name="client_secret" autocomplete="new-password" required>
                    <small>Stored encrypted for this ShopUS store only. It is never shown again.</small>
                </div>
                <button class="sa-btn sa-btn-primary" type="submit" id="connectBtn">Connect Shopify</button>
            </form>
        @endif
    </div>
    @endif

    @if($imports->count())
    <div class="sa-card">
        <h3>Import history</h3>
        <table class="sa-table">
            <thead><tr><th>When</th><th>Status</th><th>Products</th><th>Categories</th><th>Brands</th></tr></thead>
            <tbody>
            @foreach($imports as $hist)
                @php $hc = $hist->counts(); @endphp
                <tr>
                    <td>{{ optional($hist->finished_at ?: $hist->started_at)->format('d M Y, h:i A') ?: '—' }}</td>
                    <td>{{ ucfirst($hist->status) }}</td>
                    <td>{{ ($hc['products']['imported'] ?? 0) + ($hc['products']['updated'] ?? 0) }}</td>
                    <td>{{ ($hc['categories']['imported'] ?? 0) + ($hc['categories']['updated'] ?? 0) }}</td>
                    <td>{{ ($hc['brands']['imported'] ?? 0) + ($hc['brands']['updated'] ?? 0) }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
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
        <form method="post" action="{{ $configUrl }}" id="importConfig">
            @csrf
            <div class="sa-card">
                <h3>Select data</h3>
                <p class="sa-muted">Customers are not imported. Orders are optional.</p>
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
                        <label class="sa-check"><input type="checkbox" name="opt_active" value="1" @if($opt['active'] ?? true) checked @endif> Import published products</label>
                        <label class="sa-check"><input type="checkbox" name="opt_draft" value="1" @if($opt['draft'] ?? false) checked @endif> Import draft products</label>
                        <label class="sa-check"><input type="checkbox" name="opt_archived" value="1" @if($opt['archived'] ?? false) checked @endif> Import {{ $isWoo ? 'private' : 'archived' }} products</label>
                        <label class="sa-check"><input type="checkbox" name="opt_descriptions" value="1" @if($opt['import_descriptions'] ?? true) checked @endif> Import product descriptions</label>
                        <label class="sa-check"><input type="checkbox" name="opt_images" value="1" @if($opt['import_images'] ?? true) checked @endif> Import images (downloaded locally)</label>
                        <label class="sa-check"><input type="checkbox" name="opt_variants" value="1" @if($opt['import_variants'] ?? true) checked @endif> Import variants</label>
                        <label class="sa-check"><input type="checkbox" name="opt_sku" value="1" @if($opt['import_sku'] ?? true) checked @endif> Import SKU</label>
                        <label class="sa-check"><input type="checkbox" name="opt_barcode" value="1" @if($opt['import_barcode'] ?? true) checked @endif> Import barcode</label>
                        <label class="sa-check"><input type="checkbox" name="opt_pricing" value="1" @if($opt['import_pricing'] ?? true) checked @endif> Import pricing</label>
                        <label class="sa-check"><input type="checkbox" name="opt_compare" value="1" @if($opt['import_compare'] ?? true) checked @endif> Import compare-at / regular pricing</label>
                        <label class="sa-check"><input type="checkbox" name="opt_inventory" value="1" @if($opt['import_inventory'] ?? true) checked @endif> Import inventory</label>
                    </div>
                    <div>
                        <p class="sa-section-title">Categories & brands</p>
                        <label class="sa-check"><input type="checkbox" name="opt_map_collections" value="1" @if($opt['map_collections'] ?? true) checked @endif> Map {{ $isWoo ? 'categories' : 'collections' }} to categories</label>
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
                <p class="sa-muted">{{ $sourceName }} slugs are kept as ShopUS slugs so product URLs stay the same per store.</p>
                @php
                    $labels = $isWoo ? [
                        'title' => ['WooCommerce product name', 'Our product name'],
                        'body_html' => ['WooCommerce description', 'Our product description'],
                        'vendor' => ['WooCommerce brand', 'Our brand'],
                        'collection' => ['WooCommerce category', 'Our category'],
                        'sku' => ['WooCommerce SKU', 'Our SKU'],
                        'price' => ['WooCommerce price', 'Our price'],
                        'compare_at_price' => ['WooCommerce regular price', 'Our compare price'],
                        'images' => ['WooCommerce images', 'Our product media'],
                    ] : [
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
                <tbody id="previewRows">
                @forelse(($preview['samples'] ?? []) as $row)
                    <tr>
                        <td>{{ $row['title'] }}</td>
                        <td>{{ $row['vendor'] }}</td>
                        <td>{{ $row['sku'] }}</td>
                        <td>{{ $row['price'] }}</td>
                        <td>{{ $row['status'] }}</td>
                    </tr>
                @empty
                    <tr id="previewEmpty"><td colspan="5">No sample products returned. Reconnect {{ $sourceName }} if this persists.</td></tr>
                @endforelse
                </tbody>
            </table>
            <div id="ajaxImportProgress" style="display:none;margin-top:18px;">
                <h4>Importing products…</h4>
                <div class="imp-bar"><span id="ajaxFill" style="width:0%"></span></div>
                <p id="ajaxPct">0%</p>
                <ul class="imp-progress" id="ajaxCounts"></ul>
                <p class="sa-muted" id="ajaxStage"></p>
            </div>
            <div id="ajaxImportDone" style="display:none;margin-top:18px;">
                <h4>Import completed ✓</h4>
                <ul class="imp-progress" id="ajaxDoneList"></ul>
                <div class="imp-actions">
                    <a class="sa-btn sa-btn-primary" href="{{ url('/admin/products') }}">View imported products</a>
                    <a class="sa-btn sa-btn-secondary" href="{{ $failedUrl }}">View failed items</a>
                    <a class="sa-btn sa-btn-secondary" href="{{ $stepUrl('select') }}">Import again</a>
                </div>
            </div>
            <div class="imp-actions" id="previewActions" style="margin-top:16px;">
                <a class="sa-btn sa-btn-secondary" href="{{ $stepUrl('select') }}">Back</a>
                <button class="sa-btn sa-btn-primary" type="button" id="startImportBtn">Start import</button>
                <button class="sa-btn sa-btn-danger" type="button" id="cancelImportBtn" style="display:none;">Cancel import</button>
            </div>
            <p id="ajaxImportError" class="sa-muted" style="display:none;color:#d72c0d;margin-top:10px;"></p>
        </div>
        @endif

        @if($step === 'progress' && $job)
        <div class="sa-card" id="progressCard">
            <h3>Importing {{ $sourceName }} store…</h3>
            <div class="imp-bar"><span id="impFill" style="width:8%"></span></div>
            <p id="impPct">8%</p>
            <ul class="imp-progress" id="impList">
                <li>Working…</li>
            </ul>
            <form method="post" action="{{ $cancelUrl }}">@csrf<button class="sa-btn sa-btn-danger" type="submit">Cancel import</button></form>
        </div>
        @endif

        @if($step === 'done' && $job && $job->status === 'completed')
        <div class="sa-card">
            <h3>Import completed ✓</h3>
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
                <a class="sa-btn sa-btn-secondary" href="{{ $failedUrl }}">View failed items</a>
                <a class="sa-btn sa-btn-secondary" href="{{ $stepUrl('select') }}">Import again</a>
            </div>
        </div>
        @endif
    @endif
</div>

@endsection
@push('scripts')
<script>
$('#selectAll').on('change', function(){ $('.type-box').prop('checked', this.checked); });
$('#shopifyConnectForm').on('submit', function(){ $('#connectBtn').prop('disabled', true).text('Connecting...'); });
$('#fetchForm').on('submit', function(){ $('#fetchBtn').prop('disabled', true).text('Fetching products...'); });
$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
    'X-Requested-With': 'XMLHttpRequest',
    'Accept': 'application/json'
  }
});
function countLine(c, k){
  if (!c[k]) return '';
  var tot = c[k].total || 0;
  var n = (c[k].imported||0)+(c[k].updated||0)+(c[k].done||0)+(c[k].skipped||0);
  return '<li>'+k.charAt(0).toUpperCase()+k.slice(1)+' '+n+(tot?(' / '+tot):'')+'</li>';
}
function renderProgress(d){
  $('#impFill').css('width', (d.percent||0)+'%');
  $('#impPct').text((d.percent||0)+'%');
  var html = '';
  var c = d.counts || {};
  ['products','categories','brands','images','variants','inventory','orders'].forEach(function(k){
    html += countLine(c, k);
  });
  $('#impList').html(html || '<li>Starting…</li>');
  if (d.status === 'completed') { window.location = '{{ $stepUrl('done') }}'; }
  if (d.status === 'cancelled' || d.status === 'failed') { window.location = '{{ $pageUrl }}'; }
}
@if(($step ?? '') === 'progress')
function pollTick(){
  $.post('{{ $tickUrl }}')
    .done(function(d){
      renderProgress(d);
      if (d.status === 'completed' || d.status === 'cancelled' || d.status === 'failed') return;
      pollTick();
    })
    .fail(function(){ setTimeout(pollTick, 2000); });
}
pollTick();
@endif
@if(($step ?? '') === 'preview')
(function(){
  var importing = false;
  var cancelled = false;
  function showError(msg){
    $('#ajaxImportError').text(msg || 'Import failed. Please try again.').show();
  }
  function appendBatch(batch){
    if (!batch || !batch.length) return;
    $('#previewEmpty').remove();
    batch.forEach(function(row){
      var tr = '<tr class="imp-row-'+row.status+'"><td>'+escapeHtml(row.title||'')+'</td><td></td><td>'+escapeHtml(row.sku||'')+'</td><td>'+escapeHtml(row.price||'')+'</td><td>'+escapeHtml(row.status||'')+'</td></tr>';
      $('#previewRows').prepend(tr);
    });
    var extra = $('#previewRows tr').slice(40);
    extra.remove();
  }
  function escapeHtml(s){
    return String(s).replace(/[&<>"']/g, function(ch){
      return ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[ch]);
    });
  }
  function paint(d){
    $('#ajaxFill').css('width', (d.percent||0)+'%');
    $('#ajaxPct').text((d.percent||0)+'%');
    var html = '';
    var c = d.counts || {};
    ['products','categories','brands','images','variants','inventory','orders'].forEach(function(k){
      html += countLine(c, k);
    });
    $('#ajaxCounts').html(html || '<li>Starting…</li>');
    if (d.stage) $('#ajaxStage').text('Stage: '+d.stage);
    appendBatch(d.batch || []);
  }
  function paintDone(d){
    var c = d.counts || {};
    $('#ajaxDoneList').html(
      '<li>Products imported: '+(c.products && c.products.imported || 0)+'</li>'+
      '<li>Products updated: '+(c.products && c.products.updated || 0)+'</li>'+
      '<li>Categories imported: '+(c.categories && c.categories.imported || 0)+'</li>'+
      '<li>Brands imported: '+(c.brands && c.brands.imported || 0)+'</li>'+
      '<li>Failed: '+(d.failed || 0)+'</li>'
    );
  }
  function tick(){
    if (cancelled) return;
    $.post('{{ $tickUrl }}')
      .done(function(d){
        paint(d);
        if (d.status === 'completed') {
          importing = false;
          $('#startImportBtn').hide();
          $('#cancelImportBtn').hide();
          $('#ajaxImportProgress h4').text('Import completed');
          $('#ajaxImportDone').show();
          paintDone(d);
          return;
        }
        if (d.status === 'cancelled' || d.status === 'failed') {
          importing = false;
          $('#startImportBtn').prop('disabled', false).text('Start import').show();
          $('#cancelImportBtn').hide();
          showError(d.status === 'cancelled' ? 'Import cancelled.' : (d.error || 'Import failed.'));
          return;
        }
        tick();
      })
      .fail(function(xhr){
        importing = false;
        $('#startImportBtn').prop('disabled', false).text('Start import');
        $('#cancelImportBtn').hide();
        var msg = (xhr.responseJSON && (xhr.responseJSON.error || xhr.responseJSON.message)) || 'Could not import this batch.';
        showError(msg);
      });
  }
  $('#startImportBtn').on('click', function(){
    if (importing) return;
    importing = true;
    cancelled = false;
    $('#ajaxImportError').hide();
    $('#ajaxImportDone').hide();
    $('#ajaxImportProgress').show();
    $('#startImportBtn').prop('disabled', true).text('Importing…');
    $('#cancelImportBtn').show();
    $.post('{{ $startUrl }}')
      .done(function(d){
        paint(d);
        tick();
      })
      .fail(function(xhr){
        importing = false;
        $('#startImportBtn').prop('disabled', false).text('Start import');
        $('#cancelImportBtn').hide();
        var msg = (xhr.responseJSON && (xhr.responseJSON.error || xhr.responseJSON.message)) || 'Could not start import.';
        showError(msg);
      });
  });
  $('#cancelImportBtn').on('click', function(){
    cancelled = true;
    $.post('{{ $cancelUrl }}').always(function(){
      importing = false;
      $('#startImportBtn').prop('disabled', false).text('Start import').show();
      $('#cancelImportBtn').hide();
      showError('Import cancelled.');
    });
  });
})();
@endif
</script>
@endpush
