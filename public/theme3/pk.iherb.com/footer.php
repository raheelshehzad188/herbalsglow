<footer class="iherb-responsive " style="display: none">
<link itemprop="url" href="index.html"/>
<link itemprop="logo" href="https://cloudinary.images-iherb.com/image/upload/f_auto,q_auto:eco/images/static/i/menu/iherb-logo.png"/>
<link itemprop="name" content="iHerb"/>

<div id="ih-public-footer">
    

<section id="recently-viewed-footer" class="recently-viewed-products product-mini-carousel" style="display:none;">
    <div class="container-fluid">
        <div class="row">
            <div class="title-container">
                <div class="title">
                    <bdi>Recently viewed products</bdi>
                </div>
            </div>
            <div class="recently-viewed-carousel-container">
                <div class="carousel">
                    <div id="carousel-recently-viewed"
                         class="carousel slide iherb-carousel-items"
                         data-lazyload="product"
                         data-interval="false">
                        <div id="recently-viewed-inner" class="carousel-inner product-carousels rounded-product-cells">
                        </div>

                        <a class="left carousel-control" href="#carousel-recently-viewed" role="button" data-slide="prev">
                            <span class="scroll-icon scroll-l" aria-label="Previous set of recently viewed products">
                                <i class="icon-recentlyviewedarrowleft"></i>
                            </span>
                        </a>
                        <a class="right carousel-control" href="#carousel-recently-viewed" role="button" data-slide="next">
                            <span class="scroll-icon scroll-r" aria-label="Next set of Recently Viewed Products">
                                <i class="icon-recentlyviewedarrowright"></i>
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
    <section id="iherb-live"></section>

</div>
    <section class="footer-banner-container container-fluid">
            <div class="rewards-banner-container"><style>
  .cms-text-banner-wrapper .cms-banner-wrapper-root-link{display:block;width:100%;} 
  .cms-text-banner {text-align:initial;width:100%;height:auto;overflow:hidden;box-sizing:border-box;}
  .cms-text-banner-wrapper a{text-decoration: none;color:unset;}
  .cms-text-banner-wrapper a:hover{text-decoration: none;}
  .cms-text-banner span a{color:unset;}
  .cms-text-banner .cms-banner-wrapper-root{box-sizing:border-box;width:100%;margin:auto;}
  .cms-layout-635510 .cms-banner-wrapper-root{
      position:relative;
      overflow:hidden;
      background-color:;
      background-image:url(https://cloudinary.images-iherb.com/image/upload/f_auto,q_auto:eco/images/campaign/3772431785074842866ff33797313891.jpg);
      background-size:cover;
      background-position:center;
      background-repeat:no-repeat;
      padding:0px 10px;
      justify-content:center;
      align-items:center;
      display: flex; padding-inline-start: ; padding-block-start: ;
      	
  }        
  .cms-text-banner img{display:block;border-style: none;}
  .cms-text-banner cms-icon,.cms-text-banner svg{display:block;flex-shrink:0;}
  .cms-text-banner .custom-flex-box-hide{display:none;}
  .cms-text-banner[data-height-type='fixed'].cms-layout-635510 .cms-banner-wrapper-root{height:55px;}
  .cms-text-banner[data-height-type='ratio'].cms-layout-635510 .cms-banner-wrapper-root{aspect-ratio:1900 / 55}  
  .cms-text-banner[data-fix-flag='1'].cms-layout-635510 .cms-banner-wrapper-root{width:1900px;height:55px}  
  
    
  
  .img-responsive {display: block;max-width: 100%;height: auto;}    
  
  
  
</style>    
<div class="cms-banner-wrapper cms-text-banner-wrapper">
<a href="https://secure.iherb.com/iherb-rewards" class="cms-banner-wrapper-root-link" data-ga-event="click" data-ga-event-category="rewards" data-ga-event-label="learn more" data-ga-event-action="universal banner" title="iHerb | Rewards: Get free products, insider access, and exclusive offers!" aria-label="">
<div class="cms-text-banner  cms-layout-635510" data-campaign-id="9839" data-height-type="fixed" data-justify-content="center" data-align-items="center" data-fix-flag="">
  
  <div class="cms-banner-wrapper-root">
    <div style="background-repeat:no-repeat;background-size:contain;background-position:right;"><div class="custom-flex-box" style="display: flex; flex-flow: row nowrap; place-content: center; align-items: center; white-space: normal;height:55px;"><div class="custom-flex-box-item" style="margin: 0px 24px 0px 0px; text-align: center;"><img src="https://s3.images-iherb.com/cms/logos/rewards/iHerbRewards_Horz_RGB_en-us.png" class="fr-fic fr-dib" alt="iHerb Rewards" style="width: auto; height: 28px;"></div><div class="custom-flex-box-item" style="text-align: center;"><span style="font-weight: 900; font-style: normal; font-size: 16px; color: #000000;">Get free products, insider access, and exclusive offers!</span></div></div></div>   
  </div>   
  
</div>
</a>
</div>
</div>
    </section>

<div id="quality-promise-footer"></div>
<script>
(function() {
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', updateGoogleStoreLink);
    } else {
        updateGoogleStoreLink();
    }

    function getCookie(name) {
        const value = `; ${document.cookie}`;
        const parts = value.split(`; ${name}=`);
        if (parts.length === 2) return parts.pop().split(';').shift();
        return null;
    }

    function updateGoogleStoreLink() {
        try {
            const rawCookie = getCookie('ih-preference');
            if (!rawCookie) return; // Exit silently if cookie isn't set
            
            const ihPreference = decodeURIComponent(rawCookie);

            const params = new URLSearchParams(ihPreference);
            const country = params.get('country');
            let language = params.get('language');

            if (!country || !language) return;

            language = language.replace('-', '_');

            const storeLink = document.querySelector('a[href*="google.com/storepages"]');
            if (!storeLink) return; // Exit if the link doesn't exist on this specific page

            const url = new URL(storelink.href.html);
            url.searchParams.set('c', country);
            url.searchParams.set('hl', language);
            
            storeLink.href = url.toString();



        } catch (error) {

            console.error("Link update failed safely:", error);
        }
    }
})();
</script>
<style>

#iherb-history {
  margin-top: 32px;
  margin-bottom: 32px;
}
#iherb-history > * > * {
  color: #181B1F !important;
}
#iherb-history.container-fluid {
  padding: 0;
  max-width: 1376px;
}
#iherb-history a {
  text-decoration: none;
  color: #181B1F;
}
#iherb-history a:hover {
  text-decoration: underline;
}
#iherb-history .iherb-history-main {
  margin-right: 32px;
}
#iherb-history .iherb-logo {
  margin-bottom: 18px;
}
#iherb-history .iherb-logo img {
  width: 82px;
  height: 28px;
}
#iherb-history {
  display: flex;
  flex-direction: row;
}
#iherb-history .icon-iherblogo {
  color: #458500;
  width: 82px;
  height: 28px;
  margin-bottom: 12px;
}
#iherb-history .iherb-history-title {
  color: #181b1f;
  font-size: 16px;
  font-weight: 400;
  line-height: 24px;
}
#iherb-history .review-card {
  display: flex;
  margin-right: 32px;
  align-items: center;
}
#iherb-history .review-card-image {
  width: 32px;
  height: 32px;
  min-width: 32px;
  min-height: 32px;
  background-color: #458500;
  border-radius: 8px;
  padding: 4px;
  margin-right: 8px;
  display: flex;
}
[dir=rtl] #iherb-history .review-card-image {
  margin-right: unset;
  margin-left: 8px;
}

#iherb-history .review-card-image img {
  width: 100%;
  height: 100%;
}
#iherb-history .review-card-number {
  color: #181B1F;
  font-size: 14px;
  font-weight: 700;
  line-height: 20px;
  margin-right: 8px;
}
#iherb-history .iherb-reviews {
  display: flex;
  align-items: center;
}
#iherb-history .review-card-rating {
  display: flex;
  align-items: center;
}
#iherb-history .review-card-star {
  width: 13px;
  height: 13px;
  display: flex;
  align-items: center;
  margin-right: 2px;
}
#iherb-history .review-card-star a {
  display: flex;
  align-items: center;
  justify-content: center;
}
#iherb-history .review-card-star img {
  width: 100%;
  height: 100%;
}
#iherb-history .content-description-images {
  display: flex;
}
#iherb-history .iherb-reviews-google {
  display: flex;
  align-items: center;
  margin-right: 32px;
}
#iherb-history .iherb-reviews-google a {
  display: flex;
  align-items: center;
}
#iherb-history .iherb-reviews-google img {
  width: 32px;
  height: 32px;
  margin-right: 8px;
}
[dir=rtl] #iherb-history .iherb-reviews-google img {
  margin-right: unset;
  margin-left: 8px;
}
[dir=rtl] #iherb-history .iherb-history-main {
  margin-right: 0px !important;
}
[dir=rtl] #iherb-history .content-description-images {
	margin-right: 32px;    
}

#iherb-history .iherb-reviews-google-description {
  color: #181B1F;
  font-size: 14px;
  line-height: 20px;
  word-wrap: break-word;
  min-width: 120px;
}
.iherb-reviews-google-description-google {
  color: #53575A;
}
#iherb-history .review-card-description {
  color: #181B1F;
  font-size: 14px;
  font-weight: 400;
  line-height: 20px;
  white-space: nowrap;
}
#iherb-history .description-image-container {
  margin-right: 8px;
  display: flex;
  align-items: center;
}
#iherb-history .description-image-container img {
  width: 36px;
  height: 32px;
}
#iherb-history .review-card-column {
  display: flex;
  flex-direction: column;
}
@media (max-width: 1000px) {
  #iherb-history {
    flex-direction: column !important;
  }
  .iherb-history-main {
    margin-bottom: 12px;
  }
}
</style>

<div class="iherb-history-wrap container-fluid">
    <div class="iherb-header-ccl"
         tabindex="0"
         data-url="pro/countryselected.html">
        <svg class="icon icon-ccl-globe-white">
            <use xlink:href="#icon-ccl-globe-black"></use>
        </svg>
        <svg style="position: absolute; width: 0; height: 0; overflow: hidden" version="1.1" xmlns="http://www.w3.org/2000/svg"
    xmlns:xlink="http://www.w3.org/1999/xlink">
    <defs>
        <symbol id="icon-checkoutarrow" viewBox="0 0 18 28">
            <title>checkoutarrow</title>
            <path d="M1.077 1.077l15.077 12.923-15.077 12.923v-25.846z"></path>
        </symbol>
    </defs>
</svg>
<style>
    .icon-checkoutarrow {
        height: 7px;
        width: 5px;
    }
</style>
    <button class="selected-country-wrapper" data-on="off" aria-haspopup="true" aria-expanded="false"
            aria-label="Locale menu in header"
    >
        <div class="country-select">
            <span class="country-code-flag">PK</span>
        </div>
        <div class="language-select hidden-xs hidden-sm">
            <span>EN</span>
        </div>
        <div class="currency-select hidden-xs hidden-sm">
            <span>PKR</span>
        </div>
    </button>

    </div>                
    <div id="iherb-history">
            <div class="iherb-history-main">
                <div class="iherb-logo">
                    <img src="https://s3.images-iherb.com/cms/images/logos/logo-iHerb.svg"/>
                </div>
                <div class="iherb-history-title">
                   Since 1996, iHerb has been dedicated to making trusted health and wellness products accessible to all.
                </div>
            </div>
            <div class="iherb-reviews">
                <div class="iherb-reviews-google">
                    <a href="https://www.google.com/storepages?q=iherb.com&amp;c=US&amp;v=19&amp;hl=en_US">
  <img src="https://s3.images-iherb.com/cms/icons/info/google store.svg" alt="google play">
<div class="iherb-reviews-google-description">
  <div>Top Quality Store</div>
  <div class="iherb-reviews-google-description-google">on Google</div>
</div>
</a>
                    
                </div>
                <div class="iherb-reviews">
                    <a href="https://www.iherb.com/ugc/store-reviews">
                        <div class="review-card">
                            <div class="review-card-image">
                                <img src="https://s3.images-iherb.com/cms/icons/info/iHerb.svg" alt="iherb logo">
                            </div>
                            <div class="review-card-column">
                                <div class="review-card-rating">
                                    <div class="review-card-number">
                                        <bold>4.8</bold>
                                    </div>
                                    <div class="review-card-star">
                                        <img src="https://s3.images-iherb.com/cms/icons/info/star.svg" alt="green star ">
                                    </div>
                                    <div class="review-card-star">
                                        <img src="https://s3.images-iherb.com/cms/icons/info/star.svg" alt="green star ">
                                    </div>
                                    <div class="review-card-star">
                                        <img src="https://s3.images-iherb.com/cms/icons/info/star.svg" alt="green star ">
                                    </div>
                                    <div class="review-card-star">
                                        <img src="https://s3.images-iherb.com/cms/icons/info/star.svg" alt="green star ">
                                    </div>
                                    <div class="review-card-star">
                                        <img src="https://s3.images-iherb.com/cms/icons/star_three-quarters.svg" alt="green three-quarter star ">
                                    </div>
                                </div>
                                <div class="review-card-description">
                                    iHerb store reviews
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="content-description-images">
                    <div class="description-image-container">
                        <img src="https://s3.images-iherb.com/cms/icons/info/ISO.svg" alt="ISO">
                    </div>
                    <div class="description-image-container">
                        <img src="https://s3.images-iherb.com/cms/icons/info/GMP.svg" alt="GMP certified">
                    </div>
                    <div class="description-image-container">
                        <img src="https://s3.images-iherb.com/cms/icons/info/NSF.svg" alt="NSF">
                    </div>
                </div>
            </div>
        </div>
    <div class="separator"></div>
</div>



<div id="wellness-chatbot-container" data-view-state="closed"></div>


<div class="email-subcription-banner" style="display: none">
    <div class="email-subscription-container">
    <div class="email-subscription-content">
        <div class="title email-subscription-title">Sign up for iHerb Emails and Get Deals Directly to Your Inbox.</div>

        <form class="form-inline" novalidate>
            <label>
    <span class="email-subscription-text-description"> Sign up for iHerb Emails and Get Deals Directly to Your Inbox.</span>
</label>
<div class="email-subscription-input-and-validation-wrapper">
    <div class="email-subscription-input-wrapper">
        <div>
            <input class="form-control email-subscription" type="email" name="email" maxlength="256" required placeholder="Email" />
            <div class="email-subcription-inline-error">
                <span>
                    <svg class="icon icon-circleexclamation-v2">
                        <use xlink:href="#icon-circleexclamation-v2"></use>
                    </svg>
                </span>
            </div>
        </div>
        <a  
            data-ga-event="click"
            data-ga-event-category="Ecommerce"
            data-ga-event-action="prospect-mailing"
            data-ga-event-label="banner">
            <button type="button" class="btn btn-success btn-email-subscription">
                <svg
                      xmlns="http://www.w3.org/2000/svg"
                      width="24"
                      height="24"
                      viewBox="0 0 24 24"
                      fill="none"
                >
                  <path
                    d="M19 11.2499C19.4142 11.2499 19.75 11.5857 19.75 11.9999C19.75 12.4142 19.4142 12.7499 19 12.7499H5C4.58579 12.7499 4.25 12.4142 4.25 11.9999C4.25 11.5857 4.58579 11.2499 5 11.2499H19Z"
                    fill="white"
                  />
                  <path
                    d="M18.4697 11.4697C18.7626 11.1768 19.2374 11.1768 19.5303 11.4697C19.8232 11.7626 19.8232 12.2373 19.5303 12.5302L13.5303 18.5302C13.2374 18.8231 12.7626 18.8231 12.4697 18.5302C12.1768 18.2373 12.1768 17.7626 12.4697 17.4697L18.4697 11.4697Z"
                    fill="white"
                  />
                  <path
                    d="M12.4697 5.46967C12.7626 5.17678 13.2374 5.17678 13.5303 5.46967L19.5303 11.4697C19.8232 11.7626 19.8232 12.2373 19.5303 12.5302C19.2374 12.8231 18.7626 12.8231 18.4697 12.5302L12.4697 6.53022C12.1768 6.23732 12.1768 5.76256 12.4697 5.46967Z"
                    fill="white"
                  />
                </svg>
            </button>
        </a>
    </div>
    <div class="invalid-feedback">
        <span>
            <svg class="icon icon-circleexclamation-v2">
                <use xlink:href="#icon-circleexclamation-v2"></use>
            </svg>
        </span>
        Please enter a valid email address
    </div>
    <div class="valid-feedback">
        <span>
            <svg class="icon icon-circlecheckfat">
                <use xlink:href="#icon-circlecheckfat"></use>
            </svg>
        </span>
        You are now subscribed to our emails
    </div>
</div>
        </form>

        <div class="legal-text">
            Your email address will be used to send you Health Newsletters and emails about iHerb’s products, services, sales, and special offers. You can unsubscribe at any time by clicking on the unsubscribe link in each email. For more information on our use of your personal information and your rights, see our <a href="info/privacy.html">Privacy Policy.</a>
        </div>
    </div>
</div>

    <div class="email-banner-close">
        <svg width="24" height="24" class="icon icon-x-close">
            <use xlink:href="#icon-x-close"></use>
        </svg>
    </div>
</div>

<section class="footer-content">
    <div class="container-fluid footer-info-links">
                <style>
                        .footer-section {
                            width: 15%;
                        }
                        @media (max-width: 801px)
                        {
                            .footer-section {
                                width: 20%;
                            }
                        }

                    </style>
                <div class="footer-section ">
                    <ul>
                        <li class="title">About</li>
                                <li class="">
                                    <a href="info/about.html" target="_self" rel="" data-ga-event="click" data-ga-event-category="Ecommerce" data-ga-event-action="Footer-AboutiHerb" data-ga-event-label="About Us" >
About Us                                    </a>
                                </li>
                                <li class="">
                                    <a href="https://www.iherb.com/ugc/store-reviews" target="_self" rel="" data-ga-event="click" data-ga-event-category="Ecommerce" data-ga-event-action="Footer-AboutiHerb" data-ga-event-label="Store Reviews" data-ga-event-name="link_click" data-ga-interaction-type="store reviews" data-ga-location-detail="footer link">
Store Reviews<span class="badge">New</span>                                    </a>
                                </li>
                                <li class="">
                                    <a href="https://secure.iherb.com/iherb-rewards" target="_self" rel="" data-ga-event="click" data-ga-event-category="Ecommerce" data-ga-event-action="Footer-AboutiHerb" data-ga-event-label="Rewards Program" >
Rewards Program                                    </a>
                                </li>
                                <li class="">
                                    <a href="info/affiliates.html" target="_self" rel="" data-ga-event="click" data-ga-event-category="Ecommerce" data-ga-event-action="Footer-AboutiHerb" data-ga-event-label="Affiliate Program" >
Affiliate Program                                    </a>
                                </li>
                                <li class="">
                                    <a href="info/quality.html" target="_self" rel="" data-ga-event="click" data-ga-event-category="Ecommerce" data-ga-event-action="Footer-AboutiHerb" data-ga-event-label="iHerb Quality Promise" >
iHerb Quality Promise                                    </a>
                                </li>
                    </ul>
                </div>
                <div class="footer-section ">
                    <ul>
                        <li class="title">Company</li>
                                <li class="english-only">
                                    <a href="https://corporate.iherb.com/" target="_blank" rel="noopener" data-ga-event="click" data-ga-event-category="Ecommerce" data-ga-event-action="Footer-CompanyiHerb" data-ga-event-label="Corporate" >
Corporate                                    </a>
                                </li>
                                <li class="">
                                    <a href="pressreleases.html" target="_self" rel="" data-ga-event="click" data-ga-event-category="Ecommerce" data-ga-event-action="Footer-CompanyiHerb" data-ga-event-label="Press" >
Press                                    </a>
                                </li>
                                <li class="">
                                    <a href="info/suppliers.html" target="_self" rel="" data-ga-event="click" data-ga-event-category="Ecommerce" data-ga-event-action="Footer-CompanyiHerb" data-ga-event-label="Supplier Partners" >
Partner with iHerb                                    </a>
                                </li>
                    </ul>
                </div>
                <div class="footer-section ">
                    <ul>
                        <li class="title">Resources</li>
                                <li class="">
                                    <a href="blog.html" target="_self" rel="" data-ga-event="click" data-ga-event-category="Ecommerce" data-ga-event-action="Footer-ResourcesiHerb" data-ga-event-label="iHerb Blog" >
Wellness Hub                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="userway-widget">Accessibility View</a>
                                </li>
                                <li class="">
                                    <a href="info/sales-and-offers.html" target="_self" rel="" data-ga-event="click" data-ga-event-category="Ecommerce" data-ga-event-action="Footer-ResourcesiHerb" data-ga-event-label="Sales &amp; Offers" >
Sales &amp; Offers                                    </a>
                                </li>
                    </ul>
                </div>
                <div class="footer-section ">
                    <ul>
                        <li class="title">Customer Service</li>
                                <li class="">
                                    <a href="help/contact.html" target="_self" rel="" data-ga-event="click" data-ga-event-category="Ecommerce" data-ga-event-action="Footer-Customer ServiceiHerb" data-ga-event-label="Contact Us" >
24/7 Support                                    </a>
                                </li>
                                <li class="">
                                    <a href="css/request-product.html" target="_self" rel="" data-ga-event="click" data-ga-event-category="Ecommerce" data-ga-event-action="Footer-Customer ServiceiHerb" data-ga-event-label="Suggest a Product" >
Suggest a Product                                    </a>
                                </li>
                                <li class="">
                                    <a href="https://secure.iherb.com/orders/gc-tracking" target="_self" rel="" data-ga-event="click" data-ga-event-category="Ecommerce" data-ga-event-action="Footer-Customer ServiceiHerb" data-ga-event-label="Order Status" >
Order Status                                    </a>
                                </li>
                                <li class="">
                                    <a href="shipping.html" target="_self" rel="" data-ga-event="click" data-ga-event-category="Ecommerce" data-ga-event-action="Footer-Customer ServiceiHerb" data-ga-event-label="Shipping" >
Shipping                                    </a>
                                </li>
                                <li class="">
                                    <a href="info/returns.html" target="_self" rel="" data-ga-event="click" data-ga-event-category="Ecommerce" data-ga-event-action="Footer-Customer ServiceiHerb" data-ga-event-label="refunds and returns" >
Returns                                    </a>
                                </li>
                                <li class="">
                                    <a href="https://secure.iherb.com/communications/preferences" target="_self" rel="" data-ga-event="click" data-ga-event-category="Ecommerce" data-ga-event-action="Footer-Customer ServiceiHerb" data-ga-event-label="Communication Preferences" >
Communication Preferences                                    </a>
                                </li>
                    </ul>
                </div>
    </div>

    <div class="container-fluid">
        <div class="separator"></div>
        <div class="apps-email-social">
            <div class="app-links-and-social">
                <div class="mobile-app-links mobile-apps-links-desktop">
                    
<div class="mobile-apps-container">
    <div class="qr-wrapper" style="width: 120px;">
        <img src="https://cloudinary.images-iherb.com/image/upload/f_auto,q_auto:eco/images/cms/static/Sitefooter_download_App_QRcode_9_24_24.png" class="qr-code-image img-responsive" alt="QR Code App" />
    </div>
    <div class="mobile-apps-right-wrap">
        <div class="mobile-apps-title">Get the iHerb App Today</div>
        <div class="mobile-apps-description">The smartest way to shop and save.</div>
        <div class="mobile-icons">
                <div class="mobile-icons-0">
                    <a href="https://play.google.com/store/apps/details?id=com.iherb" target="_blank" rel="noopener">
                        <img class="img-responsive" src="https://cloudinary.images-iherb.com/image/upload/f_auto,q_auto:eco/images/cms/footer/google-play-badge.png" alt="iHerb App on Google Play Store" aria-label="iHerb App on Google Play Store"/>
                    </a>
                </div>
                <div class="mobile-icons-1">
                    <a href="https://itunes.apple.com/us/app/iherb/id636609212?mt=8" target="_blank" rel="noopener">
                        <img class="img-responsive" src="https://cloudinary.images-iherb.com/image/upload/f_auto,q_auto:eco/images/cms/footer/ios_app_store_us.png" alt="iHerb App on Apple App Store" aria-label="iHerb App on Apple App Store"/>
                    </a>
                </div>
        </div>
    </div>
</div>
                    <ul class="social-media-icons">
                                <li>
                                    <a itemprop="sameAs" href="https://www.facebook.com/iHerb" target="_blank" rel="noopener" aria-label="Facebook Share Icon">
                                            <img alt="Facebook share icon" src="https://cloudinary.images-iherb.com/image/upload/f_auto,q_auto:eco/images/cms/logos/social-media/facebook-logo.png" class="icon"/>
                                    </a>
                                </li>
                                <li>
                                    <a itemprop="sameAs" href="https://twitter.com/iherb" target="_blank" rel="noopener" aria-label="Twitter Share Icon">
                                            <img alt="Twitter share icon" src="https://cloudinary.images-iherb.com/image/upload/f_auto,q_auto:eco/images/cms/logos/social-media/twitter-x-logo.png" class="icon"/>
                                    </a>
                                </li>
                                <li>
                                    <a itemprop="sameAs" href="https://www.youtube.com/c/iherb" target="_blank" rel="noopener" aria-label="YouTube Share Icon">
                                            <img alt="YouTube share icon" src="https://cloudinary.images-iherb.com/image/upload/f_auto,q_auto:eco/images/cms/logos/social-media/youtube-logo.png" class="icon"/>
                                    </a>
                                </li>
                                <li>
                                    <a itemprop="sameAs" href="http://www.pinterest.com/iherb/" target="_blank" rel="noopener" aria-label="Pinterest Share Icon">
                                            <img alt="Pinterest share icon" src="https://cloudinary.images-iherb.com/image/upload/f_auto,q_auto:eco/images/cms/logos/social-media/pinterest-logo.png" class="icon"/>
                                    </a>
                                </li>
                                <li>
                                    <a itemprop="sameAs" href="http://instagram.com/iherb" target="_blank" rel="noopener" aria-label="Instagram Share Icon">
                                            <img alt="Instagram share icon" src="https://cloudinary.images-iherb.com/image/upload/f_auto,q_auto:eco/images/cms/logos/social-media/instagram-logo.png" class="icon"/>
                                    </a>
                                </li>
                    </ul>
                </div>
            </div>
            <div style="display: flex; flex-direction: column; align-items: center;">
                <div class="call-to-action-container">
                    <div class="email-subscription-container">
    <div class="email-subscription-title">Sign up for savings</div>
    <div class="email-subscription-desc">Be the first to get promo offers and reward perks straight to your inbox.</div>
    
    <form class="form-inline" novalidate>
        <label>
    <span class="email-subscription-text-description"> Sign up for iHerb Emails and Get Deals Directly to Your Inbox.</span>
</label>
<div class="email-subscription-input-and-validation-wrapper">
    <div class="email-subscription-input-wrapper">
        <div>
            <input class="form-control email-subscription" type="email" name="email" maxlength="256" required placeholder="Email" />
            <div class="email-subcription-inline-error">
                <span>
                    <svg class="icon icon-circleexclamation-v2">
                        <use xlink:href="#icon-circleexclamation-v2"></use>
                    </svg>
                </span>
            </div>
        </div>
        <a  
            data-ga-event="click"
            data-ga-event-category="Ecommerce"
            data-ga-event-action="prospect-mailing"
            data-ga-event-label="footer">
            <button type="button" class="btn btn-success btn-email-subscription">
                <svg
                      xmlns="http://www.w3.org/2000/svg"
                      width="24"
                      height="24"
                      viewBox="0 0 24 24"
                      fill="none"
                >
                  <path
                    d="M19 11.2499C19.4142 11.2499 19.75 11.5857 19.75 11.9999C19.75 12.4142 19.4142 12.7499 19 12.7499H5C4.58579 12.7499 4.25 12.4142 4.25 11.9999C4.25 11.5857 4.58579 11.2499 5 11.2499H19Z"
                    fill="white"
                  />
                  <path
                    d="M18.4697 11.4697C18.7626 11.1768 19.2374 11.1768 19.5303 11.4697C19.8232 11.7626 19.8232 12.2373 19.5303 12.5302L13.5303 18.5302C13.2374 18.8231 12.7626 18.8231 12.4697 18.5302C12.1768 18.2373 12.1768 17.7626 12.4697 17.4697L18.4697 11.4697Z"
                    fill="white"
                  />
                  <path
                    d="M12.4697 5.46967C12.7626 5.17678 13.2374 5.17678 13.5303 5.46967L19.5303 11.4697C19.8232 11.7626 19.8232 12.2373 19.5303 12.5302C19.2374 12.8231 18.7626 12.8231 18.4697 12.5302L12.4697 6.53022C12.1768 6.23732 12.1768 5.76256 12.4697 5.46967Z"
                    fill="white"
                  />
                </svg>
            </button>
        </a>
    </div>
    <div class="invalid-feedback">
        <span>
            <svg class="icon icon-circleexclamation-v2">
                <use xlink:href="#icon-circleexclamation-v2"></use>
            </svg>
        </span>
        Please enter a valid email address
    </div>
    <div class="valid-feedback">
        <span>
            <svg class="icon icon-circlecheckfat">
                <use xlink:href="#icon-circlecheckfat"></use>
            </svg>
        </span>
        You are now subscribed to our emails
    </div>
</div>
    </form>
    <div class="legal-text">
        Your email address will be used to send you Health Newsletters and emails about iHerb’s products, services, sales, and special offers. You can unsubscribe at any time by clicking on the unsubscribe link in each email. For more information on our use of your personal information and your rights, see our <a href="info/privacy.html">Privacy Policy.</a>
    </div>
    <div class="recaptcha-branding-text">
        <div>This site is protected by reCAPTCHA and the Google
    <a href="https://policies.google.com/privacy">Privacy Policy</a> and
  <a href="https://policies.google.com/terms">Terms of Service</a> apply.</div>
    </div>
</div>



                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="separator"></div>
        <div>


            <div class="col-xs-24 footer-bottom-text">


                <p>
                    <a href="index.html">iHerb.com</a>
                    &#xA9; Copyright 1997-2026 iHerb, LLC. All rights reserved. iHerb&#xAE; is a registered trademark of iHerb, LLC. *Disclaimer: Statements made, or products sold through this website, have not been evaluated by the United States Food and Drug Administration. They are not intended to diagnose, treat, cure or prevent any disease. PLEASE NOTE that iHerb, LLC is not affiliated&#xA0;with or in any way or manner related to websites that are not specifically iHerb.com.&#xA0; iHerb, LLC is not responsible or liable for products sold or shipped from unauthorized sources.
                    <a href="info/disclaimer.html" class="read-more">
                        Read more&nbsp;&raquo;
                    </a>
                </p>
            </div>
        </div>

        <div>
            <div class="col-xs-24 footer-links">
                <a href="info/privacy.html" data-ga-event="click" data-ga-event-category="Ecommerce"
                   data-ga-event-action="Footer-End-Links" data-ga-event-label="Privacy Policy">
                    Privacy Policy | iHerb
                </a>
                <a href="#" id="teconsent" style="display: none"></a>
                <a href="info/terms-of-use.html" data-ga-event="click" data-ga-event-category="Ecommerce"
                   data-ga-event-action="Footer-End-Links" data-ga-event-label="Terms of Use">
                    Terms of Use | iHerb
                </a>
                <a href="info/accessibility.html" data-ga-event="click" data-ga-event-category="Ecommerce"
                   data-ga-event-action="Footer-End-Links" data-ga-event-label="accessibility">
                    Accessibility
                </a>
            </div>
        </div>
    </div>
</section>

<section class="footer-bottom-images">
    <div class="bottom-container container-fluid">
        <div class="row">

        </div>
    </div>
</section>

<div id="tls-popup" style="display: none">
    <div class="tls-content">
        <div>
            <svg class="icon icon-circleexclamation"><use xlink:href="#icon-circleexclamation"></use></svg>
        </div>
        <div class="tls-browser-upgrade">
            Browser Upgrade Notice
        </div>
        <div class="tls-message-effort">
            As part of our ongoing efforts to improve security for our customers, your current browser version will no longer be supported for iHerb starting 7/1/2018.  Upgrade your existing browser using links below.
        </div>
        <div class="tls-upgrade-browsers">
            <div>
                <a href="https://www.mozilla.org/firefox/new/">
                    <img class="js-defer-image-popup" src="#" data-image-fallback="low" data-image-src="https://cloudinary.images-iherb.com/image/upload/f_auto,q_auto:eco/images/static/i/images/firefox.png" alt="Upgrade to Firefox" />
                    <div>
                        FireFox
                    </div>
                </a>
            </div>
            <div>
                <a href="https://www.google.com/chrome/">
                    <img class="js-defer-image-popup" src="#" data-image-fallback="low" data-image-src="https://cloudinary.images-iherb.com/image/upload/f_auto,q_auto:eco/images/static/i/images/chrome.png" alt="Upgrade to Google" />
                    <div>
                        Chrome
                    </div>
                </a>
            </div>
            <div>
                <a href="https://www.microsoft.com/windows/microsoft-edge">
                    <img class="js-defer-image-popup" src="#" data-image-fallback="low" data-image-src="https://cloudinary.images-iherb.com/image/upload/f_auto,q_auto:eco/images/static/i/images/edge.png" alt="Upgrade to Edge" />
                    <div>
                        Edge
                    </div>
                </a>
            </div>
            <div>
                <a href="https://www.microsoft.com/download/internet-explorer-11-for-windows-7-details.aspx">
                    <img class="js-defer-image-popup" src="#" data-image-fallback="low" data-image-src="https://cloudinary.images-iherb.com/image/upload/f_auto,q_auto:eco/images/static/i/images/ie.png" alt="Upgrade to Internet Explorer" />
                    <div>
                        Internet Explorer
                    </div>
                </a>
            </div>
            <div>
                <a href="https://support.apple.com/downloads/safari">
                    <img class="js-defer-image-popup" src="#" data-image-fallback="low" data-image-src="https://cloudinary.images-iherb.com/image/upload/f_auto,q_auto:eco/images/static/i/images/safari.png" alt="Upgrade to Safari" />
                    <div>
                        Safari
                    </div>
                </a>
            </div>
        </div>
        <button class="btn btn-primary btn-lg remind-me-later">
            <strong>Remind me later</strong>
        </button>
        <div class="do-not-show">
            <a href="index.html">Do not show again</a>
        </div>
    </div>
</div>

</footer>



<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function () {
        var jsFiles = [];


                jsFiles.push("https://s3.images-iherb.com/static/catalog/desktop/iherb/home.min_a6256499905249a58891c5f92e0b1ad5.js");

        function loadJS(file) {
            var script = document.createElement("script");
            script.type = "text/javascript";
            script.src = file;
            document.body.appendChild(script);
        }

        function addBodyScripts() {
            for (var i = 0; i < jsFiles.length; i++) {
                loadJS(jsFiles[i]);
            }
        }

        var head = document.getElementsByTagName("head")[0];
        var script = document.createElement('script');
        if (typeof jQuery == 'undefined') {
            script.type = 'text/javascript';
            script.src = 'https://s3.images-iherb.com/static/js/vendor/jquery-1.12.4.min.js';
            script.onload = addBodyScripts;
            head.appendChild(script);
        }
        else {
            addBodyScripts();
        }
        
        var loaderCheckFinished = setInterval(function () {
            var deferBlock = document.getElementsByClassName("defer-block");
            if (deferBlock.length > 0 && window.getComputedStyle(deferBlock[0])["display"] === "block") {
                // Stops the load spinner once the panel-stack CSS (in grid) is loaded.
                var loadSpinner = document.getElementsByClassName("loader");
                if (loadSpinner.length > 0) {
                    loadSpinner[0].className = "loader";
                    clearInterval(loaderCheckFinished);
                }
            }
        }, 100);
        var loaderCheckFinished2 = setInterval(function () {
            var panelStackBlock = document.getElementsByClassName("panel-stack");
            if (panelStackBlock.length > 0 && window.getComputedStyle(panelStackBlock[0])["display"] === "block") {
                // Stops the load spinner once the panel-stack CSS (in grid) is loaded.
                var loadSpinner = document.getElementsByClassName("loader");
                if (loadSpinner.length > 0) {
                    loadSpinner[0].className = "loader";
                }
                clearInterval(loaderCheckFinished2);
            }
        }, 100);
    });
</script>


    <script>
        window.isPersonalizedPersistentStateEnabled = false;
        window.isVitacostFeatureEnabled = true;
        window.isSelectForYouForVcUsersEnabled = true;
        window.homepageReorderRule = {"reorderList":{"default":["recently-viewed-page-home-top","flash-deals-carousel-wrapper","recommendations","buy-it-again","hp-module-super-deals","hp-module-bogo","hp-module-shop-by-category","hp-selected-for-you","hp-module-shop-by-health-topic","hp-inspired-by","hp-my-list","hp-more-items-to-consider","hp-module-trending","braze-placement-midline_homepage_desktop","brands-of-the-week","hp-module-best-selling","hp-featured-brands","product-highlight-below-the-fold","product-bundle-collections-below-the-fold","hp-module-new-arrivals"],"specialsPrioritizedComponentOrder":["hp-module-super-deals","recently-viewed-page-home-top","recommendations","buy-it-again","hp-module-bogo","hp-module-shop-by-category","hp-selected-for-you","hp-module-shop-by-health-topic","hp-inspired-by","hp-my-list","hp-more-items-to-consider","hp-module-trending","braze-placement-midline_homepage_desktop","brands-of-the-week","hp-module-best-selling","product-highlight-below-the-fold","product-bundle-collections-below-the-fold","hp-featured-brands","hp-module-new-arrivals"],"vitacostComponentOrder":["hp-selected-for-you","recently-viewed-page-home-top","hp-module-vitacost-brands-carousel-container","recommendations","buy-it-again","hp-module-super-deals","hp-module-bogo","hp-module-shop-by-category","hp-module-shop-by-health-topic","hp-inspired-by","hp-my-list","hp-more-items-to-consider","hp-module-trending","braze-placement-midline_homepage_desktop","brands-of-the-week","hp-module-best-selling","product-highlight-below-the-fold","product-bundle-collections-below-the-fold","hp-featured-brands","hp-module-new-arrivals"]}};
    </script>



    <!-- GTM DataLayer -->
    <script>
        window.dataLayer = window.dataLayer || [];
    </script>
            <script src="https://s3.images-iherb.com/static/catalog/desktop/iherb/gtm.min_82a65493bedd105fcc7252fdb4763d52.js"></script>
        <script>
            (function () {
                if (window.ih) {
                    ih.ga.prod.setCurrency('PKR');
                }
            })();
        </script>
            <noscript>
                <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5VJZTKK"
                        height="0" width="0" style="display:none;visibility:hidden"></iframe>
            </noscript>
            <noscript>
                <iframe src="https://gtm-metrics.iherb.com/ns.html?id=GTM-53H8BXM"
                        height="0" width="0" style="display:none;visibility:hidden"></iframe>
            </noscript>

<script>
        var iHerb_Protocol = location.protocol;
        var iHerb_BaseUrl = location.host;
    </script>
<script>(function(){function c(){var b=a.contentDocument||(a.contentWindow&&a.contentWindow.document);if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'a22237537d9d49a0',t:'MTc4NTIyNDIyMA=='};var a=document.createElement('script');a.src='cdn-cgi/challenge-platform/h/g/scripts/jsd/b0da9f4911ba/maind41d.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>


<!-- Mirrored from pk.iherb.com/ by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 28 Jul 2026 08:33:09 GMT -->
</html>