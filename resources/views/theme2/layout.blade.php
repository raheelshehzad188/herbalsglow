
<!DOCTYPE html>
<html lang="en">
<?php
use App\Models\Admins\Pages;
use App\Models\Admins\Setting;
$Site= Setting::where(['id'=>'1'])->first();
  ?>
    <head>

<!-- Favicon  -->

    @if(isset($meta_file) && $meta_file)
    @include($meta_file) 
    @else
    
    @include('meta.default')
    @endif

   @if(isset($meta) && $meta)

    
@else
@if(isset($meta_file) && $meta_file != 'meta.brand' && $meta_file != 'meta.product_tag' && $meta_file != 'meta.brand_tag' && $meta_file != 'meta.page' && $meta_file != 'meta.blog_detail' )

@if(Session::has('title'))
    <title>{{Session::get('title')}} | {{$Site->site_title}}</title>
    @else
      <title>{{$Site->site_title}}</title>
      @endif
@endif
@endif

    <?php $Settings = Setting::where(['id'=>'1'])->get(); ?>
    @foreach($Settings as $Setting)
    <link rel="icon" type="image/x-icon" href="{{env('APP_URL')}}{{$Setting->logo1}}">
    <link rel="shortcut icon" href="{{env('APP_URL')}}{{$Setting->logo1}}" type="image/x-icon"/ >
    @endforeach
<style>
        :root {
		--primary-color: {{ isset($Site->primary_color) && !empty($Site->primary_color) ? $Site->primary_color : '#154880' }};
		--navbar-color: {{ isset($Site->navigation_color) && !empty($Site->navigation_color) ? $Site->navigation_color : '#F1A802' }};
		--btn-color: {{ isset($Site->button_color) && !empty($Site->button_color) ? $Site->button_color : '#154880' }};

	}
	.payment-gateway-item{
    	background: #e8e8e8;
        border: 1px solid #9a9a9a;
        border-radius: 6px;
        padding: 12px 0px 0px 0px;
        margin-bottom: 10px;
	}
	.payment-gateway-head{
	    margin-bottom: 12px;
        display: flex;
        gap: 8px;
        margin-left: 10px;
	}
	.payment-gateway-detail{
	    line-height: 22px;
        font-size: 14px;
        font-weight: 300;
        background: #fff;
        padding: 10px;
        border-bottom-left-radius: 6px;
        border-bottom-right-radius: 6px;
	}
	.payment-gateway-item.is-selected {
        background: #e8f7e8; /* light green */
        border: 1px solid #2e7d32; /* dark green */
    }
    
    .payment-gateway-item.is-selected .payment-gateway-head label {
        color: #1b5e20;
        font-weight: 600;
    }
    
    .payment-gateway-item.is-selected .payment-gateway-detail {
        background: #ffffff;
        border-bottom-left-radius: 6px;
        border-bottom-right-radius: 6px;
    }
</style>
<!--diyan-->




<style>
    .home-boxes-section {
  background: #f4f2ee;
  padding: 40px 20px;
  border-top: 1px solid #e0d8cc;
}

.home-boxes-row {
  display: flex;
  justify-content: center;
  align-items: center;
}

.home-box-item {
  flex: 1;
  text-align: center;
  padding: 10px 30px;
  border-right: 1px solid #ddd;
}

.home-box-item:last-child {
  border-right: none;
}

.home-box-icon {
  font-size: 34px;
  color: #6b7c4e;
  margin-bottom: 14px;
}

.home-box-title {
  font-size: 13px;
  font-weight: 700;
  color: #2e2e2e;
  letter-spacing: 1px;
  margin-bottom: 6px;
  text-transform: uppercase;
}

.home-box-subtitle {
  font-size: 12px;
  color: #6b7c4e;
  margin: 0;
  letter-spacing: .5px;
  text-transform: uppercase;
}

.home-box-subtitle.is-sentence {
  text-transform: none;
}

/* Responsive */
@media (max-width: 600px) {
  .home-boxes-row {
    flex-direction: column;
  }
  .home-box-item {
    border-right: none;
    border-bottom: 1px solid #ddd;
    padding: 18px;
    width: 100%;
  }
  .home-box-item:last-child {
    border-bottom: none;
  }
}

</style>
        <link rel="stylesheet" href="{{ $assets_url }}css/stylesheet.css">
        <link rel="stylesheet" href="{{ $assets_url }}css/responsive.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>	
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        
        {{-- Custom Head Scripts (Google Analytics, Facebook Pixel, etc) --}}
        @if(isset($Site->head_scripts) && !empty($Site->head_scripts))
            {!! $Site->head_scripts !!}
        @endif
    </head>
    <body>
    @include('theme2.header')
    @yield('content')
    @include('theme2.footer')
    <script src="{{ $assets_url }}js/ayanstore.js"></script>
    
    <script>
    // Quantity functions - global functions for onclick (must be defined before page loads)
    window.decreaseQty = function() {
        var qtyInput = document.getElementById('product-qty');
        if (qtyInput) {
            var currentQty = parseInt(qtyInput.value) || 1;
            var minQty = parseInt(qtyInput.getAttribute('min')) || 1;
            if(currentQty > minQty) {
                qtyInput.value = currentQty - 1;
                qtyInput.dispatchEvent(new Event('change'));
            }
        }
    };

    window.increaseQty = function() {
        var qtyInput = document.getElementById('product-qty');
        if (qtyInput) {
            var currentQty = parseInt(qtyInput.value) || 1;
            var maxQty = parseInt(qtyInput.getAttribute('max')) || 999;
            if(currentQty < maxQty) {
                qtyInput.value = currentQty + 1;
                qtyInput.dispatchEvent(new Event('change'));
            }
        }
    };
    
    // Buy Now function - ensures it's globally available
    window.buyNow = function(productId, buttonElement = null, quantity = null) {
        // Get quantity from input field if not provided
        if (quantity === null) {
            const qtyInput = document.getElementById('product_quantity');
            quantity = qtyInput ? parseInt(qtyInput.value) || 1 : 1;
        }
        
        // Show loading state - get button from parameter, event, or by class
        let buyNowBtn = buttonElement;
        if (!buyNowBtn && typeof event !== 'undefined' && event && event.target) {
            buyNowBtn = event.target;
        }
        if (!buyNowBtn) {
            buyNowBtn = document.querySelector('.buy-now-btn');
        }
        
        const originalText = buyNowBtn ? buyNowBtn.textContent : 'BUY NOW';
        if (buyNowBtn) {
            buyNowBtn.textContent = 'Processing...';
            buyNowBtn.disabled = true;
        }

        console.log('Buy Now clicked - Product ID:', productId, 'Quantity:', quantity);

        // Make AJAX request to add to cart
        fetch(window.location.origin + '/cart/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                id: productId,
                qty: quantity
            })
        })
        .then(response => {
            console.log('Cart add response status:', response.status);
            return response.json();
        })
        .then(data => {
            console.log('Cart add response data:', data);
            if (data.msg_type === 'success') {
                // Update cart count in header if function exists
                if (typeof updateCartCount === 'function') {
                    updateCartCount(data.qty);
                }
                
                // Show success message if function exists
                if (typeof showNotification === 'function') {
                    showNotification(data.msg, 'success');
                } else {
                    alert(data.msg || 'Product added to cart successfully!');
                }
                
                // Redirect to checkout after a short delay
                console.log('Redirecting to checkout...');
                setTimeout(() => {
                    console.log('About to redirect to:', window.location.origin + '/checkout');
                    window.location.href = window.location.origin + '/checkout';
                }, 1000);
            } else {
                console.error('Cart add failed:', data);
                if (typeof showNotification === 'function') {
                    showNotification(data.msg || 'Error adding to cart', 'error');
                } else {
                    alert(data.msg || 'Error adding to cart');
                }
                // Reset button state on error
                if (buyNowBtn) {
                    buyNowBtn.textContent = originalText;
                    buyNowBtn.disabled = false;
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            if (typeof showNotification === 'function') {
                showNotification('Error adding to cart', 'error');
            } else {
                alert('Error adding to cart');
            }
            // Reset button state on error
            if (buyNowBtn) {
                buyNowBtn.textContent = originalText;
                buyNowBtn.disabled = false;
            }
        });
    };
    
    // Search functionality
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    document.getElementById('searchForm').submit();
                }
            });
        }
    });
    </script>
    <script>
		document.addEventListener('DOMContentLoaded', function() {
		  const sliderSection = document.querySelector('.new-cat-slider');
		  if (sliderSection) {
			const inner = sliderSection.querySelector('.new-cat-slider-inner');
			const leftBtn = sliderSection.querySelector('.new-cat-left');
			const rightBtn = sliderSection.querySelector('.new-cat-right');
			const scrollAmt = 300; 

			leftBtn.addEventListener('click', () => {
			  inner.scrollBy({ left: -scrollAmt, behavior: 'smooth' });
			});
			rightBtn.addEventListener('click', () => {
			  inner.scrollBy({ left: scrollAmt, behavior: 'smooth' });
			});
		  }
		  
		  // Display session messages
		  @if(Session::has('message'))
		  @php
			$msg = Session::get('message');
			$msgType = Session::get('msg_type', 'success');
		  @endphp
		  (function() {
			const msg = @json($msg);
			const msgType = @json($msgType);
			
			if (msg && typeof showNotification === 'function') {
				showNotification(msg, msgType);
			} else if (msg) {
				alert(msg);
			}
		  })();
		  @endif
		});
		
		// Unified Mobile Search Function - Works for both Header and Footer
		(function() {
			const mobileSearchModal = document.getElementById('mobileSearchModal');
			const mobileSearchOverlay = document.getElementById('mobileSearchOverlay');
			const mobileSearchInput = document.getElementById('mobileSearchInput');
			const closeMobileSearchBtn = document.getElementById('closeMobileSearch');
			
			if (!mobileSearchModal || !mobileSearchOverlay) return;
			
			// Function to open mobile search modal
			function openMobileSearch() {
				mobileSearchModal.classList.add('active');
				mobileSearchOverlay.classList.add('active');
				// Focus on input when modal opens
				setTimeout(() => {
					if (mobileSearchInput) {
						mobileSearchInput.focus();
					}
				}, 100);
			}
			
			// Function to close mobile search modal
			function closeMobileSearch() {
				mobileSearchModal.classList.remove('active');
				mobileSearchOverlay.classList.remove('active');
				if (mobileSearchInput) {
					mobileSearchInput.value = '';
				}
				const mobileSearchResults = document.getElementById('mobileSearchResults');
				if (mobileSearchResults) {
					mobileSearchResults.innerHTML = '';
				}
			}
			
			// Attach event listeners to all elements with class 'open-mobile-search-btn'
			document.querySelectorAll('.open-mobile-search-btn').forEach(function(btn) {
				btn.addEventListener('click', function(e) {
					e.preventDefault();
					openMobileSearch();
				});
			});
			
			// Also handle the ID selector (for backward compatibility)
			const openMobileSearchById = document.getElementById('openMobileSearch');
			if (openMobileSearchById && !openMobileSearchById.classList.contains('open-mobile-search-btn')) {
				openMobileSearchById.addEventListener('click', function(e) {
					e.preventDefault();
					openMobileSearch();
				});
			}
			
			// Close button
			if (closeMobileSearchBtn) {
				closeMobileSearchBtn.addEventListener('click', function(e) {
					e.preventDefault();
					closeMobileSearch();
				});
			}
			
			// Close on overlay click
			if (mobileSearchOverlay) {
				mobileSearchOverlay.addEventListener('click', function() {
					closeMobileSearch();
				});
			}
			
			// Close on ESC key
			document.addEventListener('keydown', function(e) {
				if (e.key === 'Escape' && mobileSearchModal.classList.contains('active')) {
					closeMobileSearch();
				}
			});
		})();
		
		// Live Search Functionality
		(function() {
			const mobileSearchInput = document.getElementById('mobileSearchInput');
			const mobileSearchResults = document.getElementById('mobileSearchResults');
			let searchTimeout = null;
			
			if (!mobileSearchInput || !mobileSearchResults) return;
			
			// Live search on keyup
			mobileSearchInput.addEventListener('keyup', function(e) {
				const query = this.value.trim();
				
				// Clear previous timeout
				if (searchTimeout) {
					clearTimeout(searchTimeout);
				}
				
				// If query is less than 2 characters, clear results
				if (query.length < 2) {
					mobileSearchResults.innerHTML = '';
					return;
				}
				
				// Debounce search - wait 300ms after user stops typing
				searchTimeout = setTimeout(() => {
					performLiveSearch(query);
				}, 300);
			});
			
			// Clear results when input is empty
			mobileSearchInput.addEventListener('input', function() {
				if (this.value.trim().length === 0) {
					mobileSearchResults.innerHTML = '';
				}
			});
			
			// Perform live search
			function performLiveSearch(query) {
				if (!query || query.length < 2) {
					mobileSearchResults.innerHTML = '';
					return;
				}
				
				// Show loading state with spinner
				mobileSearchResults.innerHTML = `
					<div class="search-loading">
						<div class="search-spinner"></div>
						<p>Searching...</p>
					</div>
				`;
				
				// Make AJAX request
				fetch('/api/live-search?q=' + encodeURIComponent(query), {
					method: 'GET',
					headers: {
						'Content-Type': 'application/json',
						'X-Requested-With': 'XMLHttpRequest'
					}
				})
				.then(response => response.json())
				.then(data => {
					displaySearchResults(data.products);
				})
				.catch(error => {
					console.error('Search error:', error);
					mobileSearchResults.innerHTML = '<div class="search-error">Error searching products. Please try again.</div>';
				});
			}
			
			// Display search results
			function displaySearchResults(products) {
				if (!products || products.length === 0) {
					mobileSearchResults.innerHTML = '<div class="search-no-results">No products found</div>';
					return;
				}
				
				const baseUrl = window.location.origin;
				const assetsUrl = baseUrl + '/theme2/';
				
				let html = '<ul class="search-results-list">';
				products.forEach(product => {
					let imageUrl = product.image || '';
					if (imageUrl && !imageUrl.startsWith('http')) {
						imageUrl = baseUrl + '/' + imageUrl;
					} else if (!imageUrl) {
						imageUrl = assetsUrl + 'img/solo.webp';
					}
					
					html += '<li class="search-result-item">';
					html += '<a href="' + product.url + '">';
					html += '<div class="search-result-image">';
					html += '<img src="' + imageUrl + '" alt="' + product.name + '" onerror="this.src=\'' + assetsUrl + 'img/solo.webp\'">';
					html += '</div>';
					html += '<div class="search-result-info">';
					html += '<h4>' + product.name + '</h4>';
					html += '<p class="search-result-price">Rs: ' + product.price + '</p>';
					html += '</div>';
					html += '</a>';
					html += '</li>';
				});
				html += '</ul>';
				
				mobileSearchResults.innerHTML = html;
			}
		})();
	</script>
    </body>
</html>
